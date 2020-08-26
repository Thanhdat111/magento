<?php
namespace Commercers\WarehouseManagement\Model\ResourceModel\WarehouseLog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    public function _construct(){
        $this->_init("Commercers\WarehouseManagement\Model\WarehouseLog","Commercers\WarehouseManagement\Model\ResourceModel\WarehouseLog");
    }
}