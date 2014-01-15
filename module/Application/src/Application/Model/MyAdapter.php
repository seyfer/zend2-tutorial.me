<?php

namespace Application\Model;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

/**
 * Description of MyAdapter
 *
 * @author seyfer
 */
class MyAdapter implements AdapterInterface {

    /**
     * FOR TEST
     * @param type $username
     * @param type $password
     */
    public $username = "seyfer";
    public $password = "seed1212";

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
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
                    $this->password == "seed1212") {

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
