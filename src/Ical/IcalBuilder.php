<?php

namespace Drenso\Shared\Ical;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\Entity\TimeZone;
use Eluceo\iCal\Domain\ValueObject\Alarm;
use Eluceo\iCal\Domain\ValueObject\Alarm\DisplayAction;
use Eluceo\iCal\Domain\ValueObject\Alarm\RelativeTrigger;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Domain\ValueObject\Timestamp;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use Eluceo\iCal\Presentation\Component\Property;
use Eluceo\iCal\Presentation\Component\Property\Value\TextValue;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Exception;
use RuntimeException;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class IcalBuilder
{
  /** @var Calendar */
  private $cal;
  /** @var DateTimeInterface|null */
  private $beginDateTime = null;
  /** @var DateTimeInterface|null */
  private $endDateTime   = null;
  /** @var string */
  private $domain;
  /** @var string */
  private $name;
  /** @var string */
  private $description;
  /** @var DateTimeZone|null */
  private $timeZone;
  /** @var bool */
  private $includeTimeZoneInformation;

  /**
   * @param DateInterval|null $publishedTTL      Set to indicate a download frequency (@see http://msdn.microsoft.com/en-us/library/ee178699(v=exchg.80).aspx)
   * @param string|null       $productIdentifier Product identifier to be used in the export, auto padded with `-//` and `//`
   */
  public function __construct(
      string $domain,
      string $name,
      string $description,
      ?DateTimeZone $timeZone = null,
      bool $includeTimeZoneInformation = true,
      ?DateInterval $publishedTTL = null,
      ?string $productIdentifier = null)
  {
    if (!class_exists(Calendar::class)) {
      throw new InvalidConfigurationException('In order to use the IcalBuilder, the iCal library needs to be installed. Try running `composer req eluceo/ical`.');
    }

    $this->domain = $domain;
    $this->name = $name;
    $this->description = $description;
    $this->timeZone = $timeZone;
    $this->includeTimeZoneInformation = $includeTimeZoneInformation;

    $this->cal = new Calendar();

    if ($publishedTTL) {
      $this->cal->setPublishedTTL($publishedTTL);
    }

    if ($productIdentifier) {
      $this->cal->setProductIdentifier('-//' . $productIdentifier . '//');
    }
  }

  /**
   * Adds an event to the calendar.
   * The bound event instance is returned for further processing if necessary.
   *
   * @param DateInterval|string|null $alarmTrigger
   * @throws Exception
   */
  public function addEvent(
      ?string $identifier,
      string $summary,
      DateTimeInterface $start,
      DateTimeInterface $end,
      ?string $description = null,
      ?string $location = null,
      $alarmTrigger = null,
      ?DateTimeInterface $touchedAt = null): Event
  {
    $identifier = sprintf('%s@%s', $identifier ?: bin2hex(random_bytes(16)), $this->domain);

    if ($this->timeZone) {
      $start = self::convertTo($start, $this->timeZone);
      $end   = self::convertTo($end, $this->timeZone);
    }

    $event = (new Event(new UniqueIdentifier($identifier)))
        ->setSummary($summary)
        ->setOccurrence(new TimeSpan(
            new DateTime($start, $this->includeTimeZoneInformation),
            new DateTime($end, $this->includeTimeZoneInformation),
        ));

    if ($description) {
      $event->setDescription($description);
    }

    if ($location) {
      $event->setLocation(new Location($location));
    }

    if ($alarmTrigger) {
      $event->addAlarm(new Alarm(
          new DisplayAction($description
              ? sprintf('%s - %s', $summary, $description)
              : $summary
          ),
          new RelativeTrigger(is_string($alarmTrigger)
              ? new DateInterval($alarmTrigger)
              : $alarmTrigger
          )
      ));
    }

    if ($touchedAt) {
      $event->touch(new Timestamp($touchedAt));
    }

    // Update the calendar time span
    if ($this->beginDateTime === null || $start < $this->beginDateTime) {
      $this->beginDateTime = $start;
    }
    if ($this->endDateTime === null || $end > $this->endDateTime) {
      $this->endDateTime = $end;
    }

    $this->cal->addEvent($event);

    return $event;
  }

  public function getCalendar(): string
  {
    if ($this->timeZone && $this->includeTimeZoneInformation) {
      $this->cal->addTimeZone(TimeZone::createFromPhpDateTimeZone(
          $this->timeZone,
          $this->beginDateTime ?? new DateTimeImmutable(),
          $this->endDateTime ?? new DateTimeImmutable(),
      ));
    }

    $component = (new CalendarFactory())->createCalendar($this->cal);

    return $component
        ->withProperty(new Property('X-WR-CALNAME', new TextValue($this->name)))
        ->withProperty(new Property('X-WR-CALDESC', new TextValue($this->description)))
        ->__toString();
  }

  private static function convertTo(DateTimeInterface $dateTime, ?DateTimeZone $timezone): DateTimeInterface
  {
    if (PHP_MAJOR_VERSION >= 8) {
      $converted = \DateTime::createFromInterface($dateTime);
    } else if ($dateTime instanceof \DateTime) {
      $converted = (clone $dateTime);
    } else if ($dateTime instanceof DateTimeImmutable) {
      $converted = $dateTime;
    } else {
      throw new RuntimeException('Local timezone could not be set');
    }

    return $timezone === null ? $converted : $converted->setTimezone($timezone);
  }
}
