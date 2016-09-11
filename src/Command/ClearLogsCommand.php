<?php

namespace PHPWorldWide\Stats\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PHPWorldWide\Stats\Log;

/**
 * Class GenerateCommand
 */
class ClearLogsCommand extends Command
{
    /**
     * @var Log
     */
    private $log;

    /**
     * Set log.
     *
     * @param Log $log
     */
    public function setLog(Log $log)
    {
        $this->log = $log;
    }

    /**
     * Configures log clearing command for Console component.
     */
    protected function configure()
    {
        try {
            $this
                ->setName('clear-logs')
                ->setDescription('Clears all log files and folders')
            ;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log->clearLogs();

        $output->writeln("Logs cleaned.");
    }
}
