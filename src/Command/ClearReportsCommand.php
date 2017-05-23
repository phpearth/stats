<?php

namespace PhpEarth\Stats\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Console command that removes all generated report folders from the defined reports
 * directory.
 */
class ClearReportsCommand extends Command
{
    private $reportsRoot;

    public function setReportsRoot($reportsRoot)
    {
        $this->reportsRoot = $reportsRoot;
    }

    /**
     * Configures reports clearing command for Console component.
     */
    protected function configure()
    {
        try {
            $this
                ->setName('clear-reports')
                ->setDescription('Clears all reports files and folders')
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
        if (!$this->reportsRoot) {
            die("You must set the reports root directory");
        }

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('This will remove all reports folders in var/reports? Are you sure you want to continue?', false);

        if (!$helper->ask($input, $output, $question)) {
            $output->writeln("Exiting...");
            return;
        }

        $dirs = array_diff(scandir($this->reportsRoot), ['..', '.', '.gitkeep']);
        foreach ($dirs as $dir) {
            $files = array_diff(scandir($this->reportsRoot.'/'.$dir), ['..', '.']);
            foreach ($files as $file) {
                unlink($this->reportsRoot.'/'.$dir.'/'.$file);
            }
            rmdir($this->reportsRoot.'/'.$dir);
        }

        $output->writeln("Reports cleaned.");
    }
}
