<?php

namespace Denis303\NotRememberMe;

use Config\Services;

class NotRememberMeSession
{

    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }    

    public function getToken()
    {
        $session = Services::session();

        return $session->get($this->name);
    }

    public function setToken(string $token)
    {
        $session = Services::session();

        return $session->set($this->name, $token);
    }

    public function deleteToken()
    {
        $session = Services::session();

        return $session->remove($this->name);
    }

}