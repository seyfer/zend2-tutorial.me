<?php

namespace Users\Model;

use Users\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select;

/**
 * Description of UploadTable
 *
 * @author seyfer
 */
class UploadTable extends BaseTable
{

    protected $uploadSharingTableGateway;

    public function __construct(TableGateway $tableGateway, TableGateway $uploadSharingTableGateway = null)
    {
        $this->tableGateway              = $tableGateway;
        $this->uploadSharingTableGateway = $uploadSharingTableGateway;
    }

    public function getUploadsByUserId($userId)
    {
        $userId = (int) $userId;

        $rowset = $this->tableGateway->select(
                array('user_id' => $userId));
        return $rowset;
    }

    public function addSharing($uploadId, $userId)
    {
        $data = array(
            'upload_id' => (int) $uploadId,
            'user_id'   => (int) $userId,
        );
        $this->uploadSharingTableGateway->insert($data);
    }

    public function removeSharing($uploadId, $userId)
    {
        $data = array(
            'upload_id' => (int) $uploadId,
            'user_id'   => (int) $userId,
        );
        $this->uploadSharingTableGateway->delete($data);
    }

    public function getSharedUsers($uploadId)
    {
        $uploadId = (int) $uploadId;

        $rowset = $this->uploadSharingTableGateway->select(
                array('upload_id' => $uploadId));

        return $rowset;
    }

    public function getSharedUploadsForUserId($userId)
    {
        $userId = (int) $userId;
        $rowset = $this->uploadSharingTableGateway->select(
                function (Select $select) use ($userId) {
            $select->columns(array())
                    ->where(array('uploads_sharing.user_id' => $userId))
                    ->join('uploads', 'uploads_sharing.upload_id = uploads.id');
        });
        return $rowset;
    }

}
