<?php

namespace Page\Model;

use Page\Model\Page;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;

/**
 * Description of PageTable
 *
 * @author seyfer
 */
class PageTable extends AbstractTableGateway {

    private $table = "page";

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;

        $this->resultSetPrototype = new ResultSet;
        $this->resultSetPrototype->setArrayObjectPrototype(new Page());

        $this->initialize();
    }

    public function fetchAll()
    {
        $resultSet = $this->select();

        return $resultSet;
    }

    public function getPage($id)
    {
        $idSanit = (int) $id;

        $rowSet = $this->select(array(
            "id" => $idSanit,
        ));

        $row = $rowSet->current();

        if (!$row) {
            throw new Exception(__METHOD__ . " не найдена страница");
        }

        return $row;
    }

    public function savePage(Page $page)
    {
        $data = $page->exchangeArray($data);
    }

    public function deletePage($id)
    {

    }

}
