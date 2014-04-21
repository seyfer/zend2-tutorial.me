<?php

namespace Users\Model;

use Users\Model\BaseTable;

/**
 * Description of UploadTable
 *
 * @author seyfer
 */
class UploadTable extends BaseTable
{

    public function getUploadsByUserId($userId)
    {
        $userId = (int) $userId;

        $rowset = $this->tableGateway->select(
                array('user_id' => $userId));
        return $rowset;
    }

}
