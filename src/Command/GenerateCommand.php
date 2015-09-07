<?php

namespace PHPWorldWide\Stats\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use PHPWorldWide\Stats\Template;
use PHPWorldWide\Stats\Config;
use PHPWorldWide\Stats\Service;

class GenerateCommand extends Command
{
    private $config;
    private $progress;

    protected function configure()
    {
        $this->config = new Config(__DIR__.'/../../app/config/parameters.yml');

        $this
            ->setName('generate_command')
            ->setDescription('Generates Facebook group report')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->progress = new ProgressBar($output, 40);
        $this->progress->setFormat(" %message%\n %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%\n\n");
        $this->progress->setMessage('Starting...');
        $output->writeln('Generating report for the from ' . $this->config->get('start_datetime') . ' till now');
        $this->progress->start();

        $service = new Service($this->config, $this->progress);

        $startDate = \DateTime::createFromFormat('Y-m-d H:i:s', $this->config->get('start_datetime'));
        $endDate = \DateTime::createFromFormat('Y-m-d H:i:s', $this->config->get('end_datetime'));
        $topics = $service->getTopics($startDate, $endDate);

        $users = $service->getUsers($startDate, $endDate);

        $this->progress->setMessage('Calculating number of blocked members...');
        $this->progress->advance();
        $blockedCount = $this->config->get('new_blocked_count') - $this->config->get('last_blocked_count');

        $date = new \DateTime();
        $data = [
            'date' => $date->format('Y-m-d'),
            'newUsersCount' => $service->getNewUsersCount(),
            'topUsers' => $users->getTopUsers($this->config->get('top_users_count')),
            'bannedCount' => $blockedCount,
            'newTopicsCount' => $topics->getNewTopicsCount(),
            'mostLikedTopicId' => $topics->getMostLikedTopicId(),
            'mostActiveTopicId' => $topics->getMostActiveTopicId(),
            'commitsCount' => 3,
        ];

        $template = new Template(__DIR__ . '/../../app/templates/report.php');
        foreach ($data as $key => $value) {
            $template->$key = $value;
        }

        $this->progress->finish();

        $output->writeln($template->render());
    }
}
