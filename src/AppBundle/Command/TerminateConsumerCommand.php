<?php

namespace AppBundle\Command;

use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TerminateConsumerCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('app:consumer:terminate');
        $this->addArgument('consumer', InputArgument::REQUIRED, 'Consumer name');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $consumer = $input->getArgument('consumer');
        $producerName = sprintf('old_sound_rabbit_mq.%s_producer', $consumer);
        if (!$this->getContainer()->has($producerName)) {
            return $output->writeln(sprintf('<error>Consumer %s not found or its producer</error>', $consumer));
        }

        $pids = $this->getConsumerPids($consumer);
        if (count($pids) === 0) {
            return $output->writeln('<comment>No consumer process found</comment>');
        }

        foreach ($pids as $pid) {
            if (posix_kill($pid, SIGKILL)) {
                $output->writeln(sprintf('Signal SIGKILL sent to PID %d', $pid));
            } else {
                $output->writeln(sprintf('<comment>Was unable to send SIGKILL to %d', $pid));
            }
        }

        /** @var Producer $producer */
        $producer = $this->getContainer()->get($producerName);
        for ($i = 0; $i < count($pids); $i++) {
            $producer->publish(serialize(array(
                '_ping' => true,
            )));
        }

        // Sleep 1 second and check for processes
        sleep(1);
        $stillAliveConsumers = $this->getConsumerPids($consumer);
        if (count($stillAliveConsumers) !== 0) {
            return $output->writeln(sprintf(
                '<error>%d consumer processes seems to be found</error>',
                count($stillAliveConsumers)
            ));
        }

        $output->writeln(sprintf(
            '<info>%d consumer were terminated</info>',
            count($pids)
        ));
    }

    /**
     * Return proccesses ID of consumer.
     *
     * @param $consumer
     *
     * @return array
     */
    private function getConsumerPids($consumer)
    {
        $pids = [];

        exec(sprintf('ps ax | grep "rabbitmq:consumer %s"', $consumer), $lines);
        foreach ($lines as $line) {
            if (strpos($line, 'app/console') !== false && preg_match('#^([ ]*)([0-9]+)#', $line, $matches)) {
                $pids[] = $matches[2];
            }
        }

        return $pids;
    }
}
