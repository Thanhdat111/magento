<?php
namespace Commercers\Workshop\Model\ResourceModel\WorkshopLog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init("Commercers\Workshop\Model\WorkshopLog","Commercers\Workshop\Model\ResourceModel\WorkshopLog");
    }
}