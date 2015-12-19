<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * GoogleCalendar.
 *
 * @ORM\Table(name="google_calendars", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="google_calendars_google_unique", columns = {"client_id", "project_id", "internal_id"})
 * }))
 * @ORM\Entity
 * @UniqueEntity({"clientId", "projectId", "internalId"})
 */
class GoogleCalendar
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
     * @var Calendar
     *
     * @ORM\ManyToOne(targetEntity="Calendar", inversedBy="googleCalendars")
     */
    private $calendar;

    /**
     * @var string
     *
     * @ORM\Column()
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column()
     */
    private $clientId;

    /**
     * @var string
     *
     * @ORM\Column()
     */
    private $clientSecret;

    /**
     * @var string
     *
     * @ORM\Column()
     */
    private $projectId;

    /**
     * @var string
     *
     * @ORM\Column()
     */
    private $internalId;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $active;

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
     * Set calendar.
     *
     * @param Calendar $calendar
     *
     * @return GoogleCalendar
     */
    public function setCalendar(Calendar $calendar)
    {
        $this->calendar = $calendar;

        return $this;
    }

    /**
     * Get calendar.
     *
     * @return Calendar
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return GoogleCalendar
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
     * Set clientId.
     *
     * @param string $clientId
     *
     * @return GoogleCalendar
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get clientId.
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set clientSecret.
     *
     * @param string $clientSecret
     *
     * @return GoogleCalendar
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * Get clientSecret.
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Set projectId.
     *
     * @param string $projectId
     *
     * @return GoogleCalendar
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;

        return $this;
    }

    /**
     * Get projectId.
     *
     * @return string
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Set internalId.
     *
     * @param string $internalId
     *
     * @return GoogleCalendar
     */
    public function setInternalId($internalId)
    {
        $this->internalId = $internalId;

        return $this;
    }

    /**
     * Get calendarId.
     *
     * @return string
     */
    public function getInternalId()
    {
        return $this->internalId;
    }
    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return GoogleCalendar
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
