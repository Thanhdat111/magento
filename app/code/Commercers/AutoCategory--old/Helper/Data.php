<?php


namespace Commercers\AutoCategory\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
class Data extends AbstractHelper
{

    const XML_PATH_HELLOWORLD = 'auto_category/';

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    public function getGeneralConfig($code, $storeId = null)
    {

        return $this->getConfigValue(self::XML_PATH_HELLOWORLD .'group_auto_category/'. $code, $storeId);
    }

    public function getAllowedAttributes(){
        $isEnable = $this->getGeneralConfig('enable_auto_category_general');
        $attributes = array();
        if($isEnable){
            $attributes = $this->getGeneralConfig('meta_attributes');
        }

        if($attributes){
            return explode(',', $attributes);
        }
        return array();
    }

}