<?php

namespace PHPWorldWide\Stats;

use PHPWorldWide\Stats\Collection\UserCollection;
use PHPWorldWide\Stats\Collection\TopicCollection;
use PHPWorldWide\Stats\Collection\CommentCollection;
use PHPWorldWide\Stats\Collection\ReplyCollection;
use Symfony\Component\Console\Helper\ProgressBar;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;

class Mapper
{
    protected $fb;
    protected $config;
    protected $progress;
    protected $feed = [];

    public function __construct(Config $config, ProgressBar $progress)
    {
        $this->config = $config;
        $this->progress = $progress;
        $this->progress->setMessage('Setting up Facebook connection ...');
        $this->progress->advance();

        $this->fb = new Facebook([
            'app_id' => $this->config->get('fb_app_id'),
            'app_secret' => $this->config->get('fb_app_secret'),
            'default_graph_version' => $this->config->get('default_graph_version'),
            'default_access_token' => $this->config->get('fb_access_token'),
        ]);
    }

    public function fetchFeed()
    {
        $this->progress->setMessage('Fetching feed...');
        $this->progress->advance();

        $this->feed = [];

        try {
            $pagesCount = 0;
            $startDate = \DateTime::createFromFormat('Y-m-d H:i:s', $this->config->get('start_datetime'));
            $response = $this->fb->get('/' . $this->config->get('group_id') . '/feed?fields=comments.limit(200).summary(1){like_count,comment_count,from,created_time,message,comments.limit(200).summary(1){like_count,comment_count,from,created_time,message}},likes.limit(0).summary(1),from,created_time,updated_time&include_hidden=true&limit=100&since=' . $startDate->getTimestamp());

            $feedEdge = $response->getGraphEdge();

            do {
                $pagesCount++;
                $this->progress->setMessage('Fetching feed from API page ' . $pagesCount . ' and with the topic updated ' . $feedEdge[0]->getField('updated_time')->format('Y-m-d H:i:s'));
                $this->progress->advance();

                foreach ($feedEdge as $topic) {
                    $topicArray = $topic->asArray();
                    $topicArray['commentsCount'] = $topic->getField('comments')->getMetaData()['summary']['total_count'];
                    $topicArray['likesCount'] = $topic->getField('likes')->getMetaData()['summary']['total_count'];
                    $this->feed[] = $topicArray;
                }
            } while ($feedEdge = $this->fb->next($feedEdge));

        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $this->progress->setMessage('Adding topics to collection...');
        $this->progress->advance();

        return $this->feed;
    }

    public function mapTopics(TopicCollection $topics, $startDate, $endDate)
    {
        $topics->setStartDate($startDate);
        $topics->setEndDate($endDate);
        $topics->setGroupId($this->config->get('group_id'));
        $topics->addTopicsFromFeed($this->fetchFeed());

        // log topics
        $log = '';
        foreach ($topics as $id => $topic) {
            $log .= $id . "\t";
            $log .= " Likes: " . $topic->getLikesCount() . "\t";
            $log .= " Comments: " . $topic->getCommentsCount() . "\n";
        }
        file_put_contents('./app/logs/topics.txt', $log);

    }

    public function mapComments(CommentCollection $comments, $startDate, $endDate)
    {
        $comments->setStartDate($startDate);
        $comments->setEndDate($endDate);
        $comments->addCommentsFromFeed($this->feed);
    }

    public function mapReplies(ReplyCollection $replies, $startDate, $endDate)
    {
        $replies->setStartDate($startDate);
        $replies->setEndDate($endDate);
        $replies->addRepliesFromFeed($this->feed);
    }

    public function mapUsers(UserCollection $users, $startDate, $endDate)
    {
        $users->setStartDate($startDate);
        $users->setEndDate($endDate);
        $users->addUsersFromFeed($this->feed);

        // log users
        $log = '';
        foreach ($users->getTopUsers() as $id => $user) {
            $log .= $id . "\t";
            $log .= $user->getName() . "\t";
            $log .= $user->getPoints() . "\n";
        }
        file_put_contents('./app/logs/users.txt', $log);
    }

    public function getNewUsersCount()
    {
        $this->progress->setMessage('Retrieving members...');
        $this->progress->advance();
        $newUsersCount = 0;
        $pagesCount = 0;

        try {
            $response = $this->fb->get('/' . $this->config->get('group_id') . '/members?fields=id,name&limit=1000');

            $feedEdge = $response->getGraphEdge();
            do {
                $pagesCount ++;
                $this->progress->setMessage('Retrieving members from API page ' . $pagesCount);
                $this->progress->advance();


                foreach ($feedEdge as $status) {
                    if ($status->asArray()['name'] == $this->config->get('last_member_name')) {
                        break 2;
                    }
                    $newUsersCount ++;
                }

                if ($pagesCount == $this->config->get('api_pages')) {
                    break;
                }
            } while ($feedEdge = $this->fb->next($feedEdge));
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $this->progress->advance();

        return $newUsersCount;
    }

}
