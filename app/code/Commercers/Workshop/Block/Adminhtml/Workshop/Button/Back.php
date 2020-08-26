<?php

namespace Commercers\Workshop\Block\Adminhtml\Workshop\Button;

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
            'on_click' => sprintf("location.href = '%s';", $this->getUrl('workshop/index/index')),
            'class' => 'back',
            'sort_order' => 10
        ];
    }
}
