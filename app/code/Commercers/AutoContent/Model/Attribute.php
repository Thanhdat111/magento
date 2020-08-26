<?php

namespace Commercers\AutoContent\Model;

class Attribute extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Commercers\AutoContent\Model\ResourceModel\Attribute');
    }
}