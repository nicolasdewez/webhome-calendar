<?php

namespace AppBundle\Consumer;

use AppBundle\Entity\GoogleLink;
use JMS\Serializer\Exception\RuntimeException;
use Ndewez\WebHome\CalendarApiBundle\V0\Model\GoogleCalendarEvent;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class GoogleUpdateEventConsumer.
 */
class GoogleUpdateEventConsumer extends AbstractGoogleEventConsumer implements ConsumerInterface
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
            $this->logger->critical(sprintf('Error in deserialize : %s', $msg->body), ['consumer' => 'GoogleUpdateEventConsumer']);

            return true;
        }

        // Get GoogleLink for google internal id
        $googleLink = $this->manager->getRepository('AppBundle:GoogleLink')->find($event->getLinkId());

        $end = null !== $event->getEnd() ?: $event->getStart();

        try {
            $this->client->update(
                $event->getConnection(),
                $googleLink->getEventId(),
                $event->getTitle(),
                true,
                $event->getStart(),
                $end
            );
        } catch (\Google_Exception $exception) {
            $this->logger->critical(sprintf('Error in Google call : %s', $exception->getMessage()), ['consumer' => 'GoogleUpdateEventConsumer']);

            return false;
        }

        return true;
    }
}
