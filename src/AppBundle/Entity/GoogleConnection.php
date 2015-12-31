<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * GoogleConnection.
 *
 * @ORM\Table(name="google_connections", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="google_connections_google_unique", columns = {"client_id", "project_id", "internal_id"})
 * }))
 * @ORM\Entity
 * @UniqueEntity({"clientId", "projectId", "internalId"})
 */
class GoogleConnection
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
    private $jobDayComplete;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $nurseryDayComplete;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Calendar", mappedBy="googleConnections")
     */
    private $calendars;

    public function __construct()
    {
        $this->calendars = new ArrayCollection();
        $this->jobDayComplete = true;
        $this->nurseryDayComplete = false;
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
     * Get calendars.
     *
     * @return Calendar
     */
    public function getCalendars()
    {
        return $this->calendars;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return GoogleConnection
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
     * @return GoogleConnection
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
     * @return GoogleConnection
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
     * @return GoogleConnection
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
     * @return GoogleConnection
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
     * Set jobDayComplete.
     *
     * @param bool $jobDayComplete
     *
     * @return GoogleConnection
     */
    public function setJobDayComplete($jobDayComplete)
    {
        $this->jobDayComplete = $jobDayComplete;

        return $this;
    }

    /**
     * Get jobDayComplete.
     *
     * @return bool
     */
    public function isJobDayComplete()
    {
        return $this->jobDayComplete;
    }

    /**
     * Set nurseryDayComplete.
     *
     * @param bool $nurseryDayComplete
     *
     * @return GoogleConnection
     */
    public function setNurseryDayComplete($nurseryDayComplete)
    {
        $this->nurseryDayComplete = $nurseryDayComplete;

        return $this;
    }

    /**
     * Get nurseryDayComplete.
     *
     * @return bool
     */
    public function isNurseryDayComplete()
    {
        return $this->nurseryDayComplete;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return GoogleConnection
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
