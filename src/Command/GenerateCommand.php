<?php

namespace PHPWorldWide\Stats\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use PHPWorldWide\Stats\Config;
use PHPWorldWide\Stats\Service;
use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_Template;
use Twig_Extensions_Extension_I18n;

class GenerateCommand extends Command
{
    private $config;
    private $progress;
    /**
     * @var Twig_Template
     */
    private $template;

    /**
     * Configures generate command for Console component.
     */
    protected function configure()
    {
        try {
            $this->config = new Config(__DIR__.'/../../app/config/parameters.yml');

            $twigLoader = new Twig_Loader_Filesystem(__DIR__.'/../../app/templates');
            $twig = new Twig_Environment($twigLoader);
            $twig->addExtension(new Twig_Extensions_Extension_I18n());

            // Set language to English
            putenv('LC_ALL=en_US');
            setlocale(LC_ALL, 'en_US');

            // Specify the location of the translation tables
            bindtextdomain('myAppPhp', 'includes/locale');
            bind_textdomain_codeset('myAppPhp', 'UTF-8');

            // Choose domain
            textdomain('myAppPhp');
            $this->template = $twig->loadTemplate('report.html.twig');
            $this
                ->setName('generate_command')
                ->setDescription('Generates Facebook group report')
            ;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->progress = new ProgressBar($output, 40);
        $this->progress->setFormat(" %message%\n %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%\n\n");
        $this->progress->setMessage('Starting...');
        $this->progress->setProgressCharacter("\xF0\x9F\x8D\xBA");
        $output->writeln('Generating report for the from '.$this->config->get('start_datetime').' till now');
        $this->progress->start();

        try {
            $service = new Service($this->config, $this->progress);

            $startDate = \DateTime::createFromFormat('Y-m-d H:i:s', $this->config->get('start_datetime'));
            $endDate = \DateTime::createFromFormat('Y-m-d H:i:s', $this->config->get('end_datetime'));
            $topics = $service->getTopics($startDate, $endDate);
            $comments = $service->getComments($startDate, $endDate);
            $replies = $service->getReplies($startDate, $endDate);
            $users = $service->getUsers($startDate, $endDate);

            $this->progress->setMessage('Calculating number of blocked members...');
            $this->progress->advance();
            $blockedCount = $this->config->get('new_blocked_count') - $this->config->get('last_blocked_count');

            $this->progress->finish();
            $output->writeln("\n");

            $output->writeln($this->template->render([
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'new_users_count' => $service->getNewUsersCount(),
                'top_users_count' => $this->config->get('top_users_count'),
                'top_users' => $users->getTopUsers($this->config->get('top_users_count')),
                'new_topics_count' => $topics->getNewTopicsCount(),
                'new_comments_count' => $comments->count(),
                'new_replies_count' => $replies->count(),
                'active_users_count' => $users->count(),
                'banned_count' => $blockedCount,
                'most_likes_count' => $topics->getMostLikedTopic()->getLikesCount(),
                'most_liked_topic_id' => $topics->getMostLikedTopic()->getReportId(),
                'most_comments_count' => $topics->getMostActiveTopic()->getCommentsCount(),
                'most_active_topic_id' => $topics->getMostActiveTopic()->getReportId(),
                'commits_count' => 3,
                'top_topics' => $this->config->get('top_topics'),
            ]));
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }
}
