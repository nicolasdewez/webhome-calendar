<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=255)
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
     * @ORM\ManyToMany(targetEntity="GoogleConnection", inversedBy="calendars")
     */
    private $googleConnections;

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

    public function __construct()
    {
        $this->jobCalendars = new ArrayCollection();
        $this->nurseryCalendars = new ArrayCollection();
        $this->googleConnections = new ArrayCollection();
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
    public function getGoogleConnections()
    {
        return $this->googleConnections;
    }

    /**
     * @param GoogleConnection $googleConnection
     */
    public function addGoogleConnection(GoogleConnection $googleConnection)
    {
        if ($this->googleConnections->contains($googleConnection)) {
            return;
        }

        $this->googleConnections->add($googleConnection);
    }

    /**
     * @param GoogleConnection $googleConnection
     */
    public function removeGoogleConnection(GoogleConnection $googleConnection)
    {
        $this->googleConnections->removeElement($googleConnection);
    }
}
