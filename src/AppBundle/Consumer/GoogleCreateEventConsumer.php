<?php

namespace AppBundle\Consumer;

use AppBundle\Entity\GoogleLink;
use JMS\Serializer\Exception\RuntimeException;
use Ndewez\WebHome\CalendarApiBundle\V0\Model\GoogleCalendarEvent;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class GoogleCreateEventConsumer.
 */
class GoogleCreateEventConsumer extends AbstractGoogleEventConsumer implements ConsumerInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $msg)
    {
        try {
            /** @var GoogleCalendarEvent $event */
            $event = $this->serializer->deserialize(
                $msg->body,
                'Ndewez\WebHome\CalendarApiBundle\V0\Model\GoogleCalendarEvent',
                'json'
            );
        } catch (RuntimeException $exception) {
            $this->logger->critical(sprintf('Error in deserialize : %s', $msg->body), ['consumer' => 'GoogleCreateEventConsumer']);

            return true;
        }

        $end = null !== $event->getEnd() ?: $event->getStart();

        try {
            $googleId = $this->client->create(
                $event->getConnection(),
                $event->getTitle(),
                true,
                $event->getStart(),
                $end
            );
        } catch (\Google_Exception $exception) {
            $this->logger->critical(sprintf('Error in Google call : %s', $exception->getMessage()), ['consumer' => 'GoogleCreateEventConsumer']);

            return false;
        }

        // Create in Google event table
        $googleLink = new GoogleLink();
        $googleLink
            ->setConnectionId($event->getConnection()->getId())
            ->setType($event->getTypeEvent())
            ->setCalendarId($event->getCalendarId())
            ->setInternalId($event->getInternalId())
            ->setEventId($googleId)
        ;

        $this->manager->persist($googleLink);
        $this->manager->flush();

        return true;
    }
}
