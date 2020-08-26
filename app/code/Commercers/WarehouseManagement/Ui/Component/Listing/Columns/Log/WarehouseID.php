<?php

namespace Commercers\WarehouseManagement\Ui\Component\Listing\Columns\Log;

class WarehouseID implements \Magento\Framework\Option\ArrayInterface
{
    public function __construct(
       \Commercers\WarehouseManagement\Model\WarehouseFactory $warehouseFactory
    ) {
        $this->warehouseFactory = $warehouseFactory;
    }
    public function toOptionArray()
    {
        $warehouseFactory = $this->warehouseFactory->create();
        $warehouseCollection = $warehouseFactory->getCollection();
        foreach($warehouseCollection as $value){
            $option[] = ['value' => $value->getId(), 'label' => __( $value->getName())];
        }
        return $option;
    }
}