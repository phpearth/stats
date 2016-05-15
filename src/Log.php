<?php

namespace PHPWorldWide\Stats;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Class Log
 */
class Log
{
    /**
     * @var Logger
     */
    private $topicsLogger;

    /**
     * @var Logger
     */
    private $contributorsLogger;

    /**
     * @var Logger
     */
    private $newUsersLogger;

    /**
     * @var string
     */
    private $timestamp;

    /**
     * @var string
     */
    private $logDir;

    /**
     * Log constructor.
     * 
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->timestamp = date('YmdHis');
        $config = array_merge([
            'log_dir' => __DIR__.'/../app/logs',
            'topics_log' => '/'.$this->timestamp.'/topics.log',
            'contributors_log' => '/'.$this->timestamp.'/contributors.log',
            'new_users_log' => '/'.$this->timestamp.'/newUsers.log',
        ], $config);

        $this->logDir = $config['log_dir'];

        $this->topicsLogger = new Logger('topics_logger');
        $this->topicsLogger->pushHandler(new StreamHandler($this->logDir.$config['topics_log'], Logger::INFO));

        $this->contributorsLogger = new Logger('contributors_logger');
        $this->contributorsLogger->pushHandler(new StreamHandler($this->logDir.$config['contributors_log'], Logger::INFO));

        $this->newUsersLogger = new Logger('new_users_logger');
        $this->newUsersLogger->pushHandler(new StreamHandler($this->logDir.$config['new_users_log'], Logger::INFO));
    }

    /**
     * Logs string to topics log.
     *
     * @param string $message
     */
    public function logTopic($message)
    {
        $this->warm();
        $this->topicsLogger->addInfo($message);
    }

    /**
     * Logs string to contributors log.
     *
     * @param string $message
     */
    public function logContributor($message)
    {
        $this->warm();
        $this->contributorsLogger->addInfo($message);
    }

    /**
     * Logs string to new users log.
     *
     * @param string $message
     */
    public function logNewUser($message)
    {
        $this->warm();
        $this->newUsersLogger->addInfo($message);
    }

    /**
     * Get current timestamp for log folder.
     *
     * @return string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Creates log folder if it is missing.
     */
    private function warm()
    {
        if (!file_exists($this->logDir.'/'.$this->timestamp)) {
            mkdir($this->logDir.'/'.$this->timestamp);
        }
    }

    /**
     * Remove log folders.
     */
    public function clearLogs()
    {
        $dirs = array_diff(scandir($this->logDir), ['..', '.', '.gitkeep']);
        foreach ($dirs as $dir) {
            $files = array_diff(scandir($this->logDir.'/'.$dir), ['..', '.']);
            foreach ($files as $file) {
                unlink($this->logDir.'/'.$dir.'/'.$file);
            }
            rmdir($this->logDir.'/'.$dir);
        }
    }
}
