<?php

namespace PhpEarth\Stats\Command;

use PhpEarth\Stats\Fetcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use PhpEarth\Stats\Auth;
use PhpEarth\Stats\Config;
use PhpEarth\Stats\Mapper;
use Twig_Environment;
use Twig_Template;

/**
 * Class GenerateCommand
 * @package PhpEarth\Stats\Command
 */
class GenerateCommand extends Command
{
    /**
     * @var string
     */
    private $reportsDir;

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
     * @var Translator
     */
    private $translator;

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
     * Set Reports directory
     */
    public function setReportsDir($reportsDir)
    {
        $this->reportsDir = $reportsDir;
    }

    /**
     * Set Translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
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
                    date('Y-m-d', strtotime('last monday +7 days'))
                )
                ->addOption(
                    'animation',
                    'a',
                    InputOption::VALUE_NONE,
                    'Animation of group activity visualized with Gource'
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
            $fetcher = new Fetcher($this->config, $progress, $this->auth->fb);
            $mapper = new Mapper($this->config, $fetcher->getFeed());
            $topics = $mapper->getTopics();
            $comments = $mapper->getComments();
            $replies = $mapper->getReplies();
            $users = $mapper->getUsers();
            $newMembers = $fetcher->getNewMembers();

            $progress->advance();

            $progress->finish();
            $output->writeln("\n");

            $start = $this->config->getParameter('start_datetime');
            $start->setTimezone(new \DateTimeZone(date_default_timezone_get()));

            $end = $this->config->getParameter('end_datetime');
            $end->setTimezone(new \DateTimeZone(date_default_timezone_get()));

            $output->writeln($this->template->render([
                'translator'     => $this->translator,
                'start_date'     => $start->format('Y-m-d'),
                'end_date'       => $end->format('Y-m-d'),
                'new_members'    => count($newMembers),
                'top_members'    => $this->config->getParameter('top_users_count'),
                'banned_count'   => $this->config->getParameter('new_blocked_count') - $this->config->getParameter('last_blocked_count'),
                'topics'         => $topics,
                'active_members' => $users->count(),
                'new_comments'   => $comments->count(),
                'new_replies'    => $replies->count(),
                'top_users'      => $users->getTopUsers($this->config->getParameter('top_users_count'), $this->config->getParameter('ignored_users')),
                'commits_count'  => 3,
                'top_topics'     => $this->config->getParameter('top_topics'),
                'group_id'       => $this->config->getParameter('group_id')
            ]));

            $this->generateReports($users, $topics, $newMembers);

            if ($input->getOption('animation')) {
                $this->generateGourceLog($topics, $comments, $replies);
            }

        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    private function generateReports($users, $topics, $newMembers)
    {
        // Create reports folder
        if (!file_exists($this->reportsDir)) {
            mkdir($this->reportsDir);
        }

        // Contributors
        foreach ($users->getTopUsers() as $id => $user) {
            $msg = $id."\t";
            $msg .= $user->getName()."\t";
            $msg .= 'Points: '.$user->getPointsCount()."\t";
            $msg .= 'Topics: '.$user->getTopicsCount()."\t";
            $msg .= 'Comments: '.$user->getCommentsCount()."\n";
            file_put_contents($this->reportsDir.'/contributors.txt', $msg, FILE_APPEND | LOCK_EX);
        }

        // Topics
        foreach ($topics as $topic) {
            $msg = $topic->getId()."\t";
            $msg .= ' Reactions: '.$topic->getReactionsCount()."\t";
            $msg .= ' Comments: '.$topic->getCommentsCount()."\n";
            file_put_contents($this->reportsDir.'/topics.txt', $msg, FILE_APPEND | LOCK_EX);
        }

        // New members
        foreach ($newMembers as $member) {
            $msg = $member[0]."\t".$member[1]."\n";
            file_put_contents($this->reportsDir.'/members.txt', $msg, FILE_APPEND | LOCK_EX);
        }
    }

    private function generateGourceLog($topics, $comments, $replies)
    {
        $count = ceil($topics->count()/10);
        $msg = '';
        foreach ($topics as $topic) {
            $topicTitle = ($topic->getMessage()) ? substr($topic->getMessage(), 0, 20) : $topic->getId();
            //$topicTitle = trim(preg_replace('/\s\s+/', ' ', $topicTitle));
            $topicTitle = preg_replace('/\s+/', ' ', trim($topicTitle));
            $topicTitle = sha1($topicTitle);

            $name = ($topic->getUser()) ? $topic->getUser()->getName() : '';

            $msg .= $topic->getCreatedTime()->getTimeStamp().'|'.($name).'|A|'.'/'.$count.'/'.$topic->getId().'/'.$topicTitle.".Topics\n";
        }
        foreach ($comments as $comment) {
            $commentMessage = substr($comment->getMessage(), 0, 30);
            $commentMessage = trim(preg_replace('/\s\s+/', ' ', $commentMessage));
            $commentMessage = sha1($commentMessage);

            $msg .= $comment->getCreatedTime()->getTimeStamp().'|'.$comment->getUser()->getName().'|A|'.'/'.$count.'/'.$comment->getTopicId().'/'.$comment->getId().'/'.$commentMessage.".Comments\n";
        }
        foreach($replies as $reply) {
            $replyMessage = substr($reply->getMessage(), 0, 30);
            $replyMessage = trim(preg_replace('/\s\s+/', ' ', $replyMessage));
            $replyMessage = sha1($replyMessage);

            $msg .= $reply->getCreatedTime()->getTimeStamp().'|'.$reply->getUser()->getName().'|A|'.'/'.$count.'/'.$reply->getTopicId().'/'.$reply->getCommentId().'/'.$reply->getId().'/'.$replyMessage.".Replies\n";
        }

        file_put_contents($this->reportsDir.'/../gource.log', $msg, FILE_APPEND | LOCK_EX);

        $data = file($this->reportsDir.'/../gource.log');
        natsort($data);
        $refactored = [];
        $count = 0;
        foreach ($data as $line) {
            $items = explode('|', $line);
            $items[3] = str_replace("\n", "", $items[3]);
            $items[] = $this->randomColor()."\n";
            $items = implode('|', $items);
            $refactored[] = $items;
        }

        file_put_contents($this->reportsDir.'/../gource_sorted.log', $refactored);
    }

    private function randomColorPart()
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }

    private function randomColor()
    {
        return $this->randomColorPart().$this->randomColorPart().$this->randomColorPart();
    }
}
