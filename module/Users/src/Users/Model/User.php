<?php

namespace Users\Model;

/**
 * Description of User
 *
 * @author seyfer
 */
class User
{

    public $id;
    public $name;
    public $email;
    public $password;

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

    function getArrayCopy()
    {
        return get_object_vars($this);
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
