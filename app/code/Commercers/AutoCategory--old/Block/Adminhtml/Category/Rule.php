<?php


namespace Commercers\AutoCategory\Block\Adminhtml\Category;


class Rule extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'auto_category';
        $this->_headerText = __('Auto Category Rules');
        $this->_addButtonLabel = __('Add New Rule');
        parent::_construct();
    }
}