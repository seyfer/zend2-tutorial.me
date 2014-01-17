<?php

namespace Page\Model;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class Acl {

    private $clist;

    public function __construct()
    {
        $this->clist = new ZendAcl();
        $this->clist
                ->addRole(new Role('guest'))
                ->addRole(new Role('admin'));

        $this->clist->addResource(new Resource('page'));

        $this->clist
                ->allow(array('guest', 'admin'), 'page', 'view')
                ->allow(array('admin'), 'page', 'add');
    }

    public function getAcl()
    {
        return $this->clist;
    }

}
