<?php

namespace PhpEarth\Stats;

use Symfony\Component\Console\Helper\ProgressBar;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;

/**
 * Class Fetcher.
 */
class Fetcher
{
    /**
     * @var Facebook
     */
    protected $fb;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var ProgressBar
     */
    protected $progress;

    /**
     * @var array
     */
    protected $feed = [];

    /**
     * @var Log
     */
    protected $log;

    /**
     * Mapper constructor.
     *
     * @param Config $config
     * @param ProgressBar $progress
     * @param Facebook $fb
     * @param Log $log
     */
    public function __construct(Config $config, ProgressBar $progress, Facebook $fb, Log $log)
    {
        $this->config = $config;
        $this->progress = $progress;
        $this->fb = $fb;
        $this->log = $log;
    }

    /**
     * Fetch feed from API data.
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getFeed()
    {
        $this->progress->setMessage('Fetching feed...');
        $this->progress->advance();

        $this->feed = [];

        try {
            $pagesCount = 0;
            $startDate = $this->config->getParameter('start_datetime');
            $endDate = $this->config->getParameter('end_datetime');
            $response = $this->fb->get('/'.$this->config->getParameter('group_id').'/feed?fields=comments.limit(200).summary(1){comment_count,from,created_time,message,can_comment,reactions.limit(0).summary(1),comments.limit(200).summary(1){comment_count,from,created_time,message,reactions.limit(0).summary(1)}},reactions.limit(0).summary(1),from,created_time,updated_time,message,type,attachments{type},shares&include_hidden=true&limit=50&since='.$startDate->getTimestamp().'&until='.$endDate->getTimestamp());

            $feedEdge = $response->getGraphEdge();

            if (count($feedEdge) > 0) {
                do {
                    ++$pagesCount;
                    $this->progress->setMessage('Fetching feed from API page '.$pagesCount.' and with the topic updated '.$feedEdge[0]->getField('updated_time')->format('Y-m-d H:i:s'));
                    $this->progress->advance();
                    foreach ($feedEdge as $topic) {
                        $this->feed[] = $topic;
                    }
                } while ($feedEdge = $this->fb->next($feedEdge));
            }
        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            throw new \Exception('Graph returned an error: '.$e->getMessage());
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            throw new \Exception('Facebook SDK returned an error: '.$e->getMessage());
        }

        $this->progress->setMessage('Adding topics to collection...');
        $this->progress->advance();

        return $this->feed;
    }

    /**
     * Get number of new users since the user's name set in app configuration.
     *
     * @return int
     *
     * @throws \Exception
     */
    public function getNewUsersCount()
    {
        $this->progress->setMessage('Retrieving members...');
        $this->progress->advance();
        $newUsersCount = 0;
        $pagesCount = 0;

        try {
            $response = $this->fb->get('/'.$this->config->getParameter('group_id').'/members?fields=id,name&limit=1000');

            $feedEdge = $response->getGraphEdge();
            do {
                ++$pagesCount;
                $this->progress->setMessage('Retrieving members from API page '.$pagesCount);
                $this->progress->advance();

                foreach ($feedEdge as $status) {
                    // log new users
                    $log = $status->asArray()['id']."\t";
                    $log .= $status->asArray()['name']."\n";
                    $this->log->logNewUser($log);

                    if ($status->asArray()['name'] == $this->config->getParameter('last_member_name')) {
                        break 2;
                    }
                    ++$newUsersCount;
                }

                if ($pagesCount == $this->config->getParameter('api_pages')) {
                    break;
                }
            } while ($feedEdge = $this->fb->next($feedEdge));
        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            throw new \Exception('Graph returned an error: '.$e->getMessage());
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            throw new \Exception('Facebook SDK returned an error: '.$e->getMessage());
        }

        $this->progress->advance();

        return $newUsersCount;
    }
}
