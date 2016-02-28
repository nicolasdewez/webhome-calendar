<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\GoogleLink;
use AppBundle\Service\Transformer\JobCalendarTransformer;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;
use Ndewez\WebHome\CalendarApiBundle\V0\Model\GoogleCalendarEvent;
use Ndewez\WebHome\CalendarApiBundle\V0\Model\GoogleConnection;
use Ndewez\WebHome\CalendarApiBundle\V0\Model\JobCalendar;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class GoogleJobSubscriber.
 */
class GoogleJobSubscriber implements EventSubscriberInterface
{
    /** @var EntityManager */
    private $manager;

    /** @var Serializer */
    private $serializer;

    /** @var JobCalendarTransformer */
    private $transformer;

    /** @var Producer */
    private $googleCreateProducer;

    /** @var Producer */
    private $googleUpdateProducer;

    /** @var Producer */
    private $googleDeleteProducer;

    /**
     * @param EntityManager          $manager
     * @param Serializer             $serializer
     * @param JobCalendarTransformer $transformer
     * @param Producer               $googleCreateProducer
     * @param Producer               $googleUpdateProducer
     * @param Producer               $googleDeleteProducer
     */
    public function __construct(
        EntityManager $manager,
        Serializer $serializer,
        JobCalendarTransformer $transformer,
        Producer $googleCreateProducer,
        Producer $googleUpdateProducer,
        Producer $googleDeleteProducer
    ) {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->transformer = $transformer;
        $this->googleCreateProducer = $googleCreateProducer;
        $this->googleUpdateProducer = $googleUpdateProducer;
        $this->googleDeleteProducer = $googleDeleteProducer;
    }

    public static function getSubscribedEvents()
    {
        return [
            JobCalendarEvents::CREATED => [['created']],
            JobCalendarEvents::UPDATED => [['updated']],
            JobCalendarEvents::DELETED => [['deleted']],
        ];
    }

    /**
     * @param JobCalendarCreatedEvent $event
     */
    public function created(JobCalendarCreatedEvent $event)
    {
        $model = $event->getModel();

        foreach ($model->getCalendar()->getGoogleConnections() as $connection) {
            $message = new GoogleCalendarEvent();
            $message
                ->setTitle(sprintf('%s (%s)', $model->getJob()->getCode(), $model->getJob()->getTitle()))
                ->setAllDay($connection->isJobDayComplete())
                ->setStart($model->getDate())
                ->setTypeEvent(Calendar::EVENT_TYPE_JOB)
                ->setConnection($connection)
                ->setCalendarId($model->getCalendar()->getId())
                ->setInternalId($model->getId())
            ;

            $this->googleCreateProducer->publish($this->serializer->serialize($message, 'json'));
        }
    }

    /**
     * @param JobCalendarUpdatedEvent $event
     */
    public function updated(JobCalendarUpdatedEvent $event)
    {
        $entity = $event->getModel();
        foreach ($entity->getCalendar()->getGoogleConnections() as $connection) {
            $googleLink = $this->getGoogleLink($connection, $entity);

            if (null === $googleLink) {
                continue;
            }

            $message = new GoogleCalendarEvent();
            $message->setConnection($connection)
                ->setTitle(sprintf('%s (%s)', $entity->getJob()->getCode(), $entity->getJob()->getTitle()))
                ->setAllDay($connection->isJobDayComplete())
                ->setStart($entity->getDate())
                ->setLinkId($googleLink->getId())
            ;

            $this->googleUpdateProducer->publish($this->serializer->serialize($message, 'json'));
        }
    }

    /**
     * @param JobCalendarDeletedEvent $event
     */
    public function deleted(JobCalendarDeletedEvent $event)
    {
        $entity = $event->getEntity();

        foreach ($entity->getCalendar()->getGoogleConnections() as $connection) {
            $googleLink = $this->getGoogleLink($connection, $entity);

            if (null === $googleLink) {
                continue;
            }

            $message = new GoogleCalendarEvent();
            $message->setConnection($connection)
                ->setLinkId($googleLink->getId());

            $this->googleDeleteProducer->publish($this->serializer->serialize($message, 'json'));
        }
    }

    /**
     * @param GoogleConnection $connection
     * @param JobCalendar      $model
     *
     * @return GoogleLink
     */
    private function getGoogleLink(GoogleConnection $connection, JobCalendar $model)
    {
        return $this->manager->getRepository('AppBundle:GoogleLink')->findOneBy([
            'connectionId' => $connection->getId(),
            'type' => Calendar::EVENT_TYPE_JOB,
            'calendarId' => $model->getCalendar()->getId(),
            'internalId' => $model->getId(),
        ]);
    }
}
