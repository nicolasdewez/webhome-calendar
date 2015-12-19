<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * JobNursery.
 *
 * @ORM\Table(name="job_nurseries")
 * @ORM\Entity
 */
class JobNursery
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
     * @ORM\ManyToOne(targetEntity="Job", inversedBy="nurseries")
     */
    private $job;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $day;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $serial;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $numberDays;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="JobNurseryPeriod", mappedBy="jobNursery")
     */
    private $periods;

    public function __construct()
    {
        $this->periods = new ArrayCollection();
    }

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
     * @return Job
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param Job $job
     *
     * @return JobNursery
     */
    public function setJob(Job $job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Set day.
     *
     * @param int $day
     *
     * @return JobNursery
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day.
     *
     * @return int
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set serial.
     *
     * @param bool $serial
     *
     * @return JobNursery
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;

        return $this;
    }

    /**
     * Get serial.
     *
     * @return bool
     */
    public function isSerial()
    {
        return $this->serial;
    }

    /**
     * Set numberDays.
     *
     * @param int $numberDays
     *
     * @return JobNursery
     */
    public function setNumberDays($numberDays)
    {
        $this->numberDays = $numberDays;

        return $this;
    }

    /**
     * Get numberDays.
     *
     * @return int
     */
    public function getNumberDays()
    {
        return $this->numberDays;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return JobNursery
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }
}
