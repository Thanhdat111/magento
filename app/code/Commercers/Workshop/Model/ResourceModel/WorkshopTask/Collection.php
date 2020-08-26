<?php
namespace Commercers\Workshop\Model\ResourceModel\WorkshopTask;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init("Commercers\Workshop\Model\WorkshopTask","Commercers\Workshop\Model\ResourceModel\WorkshopTask");
    }
}