<?php

namespace Commercers\AutoContent\Block\Adminhtml\Content;

class Rule extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'content_rule';
        $this->_headerText = __('Auto Content');
        $this->_addButtonLabel = __('Add New Rule');
        parent::_construct();
    }
}