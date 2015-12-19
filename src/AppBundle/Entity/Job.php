<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Job.
 *
 * @ORM\Table(name="jobs", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="jobs_code_unique", columns = {"code"})
 * }))
 * @ORM\Entity
 * @UniqueEntity("code")
 */
class Job
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(length=5)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=5)
     * @Assert\Regex(pattern="/^[A-Z_-]+$/i")
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column()
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time")
     *
     * @Assert\NotBlank()
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time")
     *
     * @Assert\NotBlank()
     */
    private $endTime;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="JobCalendar", mappedBy="job")
     */
    private $jobCalendars;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="JobNursery", mappedBy="job")
     */
    private $nurseries;

    public function __construct()
    {
        $this->jobCalendars = new ArrayCollection();
        $this->nurseries = new ArrayCollection();
        $this->duration = 0;
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
     * Set code.
     *
     * @param string $code
     *
     * @return Job
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Job
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set startTime.
     *
     * @param \DateTime $startTime
     *
     * @return Job
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
     * @return Job
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
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     *
     * @return Job
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Job
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
    public function getJobCalendars()
    {
        return $this->jobCalendars;
    }

    /**
     * @return ArrayCollection
     */
    public function getNurseries()
    {
        return $this->nurseries;
    }

    /**
     * Calculate duration with start en time.
     */
    public function calculateDuration()
    {
        $start = $this->startTime->getTimestamp();
        $end = $this->endTime->getTimestamp();

        if ($start <= $end) {
            $this->duration = ($end - $start) / 60;

            return;
        }

        $start0 = new \DateTime($this->startTime->format('Y-m-d'));
        $end0 = new \DateTime($this->endTime->format('Y-m-d'));
        $end0->add(new \DateInterval('P1D'));

        $this->duration = ($end0->getTimestamp() - $start + $end - $start0->getTimestamp()) / 60;
    }
}
