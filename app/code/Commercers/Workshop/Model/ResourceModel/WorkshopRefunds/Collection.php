<?php
namespace Commercers\Workshop\Model\ResourceModel\WorkshopRefunds;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init("Commercers\Workshop\Model\WorkshopRefunds","Commercers\Workshop\Model\ResourceModel\WorkshopRefunds");
    }
}