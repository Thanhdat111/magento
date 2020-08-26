<?php

namespace Commercers\WarehouseManagement\Ui\Component\Listing\Columns\Log;

class Type implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('Order')],
            ['value' => 2, 'label' => __('Receiving')]
        ];
    }
}