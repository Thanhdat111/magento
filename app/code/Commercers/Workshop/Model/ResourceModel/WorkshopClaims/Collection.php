<?php
namespace Commercers\Workshop\Model\ResourceModel\WorkshopClaims;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init("Commercers\Workshop\Model\WorkshopClaims","Commercers\Workshop\Model\ResourceModel\WorkshopClaims");
    }
}