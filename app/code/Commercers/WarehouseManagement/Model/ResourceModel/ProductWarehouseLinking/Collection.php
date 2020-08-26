<?php
namespace Commercers\WarehouseManagement\Model\ResourceModel\ProductWarehouseLinking;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    public function _construct(){
        $this->_init("Commercers\WarehouseManagement\Model\ProductWarehouseLinking","Commercers\WarehouseManagement\Model\ResourceModel\ProductWarehouseLinking");
    }
}