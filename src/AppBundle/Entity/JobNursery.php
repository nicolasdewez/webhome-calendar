<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
     *
     * @Assert\NotNull
     */
    private $job;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     *
     * @Assert\Range(min=1, max=7)
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
     *
     * @Assert\Range(min=0)
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
     * @ORM\OneToMany(targetEntity="JobNurseryPeriod", mappedBy="jobNursery", cascade={"persist", "remove"})
     *
     * @Assert\Valid(traverse=true)
     */
    private $periods;

    public function __construct()
    {
        $this->periods = new ArrayCollection();
        $this->numberDays = 0;
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

    /**
     * @return ArrayCollection
     */
    public function getPeriods()
    {
        return $this->periods;
    }

    /**
     * @param JobNurseryPeriod $period
     */
    public function addPeriod(JobNurseryPeriod $period)
    {
        if ($this->periods->contains($period)) {
            return;
        }

        $this->periods->add($period);
        $period->setJobNursery($this);
    }

    /**
     * @param JobNurseryPeriod $period
     */
    public function removePeriod(JobNurseryPeriod $period)
    {
        $this->periods->removeElement($period);
        $period->setJobNursery();
    }

    /**
     * @param ExecutionContextInterface $context
     *
     * @Assert\Callback
     */
    public function validateSerial(ExecutionContextInterface $context)
    {
        if ($this->isSerial() && $this->getNumberDays() <= 0) {
            $context->buildViolation('job_nursery.number_days')
                ->atPath('numberDays')
                ->addViolation();
        }
    }
}
