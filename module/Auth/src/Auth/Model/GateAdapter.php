<?php

namespace Auth\Model;

use Zend\Authentication\Adapter\AdapterInterface,
    Zend\Authentication\Result,
    Zend\Db\Adapter\Adapter;
use Auth\Model\GateMcrypt;
use Zend\Stdlib\Parameters;
use Zend\Http\Request,
    Zend\Http\Client;

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
    public $passwordCrypted;
    private $site      = 137;
    private $contratId;
    private $secretKey = "303409u09ru459r5uhfgu498fu";
    private $url       = "http://gate.razlet.ru/port/querydata";

    public function __construct($username = null, $password = null)
    {
//        $this->setIdentity($username)->setCredential($password);
    }

    public function setContrat($contratId)
    {
        $this->contratId = $contratId;
    }

    public function getContract()
    {
        return $this->contratId;
    }

    public function setIdentity($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setCredential($password)
    {
        $this->password = $password;
        $this->setPasswordCrypted();
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
        \Zend\Debug\Debug::dump(__METHOD__);

        $authRequest = new Request();
        $authRequest->setMethod(Request::METHOD_POST);
        $authRequest->setPost($post);
        $authRequest->setUri($this->url);
        $authRequest->getHeaders()->addHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
        ]);

        $client = new Client();
        $client->setAdapter('Zend\Http\Client\Adapter\Curl');

        \Zend\Debug\Debug::dump($authRequest->getPost()->toArray());

        try {
            $response = $client->send($authRequest);
            $result   = $response->getBody();

            \Zend\Debug\Debug::dump($result);

            return $result;
        }
        catch (\Exception $exc) {
            \Zend\Debug\Debug::dump($exc->getMessage());
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
        \Zend\Debug\Debug::dump(__METHOD__);

        $authRequestParams = array(
            'type' => 'LoginAccount',
            'data' => array(
                'email'       => $this->username,
                'password'    => $this->passwordCrypted,
                'site'        => $this->site,
                'contract_id' => $this->getContract()
        ));

        $post = new Parameters($authRequestParams);

        try {
            $result = $this->sendPost($post);
            \Zend\Debug\Debug::dump($result);
        }
        catch (\Exception $exc) {
            throw $exc;
        }

        $resultUnser = unserialize($result);
        \Zend\Debug\Debug::dump($resultUnser);

        //ошибка это конец
        if ($resultUnser['error']) {

            $error = iconv("cp1251", "utf8", $resultUnser['error']);
            \Zend\Debug\Debug::dump($error);

            throw new \Exception($error);
        }

        //выбрать контракт
        if ($resultUnser['warning']) {
            $warning = iconv("cp1251", "utf8", $resultUnser['error']);
            \Zend\Debug\Debug::dump($warning);

            throw new \Exception($warning);
        }
    }

    /**
     *
     * @return \Zend\Authentication\Result
     * @throws Exception
     */
    public function authenticate()
    {
        \Zend\Debug\Debug::dump(__METHOD__);

        try {

            $this->actionLoginAccount();

//            \Zend\Debug\Debug::dump($this->username, $this->password);
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

            throw new \Exception("Authentication Failed");
        }
        catch (\Exception $e) {
            $code     = Result::FAILURE;
            $identity = "guest";
            return new Result($code, $identity, array($e->getMessage()));
        }
    }

}
