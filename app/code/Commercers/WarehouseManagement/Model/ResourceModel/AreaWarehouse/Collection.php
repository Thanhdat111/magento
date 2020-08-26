<?php
namespace Commercers\WarehouseManagement\Model\ResourceModel\AreaWarehouse;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    public function _construct(){
        $this->_init("Commercers\WarehouseManagement\Model\AreaWarehouse","Commercers\WarehouseManagement\Model\ResourceModel\AreaWarehouse");
    }
}