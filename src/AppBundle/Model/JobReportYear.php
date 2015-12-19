<?php

namespace AppBundle\Model;

/**
 * Class JobReportYear.
 */
class JobReportYear
{
    /** @var int */
    private $year;

    /** @var JobReportMonth[] */
    private $months;

    /** @var int */
    private $duration;

    /** @var int */
    private $number;

    /**
     * @param int $year
     */
    public function __construct($year)
    {
        $this->year = $year;
        $this->months = [];
        $this->duration = 0;
        $this->number = 0;
    }

    public function update()
    {
        $this->number = 0;
        $this->duration = 0;

        foreach ($this->months as $month) {
            $month->update();
            $this->number += $month->getNumber();
            $this->duration += $month->getDuration();
        }
    }

    /**
     * @return JobReportMonth[]
     */
    public function getMonths()
    {
        return $this->months;
    }

    /**
     * @param int $month
     *
     * @return JobReportMonth
     */
    public function getMonth($month)
    {
        if (-1 === ($index = $this->getIndexMonth($month))) {
            $report = new JobReportMonth($month);
            $this->addMonth($report);

            return $report;
        }

        return $this->months[$index];
    }

    /**
     * @param JobReportMonth $month
     */
    public function addMonth(JobReportMonth $month)
    {
        if (in_array($month, $this->months)) {
            return;
        }

        $this->months[] = $month;
        usort($this->months, array($this, 'sortMonths'));
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $month
     *
     * @return int
     */
    private function getIndexMonth($month)
    {
        foreach ($this->months as $index => $reportMonth) {
            if ($month === $reportMonth->getMonth()) {
                return $index;
            }
        }

        return -1;
    }

    /**
     * @param JobReportMonth $a
     * @param JobReportMonth $b
     *
     * @return int
     */
    private function sortMonths(JobReportMonth $a, JobReportMonth $b)
    {
        return $a->getMonth() - $b->getMonth();
    }
}
