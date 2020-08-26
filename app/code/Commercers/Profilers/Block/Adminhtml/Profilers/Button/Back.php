<?php

namespace Commercers\Profilers\Block\Adminhtml\Profilers\Button;
use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic;
/**
 * Class Back
 */
class Back extends Generic
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getUrl('profilers/index/listing')),
            'class' => 'back',
            'sort_order' => 10
        ];
    }
}
