<?php

namespace PHPWorldWide\Stats\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateCommand
 */
class OffensiveWordsCommand extends Command
{
    private $offensiveWords = [];

    /**
     * Set offensive words from configuration file
     *
     * @param $offensiveWords
     */
    public function setOffensiveWords($offensiveWords)
    {
        $this->offensiveWords = $offensiveWords;
    }

    /**
     * Configures log clearing command for Console component.
     */
    protected function configure()
    {
        try {
            $this
                ->setName('offensivewords')
                ->setDescription('Manages offensive words')
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
        $rows = [];
        foreach ($this->offensiveWords as $word) {
            $rows[] = [str_rot13($word[0]), $word[1], $word[0]];
        }
        $table = new Table($output);
        $table
            ->setHeaders(['String', 'Points', 'ROT13 Transformation'])
            ->setRows($rows)
        ;
        $table->render();
    }
}
