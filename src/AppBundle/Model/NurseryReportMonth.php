<?php

namespace AppBundle\Model;

/**
 * Class NurseryReportMonth.
 */
class NurseryReportMonth
{
    /** @var int */
    private $month;

    /** @var int */
    private $duration;

    /** @var int */
    private $numberMeal;

    /**
     * @param int $month
     */
    public function __construct($month)
    {
        $this->month = $month;
        $this->duration = 0;
        $this->numberMeal = 0;
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @return int
     */
    public function getNumberMeal()
    {
        return $this->numberMeal;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int  $duration
     * @param bool $meal
     */
    public function addPeriod($duration, $meal)
    {
        $this->duration += $duration;
        if ($meal) {
            $this->numberMeal++;
        }
    }
}
