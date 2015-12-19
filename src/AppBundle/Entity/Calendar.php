<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Calendar.
 *
 * @ORM\Table(name="calendars")
 * @ORM\Entity
 */
class Calendar
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
     * @var string
     *
     * @ORM\Column()
     */
    private $title;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="JobCalendar", mappedBy="calendar")
     */
    private $jobCalendars;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="NurseryCalendar", mappedBy="calendar")
     */
    private $nurseryCalendars;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="GoogleCalendar", mappedBy="calendar")
     */
    private $googleCalendars;

    public function __construct()
    {
        $this->jobCalendars = new ArrayCollection();
        $this->nurseryCalendars = new ArrayCollection();
        $this->googleCalendars = new ArrayCollection();
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
     * Set title.
     *
     * @param string $title
     *
     * @return Calendar
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
     * Set active.
     *
     * @param bool $active
     *
     * @return Calendar
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
    public function getNurseryCalendars()
    {
        return $this->nurseryCalendars;
    }

    /**
     * @return ArrayCollection
     */
    public function getGoogleCalendars()
    {
        return $this->googleCalendars;
    }
}
