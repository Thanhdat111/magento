<?php
namespace Commercers\WarehouseManagement\Model\ResourceModel\Warehouse;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    public function _construct(){
        $this->_init("Commercers\WarehouseManagement\Model\Warehouse","Commercers\WarehouseManagement\Model\ResourceModel\Warehouse");
    }
}