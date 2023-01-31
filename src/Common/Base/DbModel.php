<?php
namespace Common\Base;

use Guillermoandrae\Models\AbstractModel;

class DbModel extends AbstractModel
{
    public function __construct($obj = null)
    {
        return parent::__construct($obj ? $obj->asArray() : []);
    }

    public function isEmpty(): bool
    {
        return empty($this->data);
    }
}
