<?php

namespace Application\Model;

use Zend\Authentication\Adapter\AdapterInterface,
    Zend\Authentication\Result,
    Zend\Authentication\Adapter\DbTable;
use Zend\Db\Adapter\Adapter;

/**
 * Description of MyAdapter
 *
 * @author seyfer
 */
class MyAdapter extends DbTable implements AdapterInterface {

    /**
     * FOR TEST
     * @param type $username
     * @param type $password
     */
    public $username;
    public $password;

    public function __construct($username, $password)
    {
        $dbConfig = $this->loadDbConfig();

//        \Zend\Debug\Debug::dump($dbConfig, $sm);

        $dbAdapter = new Adapter($dbConfig);

        parent::__construct($dbAdapter, "user", "login", "password");
        $this->username = $username;
        $this->password = $password;

        $this->setIdentity($username)->setCredential($password);
    }

    private function loadDbConfig()
    {
        $dbConfigLocal = \Zend\Config\Factory::fromFile(__DIR__ .
                        '/../../../../../config/autoload/local.php')['db'];

        $dbConfigGlobal = \Zend\Config\Factory::fromFile(__DIR__ .
                        '/../../../../../config/autoload/global.php')['db'];

        $dbConfig = array_merge($dbConfigLocal, $dbConfigGlobal);

        return $dbConfig;
    }

    /**
     *
     * @return \Zend\Authentication\Result
     * @throws Exception
     */
    public function authenticate()
    {
        $res = parent::authenticate();
        return $res;

//        try {
//
//            if ($this->username == "seyfer" &&
//                    $this->password == "sessfsf") {
//
//                $identity = "user";
//                $code     = Result::SUCCESS;
//                return new Result($code, $identity);
//            }
//            else {
//                throw new \Exception("Authentication Failed");
//            }
//        }
//        catch (\Exception $e) {
//            $code     = Result::FAILURE;
//            $identity = "guest";
//            return new Result($code, $identity, array($e->getMessage()));
//        }
    }

}
