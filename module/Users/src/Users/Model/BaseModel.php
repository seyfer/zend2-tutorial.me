<?php

namespace Users\Model;

/**
 * Description of BaseModel
 *
 * @author seyfer
 */
class BaseModel
{

    function getArrayCopy()
    {
        return get_object_vars($this);
    }

}
