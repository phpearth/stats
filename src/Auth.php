<?php

namespace PHPWorldWide\Stats;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;

/**
 * Class Auth.
 */
class Auth
{
    /**
     * @var Facebook
     */
    public $fb;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var
     */
    private $error;

    /**
     * Auth constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;

        $this->fb = new Facebook([
            'app_id' => $this->config->getParameter('fb_app_id'),
            'app_secret' => $this->config->getParameter('fb_app_secret'),
            'default_graph_version' => $this->config->getParameter('default_graph_version'),
        ]);
    }

    /**
     * Checks if current Facebook access token is valid.
     *
     * @return bool
     */
    public function isValid()
    {
        try {
            $this->fb->get('/me');
            $this->error = null;

            return true;
        } catch (FacebookResponseException $e) {
            if ($e->getErrorType() == 'OAuthException') {
                $this->error = $e->getMessage().' Error type: '.$e->getErrorType();
            } else {
                $this->error = $e->getMessage();
            }

            return false;
        } catch (FacebookSDKException $e) {
            $this->error = $e->getMessage();

            return false;
        }
    }

    /**
     * Get error.
     *
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set default Facebook access token.
     *
     * @param string $token
     */
    public function setToken($token)
    {
        $this->fb->setDefaultAccessToken($token);
    }
}
