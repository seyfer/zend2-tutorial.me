<?php

namespace Users\Form;

use Zend\Form\Form;
use Users\Model\UserTable;

/**
 * Description of BaseForm
 *
 * @author seyfer
 */
class BaseForm extends Form
{

    /**
     *
     * @var UserTable
     */
    protected $userTable;

    public function setUserTable(UserTable $userTable)
    {
        $this->userTable = $userTable;
    }

}
