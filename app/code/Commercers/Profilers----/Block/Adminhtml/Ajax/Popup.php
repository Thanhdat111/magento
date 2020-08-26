<?php
namespace  Commercers\Profilers\Block\Adminhtml\Ajax;

class Popup extends \Magento\Framework\View\Element\Template
{
    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
    {
        parent::__construct($context);
    }
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setTemplate('Commercers_Profilers::popup.phtml');
        return $this;
    }
}