<?php

namespace AppBundle\Model;

/**
 * Class JobReportMonth.
 */
class JobReportMonth
{
    /** @var int */
    private $month;

    /** @var JobReportRow[] */
    private $rows;

    /** @var int */
    private $duration;

    /** @var int */
    private $number;

    /**
     * @param int $month
     */
    public function __construct($month)
    {
        $this->month = $month;
        $this->rows = [];
        $this->duration = 0;
        $this->number = 0;
    }

    public function update()
    {
        $this->duration = 0;
        $this->number = 0;

        foreach ($this->rows as $row) {
            $this->number += $row->getNumber();
            $this->duration += $row->getDurationJob() * $row->getNumber();
        }
    }

    /**
     * @return JobReportRow[]
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param string $codeJob
     * @param string $titleJob
     * @param int    $duration
     *
     * @return JobReportRow
     */
    public function getRow($codeJob, $titleJob, $duration)
    {
        if (-1 === ($index = $this->getIndexRow($codeJob))) {
            $report = new JobReportRow($codeJob, $titleJob, $duration);
            $this->addRow($report);

            return $report;
        }

        return $this->rows[$index];
    }

    /**
     * @param JobReportRow $row
     */
    public function addRow(JobReportRow $row)
    {
        if (in_array($row, $this->rows)) {
            return;
        }

        $this->rows[] = $row;
        usort($this->rows, array($this, 'sortRows'));
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
     * @param string $codeJob
     *
     * @return int
     */
    private function getIndexRow($codeJob)
    {
        foreach ($this->rows as $index => $reportRow) {
            if ($codeJob === $reportRow->getCodeJob()) {
                return $index;
            }
        }

        return -1;
    }

    /**
     * @param JobReportRow $a
     * @param JobReportRow $b
     *
     * @return int
     */
    private function sortRows(JobReportRow $a, JobReportRow $b)
    {
        return strcmp($a->getCodeJob(), $b->getCodeJob());
    }
}
