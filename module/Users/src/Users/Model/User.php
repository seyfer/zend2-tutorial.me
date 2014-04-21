<?php

namespace Users\Model;

use Users\Model\BaseModel;

/**
 * Description of User
 *
 * @author seyfer
 */
class User extends BaseModel
{

    protected $id;
    protected $name;
    protected $email;
    protected $password;

    public function setPassword($clear_password)
    {
        $this->password = md5($clear_password);
    }

    function exchangeArray($data)
    {
        $this->id    = (isset($data['id'])) ?
                $data['id'] : null;
        $this->name  = (isset($data['name'])) ?
                $data['name'] : null;
        $this->email = (isset($data['email'])) ?
                $data['email'] : null;
        if (isset($data["password"])) {
            $this->setPassword($data["password"]);
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

}
