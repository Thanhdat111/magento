<?php
namespace Commercers\WarehouseManagement\Model\ResourceModel\RowWarehouse;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    public function _construct(){
        $this->_init("Commercers\WarehouseManagement\Model\RowWarehouse","Commercers\WarehouseManagement\Model\ResourceModel\RowWarehouse");
    }
}