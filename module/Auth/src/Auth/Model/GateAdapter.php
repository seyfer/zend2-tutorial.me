<?php

namespace Auth\Model;

use Zend\Authentication\Adapter\AdapterInterface,
    Zend\Authentication\Result,
    Zend\Db\Adapter\Adapter;

/**
 * Description of GateAdapter
 *
 * @author seyfer
 */
class GateAdapter implements AdapterInterface {

    /**
     * FOR TEST
     * @param type $username
     * @param type $password
     */
    public $username;
    public $password;

    public function __construct($username, $password)
    {
//        $this->setIdentity($username)->setCredential($password);
    }

    /**
     *
     * @return \Zend\Authentication\Result
     * @throws Exception
     */
    public function authenticate()
    {

        try {

            if ($this->username == "seyfer" &&
                    $this->password == "sessfsf") {

                $identity = "user";
                $code     = Result::SUCCESS;
                return new Result($code, $identity);
            }
            else {
                throw new \Exception("Authentication Failed");
            }
        }
        catch (\Exception $e) {
            $code     = Result::FAILURE;
            $identity = "guest";
            return new Result($code, $identity, array($e->getMessage()));
        }
    }

}
