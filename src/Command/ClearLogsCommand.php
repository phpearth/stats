<?php

namespace PhpEarth\Stats\Command;

use PhpEarth\Stats\Log;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Console command that removes all generated log folders from the defined log
 * directory.
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
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('This will remove all log folders in app/config/log? Are you sure you want to continue?', false);

        if (!$helper->ask($input, $output, $question)) {
            $output->writeln("Exiting...");
            return;
        }

        $this->log->clearLogs();

        $output->writeln("Logs cleaned.");
    }
}
