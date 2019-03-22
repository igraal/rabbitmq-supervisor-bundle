<?php

namespace Phobetor\RabbitMqSupervisorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BuildCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('rabbitmq-supervisor:build')
            ->setDescription('Build supervisor worker configuration for all RabbitMQ consumer.')
            ->addOption('wait-for-supervisord', null, InputOption::VALUE_NONE)
            ->addOption('user', null, InputOption::VALUE_REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Phobetor\RabbitMqSupervisorBundle\Services\RabbitMqSupervisor $handler */
        $handler = $this->getContainer()->get('phobetor_rabbitmq_supervisor');
        $handler->setWaitForSupervisord((bool) $input->getOption('wait-for-supervisord'));
        if ($input->hasOption('user')) {
            $handler->setUser($input->getOption('user'));
        }
        $handler->setNoDaemon(
            $this->getContainer()->getParameter('kernel.environment') === 'dev'
        );
        $handler->build();
    }
}
