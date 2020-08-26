<?php

namespace Commercers\Profilers\Block\Adminhtml\Form;

class Rule extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'index_rule';
        $this->_headerText = __('Profilers');
        $this->_addButtonLabel = __('Add New Rule');
        parent::_construct();
    }
}