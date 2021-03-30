<?php

namespace Phobetor\RabbitMqSupervisorBundle\Command;

use Phobetor\RabbitMqSupervisorBundle\Services\RabbitMqSupervisor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RebuildCommand extends AbstractRabbitMqSupervisorAwareCommand
{
    /**
     * @var string
     */
    private $env;

    public function __construct(RabbitMqSupervisor $rabbitMqSupervisor, string $env)
    {
        parent::__construct($rabbitMqSupervisor);
        $this->env = $env;
    }

    protected function configure()
    {
        $this
            ->setName('rabbitmq-supervisor:rebuild')
            ->setDescription('Stop supervisord, rebuild supervisor worker configuration for all RabbitMQ consumer and start supervisord again.')
            ->addOption('wait-for-supervisord', null, InputOption::VALUE_NONE)
            ->addOption('user', null, InputOption::VALUE_REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->rabbitMqSupervisor->setWaitForSupervisord((bool) $input->getOption('wait-for-supervisord'));

        if ($input->hasOption('user')) {
            $this->rabbitMqSupervisor->setUser($input->getOption('user'));
        }

        $this->rabbitMqSupervisor->setNoDaemon($this->env === 'dev');
        $this->rabbitMqSupervisor->rebuild();

        return 0;
    }
}
