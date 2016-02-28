<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GoogleLink.
 *
 * @ORM\Table(name="google_links")
 * @ORM\Entity
 */
class GoogleLink
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
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $connectionId;

    /**
     * Event id in Google calendar.
     *
     * @var string
     *
     * @ORM\Column()
     */
    private $eventId;

    /**
     * @var string
     *
     * @ORM\Column()
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $calendarId;

    /**
     * JobCalendar.id or NurseryCalendar.id.
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $internalId;

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
     * @return GoogleConnection
     */
    public function getConnectionId()
    {
        return $this->connectionId;
    }

    /**
     * @param int $connectionId
     *
     * @return GoogleLink
     */
    public function setConnectionId($connectionId)
    {
        $this->connectionId = $connectionId;

        return $this;
    }

    /**
     * @return string
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * @param string $eventId
     *
     * @return GoogleLink
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;

        return $this;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return GoogleLink
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getCalendarId()
    {
        return $this->calendarId;
    }

    /**
     * @param int $calendarId
     *
     * @return GoogleLink
     */
    public function setCalendarId($calendarId)
    {
        $this->calendarId = $calendarId;

        return $this;
    }

    /**
     * @return int
     */
    public function getInternalId()
    {
        return $this->internalId;
    }

    /**
     * @param int $internalId
     *
     * @return GoogleLink
     */
    public function setInternalId($internalId)
    {
        $this->internalId = $internalId;

        return $this;
    }
}
