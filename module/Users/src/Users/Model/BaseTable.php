<?php

namespace Users\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Description of BaseTable
 *
 * @author seyfer
 */
class BaseTable
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

}
