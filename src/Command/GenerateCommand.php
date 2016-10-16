<?php

namespace PHPWorldWide\Stats\Command;

use PHPWorldWide\Stats\Fetcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use PHPWorldWide\Stats\Auth;
use PHPWorldWide\Stats\Config;
use PHPWorldWide\Stats\Log;
use PHPWorldWide\Stats\Mapper;
use Twig_Environment;
use Twig_Template;

/**
 * Class GenerateCommand
 * @package PHPWorldWide\Stats\Command
 */
class GenerateCommand extends Command
{
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
                ->setName('generate')
                ->setDescription('Generates Facebook group stats report.')
                ->setHelp('This command generates Facebook group stats report.')
                ->addOption(
                    'from',
                    'f',
                    InputOption::VALUE_REQUIRED,
                    'Start date of the generated stats report ('.date('Y-m-d', strtotime('last monday')).')',
                    date('Y-m-d', strtotime('last monday'))
                )
                ->addOption(
                    'to',
                    't',
                    InputOption::VALUE_REQUIRED,
                    'End date of the generated stats report ('.date('Y-m-d', strtotime('last monday +7 days')).')',
                    date('Y-m-d', strtotime('last monday +6 days'))
                )
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
        $fromDate = \DateTime::createFromFormat('Y-m-d H:i:s', $input->getOption('from').' 00:00:00');
        $fromDate->setTimezone(new \DateTimeZone('UTC'));
        $this->config->setParameter('start_datetime', $fromDate);

        $toDate = \DateTime::createFromFormat('Y-m-d H:i:s', $input->getOption('to').' 00:00:00');
        $toDate->setTimezone(new \DateTimeZone('UTC'));
        $this->config->setParameter('end_datetime', $toDate);

        $progress = new ProgressBar($output, 40);
        $progress->setFormat(" %message%\n %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%\n\n");
        $progress->setMessage('Starting...');
        $progress->setProgressCharacter("\xF0\x9F\x8D\xBA");

        if (!$this->auth->isValid()) {
            $helper = $this->getHelper('question');

            $question = new Question($this->auth->getError()."\n\n".
                "Enter user access token from the Graph API Explorer\n".
                "https://developers.facebook.com/tools/explorer\n");
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

        $output->writeln('Generating report from '.$this->config->getParameter('start_datetime')->format('c').' to '.$this->config->getParameter('end_datetime')->format('c')."\n");
        $progress->start();
        $progress->setMessage('Setting up Facebook service...');
        $progress->advance();

        try {
            $fetcher = new Fetcher($this->config, $progress, $this->auth->fb, $this->log);
            $mapper = new Mapper($this->config, $fetcher->getFeed(), $this->log);
            $topics = $mapper->getTopics();
            $comments = $mapper->getComments();
            $replies = $mapper->getReplies();
            $users = $mapper->getUsers();

            $progress->advance();

            $progress->finish();
            $output->writeln("\n");

            $start = $this->config->getParameter('start_datetime');
            $start->setTimezone(new \DateTimeZone(date_default_timezone_get()));

            $end = $this->config->getParameter('end_datetime');
            $end->setTimezone(new \DateTimeZone(date_default_timezone_get()));

            $output->writeln($this->template->render([
                'start_date' => $start->format('Y-m-d'),
                'end_date' => $end->format('Y-m-d'),
                'new_users_count' => $fetcher->getNewUsersCount(),
                'top_users_count' => $this->config->getParameter('top_users_count'),
                'top_users' => $users->getTopUsers($this->config->getParameter('top_users_count'), $this->config->getParameter('ignored_users')),
                'topics' => $topics,
                'new_comments_count' => $comments->count(),
                'new_replies_count' => $replies->count(),
                'active_users_count' => $users->count(),
                'banned_count' => $this->config->getParameter('new_blocked_count') - $this->config->getParameter('last_blocked_count'),
                'commits_count' => 3,
                'top_topics' => $this->config->getParameter('top_topics'),
            ]));
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }
}
