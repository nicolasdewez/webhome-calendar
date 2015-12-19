<?php

namespace AppBundle\Model;

/**
 * Class NurseryReportYear.
 */
class NurseryReportYear
{
    /** @var int */
    private $year;

    /** @var NurseryReportMonth[] */
    private $months;

    /** @var int */
    private $duration;

    /** @var int */
    private $numberMeal;

    /**
     * @param int $year
     */
    public function __construct($year)
    {
        $this->year = $year;
        $this->months = [];
        $this->duration = 0;
        $this->numberMeal = 0;
    }

    public function update()
    {
        $this->numberMeal = 0;
        $this->duration = 0;

        foreach ($this->months as $month) {
            $this->numberMeal += $month->getNumberMeal();
            $this->duration += $month->getDuration();
        }
    }

    /**
     * @return NurseryReportMonth[]
     */
    public function getMonths()
    {
        return $this->months;
    }

    /**
     * @param int $month
     *
     * @return NurseryReportMonth
     */
    public function getMonth($month)
    {
        if (-1 === ($index = $this->getIndexMonth($month))) {
            $report = new NurseryReportMonth($month);
            $this->addMonth($report);

            return $report;
        }

        return $this->months[$index];
    }

    /**
     * @param NurseryReportMonth $month
     */
    public function addMonth(NurseryReportMonth $month)
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
     * @param NurseryReportMonth $a
     * @param NurseryReportMonth $b
     *
     * @return int
     */
    private function sortMonths(NurseryReportMonth $a, NurseryReportMonth $b)
    {
        return $a->getMonth() - $b->getMonth();
    }
}
