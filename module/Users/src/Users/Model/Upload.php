<?php

namespace Users\Model;

use Users\Model\BaseModel;

/**
 * Description of Upload
 *
 * @author seyfer
 */
class Upload extends BaseModel
{

    protected $id;
    protected $filename;
    protected $label;
    protected $user_id;

    function exchangeArray($data)
    {
        $this->id       = (isset($data['id'])) ?
                $data['id'] : null;
        $this->filename = (isset($data['filename'])) ?
                $data['filename'] : null;
        $this->label    = (isset($data['label'])) ?
                $data['label'] : null;
        $this->user_id  = (isset($data['user_id'])) ?
                $data['user_id'] : null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

}
