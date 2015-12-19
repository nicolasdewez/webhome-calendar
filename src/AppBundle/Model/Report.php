<?php

namespace AppBundle\Model;

/**
 * Class Report.
 */
class Report
{
    /** @var JobReportYear[] */
    private $jobReportYears;

    /** @var NurseryReportYear[] */
    private $nurseryReportYears;

    public function __construct()
    {
        $this->jobReportYears = [];
        $this->nurseryReportYears = [];
    }

    public function update()
    {
        foreach ($this->jobReportYears as $report) {
            $report->update();
        }

        foreach ($this->nurseryReportYears as $report) {
            $report->update();
        }
    }

    /**
     * @return JobReportYear[]
     */
    public function getJobReportYears()
    {
        return $this->jobReportYears;
    }

    /**
     * @param int $year
     *
     * @return JobReportYear
     */
    public function getJobReportYear($year)
    {
        if (-1 === ($index = $this->getIndexJobReportYear($year))) {
            $report = new JobReportYear($year);
            $this->addJobReportYear($report);

            return $report;
        }

        return $this->jobReportYears[$index];
    }

    /**
     * @param JobReportYear $report
     */
    public function addJobReportYear(JobReportYear $report)
    {
        if (in_array($report, $this->jobReportYears)) {
            return;
        }

        $this->jobReportYears[] = $report;
        usort($this->jobReportYears, array($this, 'sortJobReportYears'));
    }

    /**
     * @return NurseryReportYear[]
     */
    public function getNurseryReportYears()
    {
        return $this->nurseryReportYears;
    }

    /**
     * @param int $year
     *
     * @return NurseryReportYear
     */
    public function getNurseryReportYear($year)
    {
        if (-1 === ($index = $this->getIndexNurseryReportYear($year))) {
            $report = new NurseryReportYear($year);
            $this->addNurseryReportYear($report);

            return $report;
        }

        return $this->nurseryReportYears[$index];
    }

    /**
     * @param NurseryReportYear $report
     */
    public function addNurseryReportYear(NurseryReportYear $report)
    {
        if (in_array($report, $this->nurseryReportYears)) {
            return;
        }

        $this->nurseryReportYears[] = $report;
        usort($this->nurseryReportYears, array($this, 'sortNurseryReportYears'));
    }

    /**
     * @param int $year
     *
     * @return int
     */
    private function getIndexJobReportYear($year)
    {
        foreach ($this->jobReportYears as $index => $jobReportYear) {
            if ($year === $jobReportYear->getYear()) {
                return $index;
            }
        }

        return -1;
    }

    /**
     * @param JobReportYear $a
     * @param JobReportYear $b
     *
     * @return int
     */
    private function sortJobReportYears(JobReportYear $a, JobReportYear $b)
    {
        return $a->getYear() - $b->getYear();
    }

    /**
     * @param int $year
     *
     * @return int
     */
    private function getIndexNurseryReportYear($year)
    {
        foreach ($this->nurseryReportYears as $index => $nurseryReportYear) {
            if ($year === $nurseryReportYear->getYear()) {
                return $index;
            }
        }

        return -1;
    }

    /**
     * @param NurseryReportYear $a
     * @param NurseryReportYear $b
     *
     * @return int
     */
    private function sortNurseryReportYears(NurseryReportYear $a, NurseryReportYear $b)
    {
        return $a->getYear() - $b->getYear();
    }
}
