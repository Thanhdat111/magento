<?php
namespace Commercers\Workshop\Model\ResourceModel\WorkshopChat;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init("Commercers\Workshop\Model\WorkshopChat","Commercers\Workshop\Model\ResourceModel\WorkshopChat");
    }
}