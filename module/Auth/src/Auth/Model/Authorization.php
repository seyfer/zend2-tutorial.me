<?php

namespace Auth\Model;

use Zend\Session\Container;

/**
 * Description of Authorization
 *
 * @author seyfer
 */
class Authorization {

    /**
     *
     * @var Container
     */
    private $container;

    public function __construct()
    {
        $this->container = new Container("gateAuth");

        if (!$this->container->user) {
//            throw new \Exception(__METHOD__ . " авторизация не выполнена!");
            header("Location:" . "/login");
        }
    }

    public function getContractorId()
    {
        return $this->container->user['contractor']['id'];
    }

}
