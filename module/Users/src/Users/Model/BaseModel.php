<?php

namespace Users\Model;

/**
 * Description of BaseModel
 *
 * @author seyfer
 */
abstract class BaseModel
{

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}
