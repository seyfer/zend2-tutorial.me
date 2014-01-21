<?php

namespace Auth\Model;

use Zend\Authentication\Adapter\AdapterInterface,
    Zend\Authentication\Result,
    Zend\Db\Adapter\Adapter;
use Auth\Model\GateMcrypt;
use Zend\Stdlib\Parameters;
use Zend\Http\Request,
    Zend\Http\Client;
use Zend\Session\SessionManager;
use Zend\Session\Container;

/**
 * Description of GateAdapter
 *
 * @author seyfer
 */
class GateAdapter implements AdapterInterface {

    const STATUS_OK      = 2;
    const STATUS_ERROR   = 3;
    const STATUS_WARNING = 1;

    /**
     * FOR TEST
     * @param type $username
     * @param type $password
     */
    public $username;
    public $password;
    public $passwordCrypted;
    public $authStatus;
    private $site      = 137;
    private $contratId;
    private $secretKey = "303409u09ru459r5uhfgu498fu";
    private $url       = "http://gate.razlet.ru/port/querydata";
    private $gateAuthContainer;

    public function __construct($username = null, $password = null)
    {
//        $this->setIdentity($username)->setCredential($password);
    }

    public function getStatus()
    {
        return $this->authStatus;
    }

    protected function setStatus($status)
    {
        switch ($status) {
            case "error" :
                $this->setStatusError();
                break;
            case "warning" :
                $this->setStatusWarning();
                break;
            default : $this->authStatus = NULL;
        }
    }

    protected function setStatusWarning()
    {
        $this->authStatus = self::STATUS_WARNING;
    }

    protected function setStatusError()
    {
        $this->authStatus = self::STATUS_ERROR;
    }

    public function setContract($contratId)
    {
        $this->contratId = $contratId;
    }

    public function getContract()
    {
        return $this->contratId;
    }

    public function getAvailableContracts()
    {
        return $this->getAuthContainer()->contracts;
    }

    public function setIdentity($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setCredential($password)
    {
        if ($password) {
            $this->password = $password;
            $this->setPasswordCrypted();
        }

        return $this;
    }

    /**
     * установить зашифрованный пароль
     */
    public function setPasswordCrypted()
    {
        $mcrypt = new GateMcrypt();

        $mcrypt->setCalculatedSalt();
        $mcrypt->setKey($this->secretKey);

        $this->passwordCrypted = base64_encode($mcrypt->encrypt($this->password));
    }

    /**
     * отправить пост
     * @param \Zend\Stdlib\Parameters $post
     * @return type
     * @throws \Auth\Model\Exception
     */
    protected function sendPost(Parameters $post)
    {
//        \Application\Debug::dump(__METHOD__);

        $authRequest = new Request();
        $authRequest->setMethod(Request::METHOD_POST);
        $authRequest->setPost($post);
        $authRequest->setUri($this->url);
        $authRequest->getHeaders()->addHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
        ]);

        $client = new Client();
        $client->setAdapter('Zend\Http\Client\Adapter\Curl');

//        \Application\Debug::dump($authRequest->getPost()->toArray());

        try {
            $response = $client->send($authRequest);
            $result   = $response->getBody();

//            \Application\Debug::dump($result);

            return $result;
        }
        catch (\Exception $exc) {
            throw $exc;
        }
    }

    /**
     * попытка авторизации
     * @throws \Auth\Model\Exception
     * @throws \Exception
     */
    protected function actionLoginAccount()
    {
//        \Application\Debug::dump(__METHOD__);

        $authRequestParams = array(
            'type' => 'LoginAccount',
            'data' => array(
                'email'       => $this->username,
                'password'    => $this->passwordCrypted,
                'site'        => $this->site,
                'contract_id' => $this->getContract()
        ));

        $post = new Parameters($authRequestParams);

        $result = $this->sendPost($post);
//        \Application\Debug::dump($result);

        $resultUnser = unserialize($result);
//        \Application\Debug::dump($resultUnser, "resultUnser");
        //ошибка это конец
        if ($resultUnser['error']) {

            $error = iconv("cp1251", "utf8", $resultUnser['error']);

            $this->setStatusError();
            throw new \Exception($error);
        }

        //выбрать контракт
        if ($resultUnser['warning']) {

            $warning = iconv("cp1251", "utf8", $resultUnser['warning']);
            $this->setStatusWarning();

            $this->setAvailableContracts($resultUnser['contracts']);

            \Application\Debug::dump($warning);

            throw new \Exception($warning);
        }

        $this->getAuthContainer()->user = $resultUnser;

        return TRUE;
    }

    private function setAvailableContracts($contracts)
    {
        foreach ($contracts as $id => $contract) {
            $contractEnc[$id] = iconv("cp1251", "utf8", $contract);
        }

        $this->getAuthContainer()->contracts = $contractEnc;
    }

    public function clearAvailableContracts()
    {
        $this->getAuthContainer()->contracts = NULL;
    }

    private function getAuthContainer()
    {
        if (!$this->gateAuthContainer) {
            $this->gateAuthContainer = new Container("gateAuth");
        }

        return $this->gateAuthContainer;
    }

    public function actionLogoutAccount()
    {
        $this->getAuthContainer()->user = NULL;
    }

    /**
     *
     * @return \Zend\Authentication\Result
     * @throws Exception
     */
    public function authenticate()
    {
//        \Application\Debug::dump(__METHOD__);

        try {

            $result = $this->actionLoginAccount();

            if ($result) {

                $identity = "user";
                $code     = Result::SUCCESS;
                return new Result($code, $identity, array("Success"));
            }

            throw new \Exception("Authentication Failed");
        }
        catch (\Exception $e) {
            $code     = Result::FAILURE;
            $identity = "guest";
            return new Result($code, $identity, array($e->getMessage()));
        }
    }

}
