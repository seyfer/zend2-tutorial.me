<?php

namespace Auth\Model;

use Zend\Authentication\Storage;

/**
 * Description of AuthStorage
 *
 * @author seyfer
 */
class AuthStorage extends Storage\Session {

    /**
     * minutes
     * @var type
     */
    private $expTime;

    public function setRememberMe($rememberMe = 0, $time = 1209600)
    {
        $this->expTime = 15 * 60;

        if ($rememberMe == 1) {
            $this->session->getManager()->rememberMe($this->expTime);
        }
    }

    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }

}
