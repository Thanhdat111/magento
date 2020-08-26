<?php

namespace Commercers\AutoContent\Ui\Component\AutoContent;

class Status implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 0, 'label' => __('Failed')],
                ['value' => 1, 'label' => __('Successfully')]
               ];
    }
}