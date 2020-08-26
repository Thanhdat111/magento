<?php


namespace Mastering\SampleModule\Model\ResourceModel\Items;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init("Mastering\SampleModule\Model\Items", "Mastering\SampleModule\Model\ResourceModel\Items");
    }
}