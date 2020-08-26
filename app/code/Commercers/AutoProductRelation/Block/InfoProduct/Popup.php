<?php
namespace Commercers\AutoProductRelation\Block\InfoProduct;

class Popup extends \Magento\Framework\View\Element\Template
{
    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
    {
        parent::__construct($context);
    }
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setTemplate('Commercers_AutoProductRelation::product/popup.phtml');
        return $this;
    }
}