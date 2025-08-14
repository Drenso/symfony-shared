<?php

namespace Drenso\Shared\Ical;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Drenso\Shared\Helper\DateTimeHelper;
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
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class IcalBuilder
{
  private readonly Calendar $cal;

  private ?DateTimeInterface $beginDateTime = null;
  private ?DateTimeInterface $endDateTime   = null;

  /**
   * @param DateInterval|null $publishedTTL      Set to indicate a download frequency (@see http://msdn.microsoft.com/en-us/library/ee178699(v=exchg.80).aspx)
   * @param string|null       $productIdentifier Product identifier to be used in the export, auto padded with `-//` and `//`
   */
  public function __construct(
    private readonly string $domain,
    private readonly string $name,
    private readonly string $description,
    private readonly ?DateTimeZone $timeZone = null,
    private readonly bool $includeTimeZoneInformation = true,
    ?DateInterval $publishedTTL = null,
    ?string $productIdentifier = null)
  {
    if (!class_exists(Calendar::class)) {
      throw new InvalidConfigurationException('In order to use the IcalBuilder, the iCal library needs to be installed. Try running `composer req eluceo/ical`.');
    }

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
   */
  public function addEvent(
    ?string $identifier,
    string $summary,
    DateTimeInterface $start,
    DateTimeInterface $end,
    ?string $description = null,
    ?string $location = null,
    DateInterval|string|null $alarmTrigger = null,
    ?DateTimeInterface $touchedAt = null): Event
  {
    $identifier = sprintf('%s@%s', $identifier ?: bin2hex(random_bytes(16)), $this->domain);

    if ($this->timeZone) {
      $start = DateTimeHelper::convertTo($start, $this->timeZone);
      $end   = DateTimeHelper::convertTo($end, $this->timeZone);
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
      // Parse the value and make sure to use an inverted duration to place the trigger before event start
      $duration = is_string($alarmTrigger) ? new DateInterval($alarmTrigger) : $alarmTrigger;

      $days    = abs($duration->days ?: 0);
      $hours   = abs($duration->h);
      $minutes = abs($duration->i);
      $seconds = abs($duration->s);
      if ($days + $hours + $minutes + $seconds === 0) {
        // iCal bug, see https://github.com/markuspoerschke/iCal/pull/683
        // So, fall back to a single second
        $duration = new DateInterval('PT1S');
      }

      $duration->invert = 1;
      $event->addAlarm(new Alarm(
        new DisplayAction($description
            ? sprintf('%s - %s', $summary, $description)
            : $summary
        ),
        new RelativeTrigger($duration)
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
}
