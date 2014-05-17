<?php

namespace Users\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Description of BaseTable
 *
 * @author seyfer
 */
abstract class BaseTable
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function save(BaseModel $model)
    {
        $data = $model->getArrayCopy();

        $id = (int) $model->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception(get_class($model) . ' ID does not exist');
            }
        }
    }
    
    public function getLastInsertValue() {
        return $this->tableGateway->getLastInsertValue();
    }

    public function getById($id)
    {
        $id     = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row    = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function deleteById($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

}
