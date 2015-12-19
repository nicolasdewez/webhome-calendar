<?php

namespace AppBundle\Model;

/**
 * Class JobReportRow.
 */
class JobReportRow
{
    /** @var string */
    private $codeJob;

    /** @var string */
    private $titleJob;

    /** @var int */
    private $durationJob;

    /** @var int */
    private $number;

    /**
     * @param string $codeJob
     * @param string $titleJob
     * @param int    $durationJob
     */
    public function __construct($codeJob, $titleJob, $durationJob)
    {
        $this->codeJob = $codeJob;
        $this->titleJob = $titleJob;
        $this->durationJob = $durationJob;
        $this->number = 0;
    }

    /**
     * @return string
     */
    public function getCodeJob()
    {
        return $this->codeJob;
    }

    /**
     * @return string
     */
    public function getTitleJob()
    {
        return $this->titleJob;
    }

    /**
     * @return int
     */
    public function getDurationJob()
    {
        return $this->durationJob;
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
        return $this->number * $this->durationJob;
    }

    /**
     * Add one unit.
     */
    public function add()
    {
        $this->number++;
    }
}
