<?php

namespace Commercers\Profilers\Model;

class Attribute extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Commercers\Profilers\Model\ResourceModel\Attribute');
    }
}