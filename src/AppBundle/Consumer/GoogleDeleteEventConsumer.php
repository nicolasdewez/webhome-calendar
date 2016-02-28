<?php

namespace AppBundle\Consumer;

use JMS\Serializer\Exception\RuntimeException;
use Ndewez\WebHome\CalendarApiBundle\V0\Model\GoogleCalendarEvent;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class GoogleDeleteEventConsumer.
 */
class GoogleDeleteEventConsumer extends AbstractGoogleEventConsumer implements ConsumerInterface
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
            $this->logger->critical(sprintf('Error in deserialize : %s', $msg->body), ['consumer' => 'GoogleDeleteEventConsumer']);

            return true;
        }

        // Get GoogleLink for google internal id
        $googleLink = $this->manager->getRepository('AppBundle:GoogleLink')->find($event->getLinkId());

        try {
            $this->client->delete($event->getConnection(), $googleLink->getEventId());
        } catch (\Google_Exception $exception) {
            $this->logger->critical(sprintf('Error in Google call : %s', $exception->getMessage()), ['consumer' => 'GoogleDeleteEventConsumer']);

            return false;
        }

        // Delete in Googlelink table
        if (null !== $googleLink) {
            $this->manager->remove($googleLink);
            $this->manager->flush();
        }

        return true;
    }
}
