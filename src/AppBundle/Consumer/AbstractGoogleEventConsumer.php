<?php

namespace AppBundle\Consumer;

use AppBundle\Service\GoogleCalendarClient;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class AbstractGoogleEventConsumer.
 */
abstract class AbstractGoogleEventConsumer
{
    /** @var Serializer $serializer */
    protected $serializer;

    /** @var GoogleCalendarClient */
    protected $client;

    /** @var EntityManager */
    protected $manager;

    /** @var Logger */
    protected $logger;

    /**
     * @param Serializer           $serializer
     * @param GoogleCalendarClient $client
     * @param EntityManager        $manager
     * @param Logger               $logger
     */
    public function __construct(Serializer $serializer, GoogleCalendarClient $client, EntityManager $manager, Logger $logger)
    {
        $this->serializer = $serializer;
        $this->client = $client;
        $this->manager = $manager;
        $this->logger = $logger;
    }
}
