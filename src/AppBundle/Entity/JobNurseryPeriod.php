<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * JobNurseryPeriod.
 *
 * @ORM\Table(name="job_nursery_periods")
 * @ORM\Entity
 */
class JobNurseryPeriod
{
    const TYPE_SAME_DAY = 1;
    const TYPE_NEXT_DAY = 2;
    const TYPE_SERIAL_DAY = 3;
    const TYPE_SERIAL_DURING = 4;
    const TYPE_SERIAL_AFTER = 5;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var JobNursery
     *
     * @ORM\ManyToOne(targetEntity="JobNursery", inversedBy="periods")
     */
    private $jobNursery;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time")
     *
     * @Assert\Time
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time")
     *
     * @Assert\Time
     */
    private $endTime;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     *
     * @Assert\Range(min=1, max=5)
     */
    private $type;

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
     * @return JobNursery
     */
    public function getJobNursery()
    {
        return $this->jobNursery;
    }

    /**
     * @param JobNursery $jobNursery
     *
     * @return JobNurseryPeriod
     */
    public function setJobNursery(JobNursery $jobNursery = null)
    {
        $this->jobNursery = $jobNursery;

        return $this;
    }

    /**
     * Set startTime.
     *
     * @param \DateTime $startTime
     *
     * @return JobNurseryPeriod
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
     * @return JobNurseryPeriod
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
     * Set type.
     *
     * @param int $type
     *
     * @return JobNurseryPeriod
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set meal.
     *
     * @param bool $meal
     *
     * @return JobNurseryPeriod
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
     * @return bool
     */
    public function isSerialType()
    {
        return $this->type >= self::TYPE_SERIAL_DAY;
    }

    /**
     * @param ExecutionContextInterface $context
     *
     * @Assert\Callback
     */
    public function validateTime(ExecutionContextInterface $context)
    {
        $start = $this->startTime->getTimestamp();
        $end = $this->endTime->getTimestamp();

        if ($end < $start) {
            $context->buildViolation('job_nursery.time')
                ->atPath('startTime')
                ->addViolation();
        }
    }

    /**
     * @param ExecutionContextInterface $context
     *
     * @Assert\Callback
     */
    public function validatePeriods(ExecutionContextInterface $context)
    {
        if ($this->jobNursery->isSerial() !== $this->isSerialType()) {
            $context->buildViolation('job_nursery.period_serial')
                ->atPath('type')
                ->addViolation();
        }
    }
}
