<?php
/**
 * Commercers Vietnam
 * Toan Dao 
 */
namespace Commercers\WarehouseManagement\Model;

class AreaWarehouse extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Commercers\WarehouseManagement\Model\ResourceModel\AreaWarehouse');
    }
}
