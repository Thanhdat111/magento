<?php

namespace Commercers\AutoProductRelation\Block\Adminhtml\Crosssell;

class Rule extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'crosssell_rule';
        $this->_headerText = __('ProductRelation AutoProductRelation');
        $this->_addButtonLabel = __('Add New Rule');
        parent::_construct();
    }
}