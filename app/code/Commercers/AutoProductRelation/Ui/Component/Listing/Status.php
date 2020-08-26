<?php

namespace Commercers\AutoProductRelation\Ui\Component\Listing;

class Status implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 0, 'label' => __('Not Sent')],
                ['value' => 1, 'label' => __('Successfully')],
                ['value' => 2, 'label' => __('Sent Fail')],
                ['value' => 3, 'label' => __('No Linked')]
               ];
    }
}