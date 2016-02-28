<?php

namespace AppBundle\EventListener;

use Ndewez\WebHome\CalendarApiBundle\V0\Model\JobCalendar;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class JobCalendarDeletedEvent.
 */
class JobCalendarDeletedEvent extends Event
{
    /** @var JobCalendar */
    private $jobCalendar;

    /**
     * @param JobCalendar $jobCalendar
     */
    public function __construct(JobCalendar $jobCalendar)
    {
        $this->jobCalendar = $jobCalendar;
    }

    /**
     * @return JobCalendar
     */
    public function getEntity()
    {
        return $this->jobCalendar;
    }
}
