<?php

namespace Drenso\Shared\Ical;

use BOMO\IcalBundle\Model\Calendar;
use BOMO\IcalBundle\Model\Event;
use BOMO\IcalBundle\Model\Timezone;
use BOMO\IcalBundle\Provider\IcsProvider;
use DateTimeInterface;
use Kigkonsult\Icalcreator\IcalInterface;

/**
 * Class IcalProvider
 */
class IcalProvider
{

  /**
   * IcalProvider constructor.
   */
  public function __construct(protected IcsProvider $provider)
  {
  }

  /**
   * Create an iCal Calendar object with the correct timezone options
   */
  public function createCalendar(string $name, string $description, ?Timezone $tz = NULL): Calendar
  {
    $calendar = $this->provider->createCalendar($tz)
        ->setName($name)
        ->setDescription($description);

    if ($tz && $locationProp = $tz->getTimezone()->getXprop(IcalInterface::X_LIC_LOCATION)) {
      $calendar->getCalendar()->setXprop($locationProp[0], $locationProp[1]);
    }

    return $calendar;
  }

  /**
   * Get the timezone configuration for the Netherlands
   */
  public function nlTimezone(): Timezone
  {
    $tz = $this->provider->createTimezone();
    $tz
        ->setTzid('Europe/Amsterdam')
        ->setStandard([
            'dtstart'      => '19701025T030000',
            'tzoffsetto'   => '+0100',
            'tzoffsetfrom' => '+0200',
            'rrule'        => [
                'freq'    => 'YEARLY',
                'wkst'    => 'SU',
                'byday'   => ['-1', 'SU'],
                'bymonth' => 10,
            ],
            'tzname'       => 'CET',
        ])
        ->setDaylight([
            'dtstart'      => '19700329T020000',
            'tzoffsetto'   => '+0200',
            'tzoffsetfrom' => '+0100',
            'rrule'        => [
                'freq'    => 'YEARLY',
                'wkst'    => 'SU',
                'byday'   => ['-1', 'SU'],
                'bymonth' => 3,
            ],
            'tzname'       => 'CEST',
        ])
        ->setXProp(IcalInterface::X_LIC_LOCATION, $tz->getTzid());

    return $tz;
  }

  /**
   * Create an Event for a Calendar with the correct timezones.
   *
   * @note You will still need to add it to the Calendar with attachEvent($event).
   */
  public function createEvent(
      ?string           $summary,
      ?string           $description,
      DateTimeInterface $start,
      DateTimeInterface $end,
      ?string           $location = NULL): Event
  {
    $event = $this->provider->createEvent();
    $event->getEvent()
        ->setDtstart($start)
        ->setDtend($end)
        ->setSummary($summary)
        ->setDescription($description)
        ->setLocation($location);

    return $event;
  }

  /**
   * Add an alarm to an Event. This must be done before attaching it to a Calendar.
   */
  public function addAlarm(Event $event, string $trigger)
  {
    $alarm  = $event->newAlarm();
    $vEvent = $event->getEvent();
    $alarm
        ->setAction('DISPLAY')
        ->setDescription($vEvent->getSummary() . ' - ' . $vEvent->getDescription())
        ->setTrigger($trigger);
  }

}
