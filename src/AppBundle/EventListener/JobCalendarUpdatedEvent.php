<?php

namespace AppBundle\EventListener;

use Ndewez\WebHome\CalendarApiBundle\V0\Model\JobCalendar;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class JobCalendarUpdatedEvent.
 */
class JobCalendarUpdatedEvent extends Event
{
    /** @var JobCalendar */
    private $model;

    /**
     * @param JobCalendar $entity
     */
    public function __construct(JobCalendar $entity)
    {
        $this->model = $entity;
    }

    /**
     * @return JobCalendar
     */
    public function getModel()
    {
        return $this->model;
    }
}
