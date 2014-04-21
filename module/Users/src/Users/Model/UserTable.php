<?php

namespace Users\Model;

use Users\Model\BaseTable;

/**
 * Description of UserTable
 *
 * @author seyfer
 */
class UserTable extends BaseTable
{

    public function saveUser(User $user)
    {
        $data = $user->getArrayCopy();

        $id = (int) $user->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('User ID does not exist');
            }
        }
    }

    public function getUser($id)
    {
        $id     = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row    = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getUserByEmail($userEmail)
    {
        $rowset = $this->tableGateway->select(array('email' =>
            $userEmail));
        $row    = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $ userEmail");
        }
        return $row;
    }

    public function deleteUser($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

}
