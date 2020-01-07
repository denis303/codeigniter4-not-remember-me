<?php

/**
 * CodeIgniter 4 Not Remember Me Cookie
 *
 * @author denis303 <dev@denis303.com>
 * @license MIT
 * @link http://denis303.com
 *
 * Not rememver me feature not working in Chrome (and other browsers) when:
 *
 *   1. On Startup = "Continue where you left off"
 *   2. Continue running background apps when Google Chrome is closed = "On"
 */
namespace Denis303\NotRememberMe;

use Config\App;
use Exception;

abstract class BaseNotRememberMeCookie
{

    public $name;

    public $secure = false; // Whether to only send the cookie through HTTPS

    public $httpOnly = false; // Whether to hide the cookie from JavaScript

    public $cookieDomain;

    public $cookiePath;

    public $cookiePrefix;

    public function __construct($name)
    {
        $this->name = $name;

        $config = config(App::class);

        if (!$config)
        {
            throw new Exception('App config not found.');
        }

        $this->cookieDomain = $config->cookieDomain;

        $this->cookiePath = $config->cookiePath;

        $this->cookiePrefix = $config->cookiePrefix;
    }

    public function getToken()
    {
        helper(['cookie']);

        return get_cookie($this->name);
    }

    public function setToken(string $token)
    {
        helper(['cookie']);

        return set_cookie(
            $this->name,
            $token,
            0,
            $this->cookieDomain,
            $this->cookiePath,
            $this->cookiePrefix,
            $this->secure,
            $this->httpOnly
        );
    }

    public function deleteToken()
    {
        helper(['cookie']);

        return delete_cookie(
            $this->name, 
            $this->cookieDomain, 
            $this->cookiePath, 
            $this->cookiePrefix
        );
    }

}