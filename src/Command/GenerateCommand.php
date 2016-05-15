<?php

namespace PHPWorldWide\Stats\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Question\Question;
use PHPWorldWide\Stats\Auth;
use PHPWorldWide\Stats\Config;
use PHPWorldWide\Stats\Log;
use PHPWorldWide\Stats\Service;
use Twig_Environment;
use Twig_Template;

/**
 * Class GenerateCommand
 * @package PHPWorldWide\Stats\Command
 */
class GenerateCommand extends Command
{
    /**
     * @var ProgressBar
     */
    private $progress;

    /**
     * @var Twig_Template
     */
    private $template;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var Log
     */
    private $log;

    /**
     * Set configuration.
     *
     * @param Config $config
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Set template.
     *
     * @param Twig_Environment $twig
     */
    public function setTemplate(Twig_Environment $twig)
    {
        $this->template = $twig->loadTemplate('report.txt.twig');
    }

    /**
     * Set authentication.
     *
     * @param Auth $auth
     */
    public function setAuth(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param Log $log
     */
    public function setLog(Log $log)
    {
        $this->log = $log;
    }

    /**
     * Configures generate command for Console component.
     */
    protected function configure()
    {
        try {
            $this
                ->setName('generate_command')
                ->setDescription('Generates Facebook group report')
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
        $this->progress = new ProgressBar($output, 40);
        $this->progress->setFormat(" %message%\n %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%\n\n");
        $this->progress->setMessage('Starting...');
        $this->progress->setProgressCharacter("\xF0\x9F\x8D\xBA");
        $output->writeln('Generating report for the from '.$this->config->get('start_datetime')." till now\n");
        $this->progress->start();

        $this->progress->setMessage('Setting up Facebook service...');

        if (!$this->auth->isValid()) {
            $helper = $this->getHelper('question');

            $question = new Question($this->auth->getError() . ' Enter a new access token:');
            $auth = $this->auth;
            $question->setValidator(function ($token) use ($auth) {
                if (trim($token) == '') {
                    throw new \Exception('The token can not be empty');
                }

                $auth->setToken($token);

                if (!$auth->isValid()) {
                    throw new \Exception($auth->getError());
                }

                return $token;
            });

            $question->setHidden(true);
            $question->setMaxAttempts(20);

            $helper->ask($input, $output, $question);
        }

        $this->progress->advance();

        try {
            $service = new Service($this->config, $this->progress, $this->auth->fb, $this->log);

            $startDate = \DateTime::createFromFormat('Y-m-d H:i:s', $this->config->get('start_datetime'));
            $endDate = \DateTime::createFromFormat('Y-m-d H:i:s', $this->config->get('end_datetime'));
            $topics = $service->getTopics($startDate, $endDate);
            $comments = $service->getComments($startDate, $endDate);
            $replies = $service->getReplies($startDate, $endDate);
            $users = $service->getUsers($startDate, $endDate);

            $this->progress->setMessage('Calculating number of blocked members...');
            $blockedCount = $this->config->get('new_blocked_count') - $this->config->get('last_blocked_count');
            $this->progress->advance();

            $this->progress->finish();
            $output->writeln("\n");

            $output->writeln($this->template->render([
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'new_users_count' => $service->getNewUsersCount(),
                'top_users_count' => $this->config->get('top_users_count'),
                'top_users' => $users->getTopUsers($this->config->get('top_users_count')),
                'new_topics_count' => $topics->getNewTopicsCount(),
                'closed_topics_count' => $topics->getClosedTopicsCount(),
                'new_comments_count' => $comments->count(),
                'new_replies_count' => $replies->count(),
                'active_users_count' => $users->count(),
                'banned_count' => $blockedCount,
                'most_likes_count' => $topics->getMostLikedTopic()->getLikesCount(),
                'most_liked_topic_id' => $topics->getMostLikedTopic()->getPermalinkPostId(),
                'most_comments_count' => $topics->getMostActiveTopic()->getCommentsCount(),
                'most_active_topic_id' => $topics->getMostActiveTopic()->getPermalinkPostId(),
                'commits_count' => 3,
                'top_topics' => $this->config->get('top_topics'),
            ]));
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }
}
