<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PurgeQueuesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('app:queue:purge');
        $this->addArgument('consumer', InputArgument::OPTIONAL, 'Consumer name');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $consumers = [
            'google_create',
            'google_update',
            'google_delete',
        ];

        if (null !== $consumer = $input->getArgument('consumer')) {
            $consumers = [$consumer];
        }

        foreach ($consumers as $consumer) {
            $output->writeln(sprintf('Request to purge sent to %s', $consumer));

            $inputCommand = new ArrayInput(['command' => 'rabbitmq:purge', 'name' => $consumer]);
            $inputCommand->setInteractive(false);

            $this->getApplication()->doRun($inputCommand, $output);

            $output->writeln(sprintf('<info>Consumer %s purged</info>', $consumer));
        }
    }
}
