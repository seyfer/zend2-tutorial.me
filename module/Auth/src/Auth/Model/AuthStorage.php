<?php

namespace Auth\Model;

use Zend\Authentication\Storage;

/**
 * Description of AuthStorage
 *
 * @author seyfer
 */
class AuthStorage extends Storage\Session
{

    /**
     * minutes by default
     * @var type
     */
    private $expTime;

    public function __construct($namespace = null, $member = null, \Zend\Session\ManagerInterface $manager = null)
    {
        parent::__construct($namespace, $member, $manager);

        $this->expTime = 2 * 60 * 60;
    }

    public function setRememberMe($rememberMe = 0, $time = 0)
    {
        $expTime = $time ? $time : $this->expTime;

        if ($rememberMe == 1) {
            $this->session->getManager()->rememberMe($expTime);
        }
    }

    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }

}
