<?php
namespace Commercers\Profilers\Block\Adminhtml\Profilers\Button;

use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic;

class Import extends Generic
{
    public function getButtonData()
    {
        return [
            'class' => 'import',
            'label' => __('ImportCSV'),
            'on_click' => "",
            'id' => 'id-button-import'
        ];
    }
}