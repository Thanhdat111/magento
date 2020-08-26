<?php

namespace Commercers\WarehouseManagement\Block\Adminhtml\ReBooking;

class Form extends \Magento\Backend\Block\Template {

    
    public function __construct(
            \Magento\Framework\App\ResourceConnection $resource, 
            \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
            \Magento\Framework\Data\Form\FormKey $formKey,
            \Magento\Backend\Helper\Data $HelperBackend,
            \Magento\Backend\Block\Template\Context $context
    ) {
        $this->resource = $resource;
        $this->formKey = $formKey;
        $this->scopeConfig = $scopeConfig;
        $this->HelperBackend = $HelperBackend;
        parent::__construct($context);
    }

    protected function _prepareLayout() {
        return parent::_prepareLayout();
    }

   public function getSaveUrl(){
        return $this->getUrl('*/*/rebooking_save');
    }
    public function getBackUrl(){
        return $this->HelperBackend->getHomePageUrl();
    }
    public function getFormKey()
    {
         return $this->formKey->getFormKey();
    }
    public function getUrlAjax(){
        
        $urls = array(
            'url_load_warehouse' => $this->getUrl('backend/warehouse_rebooking/loadwarehouse')
        );
        return json_encode($urls);
        
    }
}
