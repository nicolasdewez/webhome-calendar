<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NurseryCalendar.
 *
 * @ORM\Table(name="nursery_calendars")
 * @ORM\Entity
 */
class NurseryCalendar
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Job
     *
     * @ORM\ManyToOne(targetEntity="Calendar", inversedBy="nurseryCalendars")
     */
    private $calendar;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time")
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time")
     */
    private $endTime;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $meal;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set calendar.
     *
     * @param Calendar $calendar
     *
     * @return NurseryCalendar
     */
    public function setCalendar(Calendar $calendar)
    {
        $this->calendar = $calendar;

        return $this;
    }

    /**
     * Get calendar.
     *
     * @return Calendar
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return NurseryCalendar
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set startTime.
     *
     * @param \DateTime $startTime
     *
     * @return NurseryCalendar
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime.
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime.
     *
     * @param \DateTime $endTime
     *
     * @return NurseryCalendar
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime.
     *
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set meal.
     *
     * @param bool $meal
     *
     * @return NurseryCalendar
     */
    public function setMeal($meal)
    {
        $this->meal = $meal;

        return $this;
    }

    /**
     * Get meal.
     *
     * @return bool
     */
    public function isMeal()
    {
        return $this->meal;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        $start = $this->startTime->getTimestamp();
        $end = $this->endTime->getTimestamp();

        if ($start <= $end) {
            return ($end - $start) / 60;
        }

        $start0 = new \DateTime($this->startTime->format('Y-m-d'));
        $end0 = new \DateTime($this->endTime->format('Y-m-d'));
        $end0->add(new \DateInterval('P1D'));

        return ($end0->getTimestamp() - $start + $end - $start0->getTimestamp()) / 60;
    }
}
