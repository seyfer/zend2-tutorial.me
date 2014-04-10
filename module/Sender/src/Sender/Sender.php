<?php

namespace Sender;

use Zend\Stdlib\Parameters;
use Zend\Http\Request,
    Zend\Http\Client;

/**
 * Description of Sender
 *
 * @author seyfer
 */
class Sender {

    /**
     *
     * @var Client
     */
    private $client;
    private $url;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAdapter('Zend\Http\Client\Adapter\Curl');
    }

    /**
     * отправить пост
     * @param \Zend\Stdlib\Parameters|array $post
     * @return type
     * @throws \Auth\Model\Exception
     */
    public function sendPost($url, $post = array())
    {
        $this->url = $url;

        if (!$post instanceof Parameters) {
            if (is_array($post)) {
                $post = new Parameters($post);
            } else {
                throw new Exception(__METHOD__ . " param need: Parameters or array");
            }
        }

        $postRequest = $this->preparePostRequest($post);

        try {
            $response = $this->client->send($postRequest);
            $result   = $response->getBody();

            return $result;
        } catch (\Exception $exc) {
            throw $exc;
        }
    }

    private function preparePostRequest($post)
    {
        $postRequest = new Request();
        $postRequest->setMethod(Request::METHOD_POST);
        $postRequest->setPost($post);
        $postRequest->setUri($this->url);
        $postRequest->getHeaders()->addHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
        ]);

        return $postRequest;
    }

}
