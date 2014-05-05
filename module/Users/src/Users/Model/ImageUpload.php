<?php

namespace Users\Model;

use Users\Model\Upload;

/**
 * Description of ImageUpload
 *
 * @author seyfer
 */
class ImageUpload extends Upload
{

    protected $thumbnail;

    public function exchangeArray($data)
    {
        parent::exchangeArray($data);

        $this->thumbnail = (isset($data['thumbnail'])) ?
                $data['thumbnail'] : null;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

}
