<?php


namespace Commercers\MissingProduct\Model\ResourceModel\MissingProduct;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init("Commercers\MissingProduct\Model\MissingProduct", "Commercers\MissingProduct\Model\ResourceModel\MissingProduct");
    }
}