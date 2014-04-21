<?php

namespace Users\Model;

use Users\Model\BaseTable,
    Users\Model\BaseModel;

/**
 * Description of UserTable
 *
 * @author seyfer
 */
class UserTable extends BaseTable
{

    public function getUserByEmail($userEmail)
    {
        $rowset = $this->tableGateway->select(array(
            'email' => $userEmail));
        $row    = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $ userEmail");
        }
        return $row;
    }

}
