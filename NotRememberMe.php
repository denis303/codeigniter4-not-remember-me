<?php
/**
 * @author denis303 <dev@denis303.com>
 * @license MIT
 * @link http://denis303.com
 */
namespace Denis303\NotRememberMe;

class NotRememberMe
{

    public $name;

    protected $_session;

    protected $_cookie;

    public function __construct($name)
    {
        $this->name = $name;

        $this->_session = new NotRememberMeSession($name);

        $this->_cookie = new NotRememberMeCookie($name);
    }

    public function generateToken()
    {
        return md5(time() . rand(0, PHP_INT_MAX)); 
    }

    public function validateToken()
    {
        $sessionToken = $this->_session->getToken();

        if ($sessionToken)
        {
            $cookieToken = $this->_cookie->getToken();
        
            if ($cookieToken != $sessionToken)
            {
                return false;
            }
        }

        return true;
    }

    public function setToken(string $token)
    {
        $this->_session->setToken($token);

        $this->_cookie->setToken($token);
    }

    public function deleteToken()
    {
        $this->_session->deleteToken();

        $this->_cookie->deleteToken();
    }

}