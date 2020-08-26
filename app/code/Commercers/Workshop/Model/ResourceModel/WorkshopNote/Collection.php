<?php
namespace Commercers\Workshop\Model\ResourceModel\WorkshopNote;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init("Commercers\Workshop\Model\WorkshopNote","Commercers\Workshop\Model\ResourceModel\WorkshopNote");
    }
}