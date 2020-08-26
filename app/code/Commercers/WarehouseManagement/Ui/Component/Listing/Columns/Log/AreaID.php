<?php

namespace Commercers\WarehouseManagement\Ui\Component\Listing\Columns\Log;

class AreaID implements \Magento\Framework\Option\ArrayInterface
{
    public function __construct(
        \Commercers\WarehouseManagement\Model\AreaWarehouseFactory $areaWarehouseFactory
    ) {
        $this->areaWarehouseFactory = $areaWarehouseFactory;
    }
    public function toOptionArray()
    {
        $areaWarehouseFactory = $this->areaWarehouseFactory->create();
        $areaWarehouseCollection = $areaWarehouseFactory->getCollection();
        foreach($areaWarehouseCollection as $value){
            $option[] =  ['value' => $value->getId(), 'label' => __($value->getArea())];
        }
        return $option;

    }
}