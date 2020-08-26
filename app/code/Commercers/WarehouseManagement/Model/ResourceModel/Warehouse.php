<?php
namespace Commercers\WarehouseManagement\Model\ResourceModel;

class Warehouse extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
    \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
    parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('commercers_warehouse_management', 'warehouse_id');
    }

}