<?php


namespace Commercers\MissingProduct\Model;


use Magento\Framework\Model\AbstractModel;

class MissingProduct extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Commercers\MissingProduct\Model\ResourceModel\MissingProduct::class);
    }

}