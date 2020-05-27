<?php

namespace Drenso\Shared\Ical;

use BOMO\IcalBundle\Model\Calendar;
use BOMO\IcalBundle\Model\Event;
use BOMO\IcalBundle\Model\Timezone;
use BOMO\IcalBundle\Provider\IcsProvider;
use DateTimeInterface;

/**
 * Class IcalProvider
 */
class IcalProvider
{

  /** @var IcsProvider */
  protected $provider;

  /**
   * IcalProvider constructor.
   *
   * @param IcsProvider $provider
   */
  public function __construct(IcsProvider $provider)
  {
    $this->provider = $provider;
  }

  /**
   * Create an iCal Calendar object with the correct timezone options
   *
   * @param string        $name        Name of the Calendar
   * @param string        $description Description for the Calendar
   * @param Timezone|null $tz          The timezone for the Calendar
   *
   * @return Calendar
   */
  public function createCalendar(string $name, string $description, ?Timezone $tz = NULL): Calendar
  {
    return $this->provider->createCalendar($tz)
        ->setName($name)
        ->setDescription($description);
  }

  /**
   * Get the timezone configuration for the Netherlands
   *
   * @return Timezone
   */
  public function nlTimezone(): Timezone
  {
    $tz = $this->provider->createTimezone();
    $tz
        ->setTzid('Europe/Amsterdam')
        ->setProperty('X-LIC-LOCATION', $tz->getTzid())
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
        ]);

    return $tz;
  }

  /**
   * Create an Event for a Calendar with the correct timezones.
   *
   * @note You will still need to add it to the Calendar with attachEvent($event).
   *
   * @param string            $name        Name of the event
   * @param string            $description Description for the event
   * @param DateTimeInterface $start       Start of the event
   * @param DateTimeInterface $end         End of the event
   *
   * @return Event
   */
  public function createEvent(string $name, string $description, DateTimeInterface $start, DateTimeInterface $end): Event
  {
    $event = $this->provider->createEvent();
    $event->getEvent()->setDtstart($start->format('Y'), $start->format('m'), $start->format('d'), $start->format('H'), $start->format('i'), 0, 'Europe/Amsterdam');
    $event->getEvent()->setDtend($end->format('Y'), $end->format('m'), $end->format('d'), $end->format('H'), $end->format('i'), 0, 'Europe/Amsterdam');
    $event
        ->setName($name)
        ->setDescription($description);

    return $event;
  }

  /**
   * Add an alarm to an Event. This must be done before attaching it to a Calendar.
   *
   * @param Event  $event   Event to set the alarm for
   * @param string $trigger DateInterval of trigger
   */
  public function addAlarm(Event &$event, string $trigger)
  {
    $alarm = $event->newAlarm();
    $alarm
        ->setAction('DISPLAY')
        ->setDescription($event->getProperty('summary') . ' - ' . $event->getProperty('description'))
        ->setTrigger($trigger);
  }

}
