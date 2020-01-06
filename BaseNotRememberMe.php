<?php
/**
 * CodeIgniter 4 Not Remember Me
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
namespace Denis303\CodeIgniter;

use Config\App;
use Config\Services;
use Exception;

abstract class BaseNotRememberMe
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
            throw new Exception('Config not found.');
        }

        $this->cookieDomain = $config->cookieDomain;

        $this->cookiePath = $config->cookiePath;

        $this->cookiePrefix = $config->cookiePrefix;
    }

    public function generateToken()
    {
        return md5(time() . rand(0, PHP_INT_MAX)); 
    }

    public function deleteToken()
    {
        $this->deleteTokenFromSession();

        $this->deleteTokenFromCookie();
    }

    public function getTokenFromCookie()
    {
        helper(['cookie']);

        return get_cookie($this->name);
    }

    public function setTokenToCookie($token)
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

    public function deleteTokenFromCookie()
    {
        helper(['cookie']);

        return delete_cookie(
            $this->name, 
            $this->cookieDomain, 
            $this->cookiePath, 
            $this->cookiePrefix
        );
    }

    public function getTokenFromSession()
    {
        $session = Services::session();

        return $session->get($this->name);
    }

    public function setTokenToSession(string $token)
    {
        $session = Services::session();

        return $session->set($this->name, $token);
    }

    public function deleteTokenFromSession()
    {
        $session = Services::session();

        return $session->remove($this->name);
    }

    public function setToken($token = null)
    {
        if (!$token)
        {
            $token = $this->generateToken();
        }

        $this->setTokenToSession($token);

        $this->setTokenToCookie($token);
    }

    public function validateToken()
    {
        $token = $this->getTokenFromSession();

        if ($token)
        {
            $cookie = $this->getTokenFromCookie();
        
            if ($cookie != $token)
            {
                return false;
            }
        }

        return true;
    }

}