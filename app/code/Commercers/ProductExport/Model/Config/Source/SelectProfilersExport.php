<?php

namespace Commercers\ProductExport\Model\Config\Source;

class SelectProfilersExport
{
    const XML_PATH_PROFILERS = 'section_product_export/group_product_export/folder_profilers_xml_product_export';
    const XML_SELECTED_XML = 'section_product_export/group_product_export/profilers_selected_xml_product_export';
    const XML_SELECTED_DATABASE = 'section_product_export/group_product_export/profilers_selected_database_product_export';
    const XML_ENABLE_PROFILERS_FROM_XML = 'section_product_export/group_product_export/enable_profilers_from_xml_product_export';
    
    protected $_profilers;
    protected $_scopeConfig;
    
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Commercers\Profilers\Service\ProfilerManagement $profilers
    ) 
    {
        $this->_profilers = $profilers; 
        $this->_scopeConfig = $scopeConfig;
    }
    
    public function toOptionArray() { 
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORES;
        $enableProfilersFromXml = $this->_scopeConfig->getValue(self::XML_ENABLE_PROFILERS_FROM_XML, $storeScope); 
        $ret = [];
        if($enableProfilersFromXml == 0){
            //database
            $profilers = $this->getArrayProfilers();
            if ($profilers != '') {
                foreach($profilers as $profiler){
                    if($profiler['data_source'] == 'product' && $profiler['enable_disable'] == 1 && $profiler['import_export'] == 0){
                        $ret[] = [
                            'value' => $profiler['id_profiler'],
                            'label' => $profiler['name']
                        ];
                    }
                }
            }else{
                return false;
            }
        }else{
            //xml
            $profilers = $this->getArrayProfilers(); 
            if ($profilers) {
                foreach($profilers as $profiler){ 
                    if(isset($profiler[0])){
                        //if more product
                        foreach($profiler as $value){
                            if($value['data_source'] == 'product' && $value['enable_disable'] == 1 && $profiler['import_export'] == 0){
                                $ret[] = [
                                    'value' => $value['id'],
                                    'label' => $value['name']
                                ];
                            }
                        }
                    }else{                        
                        //if only product
                        if($profiler['data_source'] == 'product' && $profiler['enable_disable'] == 1 && $profiler['import_export'] == 0){
                            $ret[] = [
                                'value' => $profiler['id'],
                                'label' => $profiler['name']
                            ];
                        }
                    }
                }
            }else{
                return false;
            }
        }
        return $ret;
    }

    public function getArrayProfilers() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORES;
        $configPath = $this->_scopeConfig->getValue(self::XML_PATH_PROFILERS, $storeScope);
        $enableProfilersFromXml = $this->_scopeConfig->getValue(self::XML_ENABLE_PROFILERS_FROM_XML, $storeScope);

        if($enableProfilersFromXml == 1){
            $arr = $this->_profilers->getProfilersFromXml($configPath);
        }else{ 
            $arr = $this->_profilers->getProfilersFromDataBase();
        }
        return $arr;
    }

    public function getProfilersSelected(){
    	$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORES;
        $configPath = $this->_scopeConfig->getValue(self::XML_PATH_PROFILERS, $storeScope);
        $selectedFromXml = $this->_scopeConfig->getValue(self::XML_SELECTED_XML, $storeScope);
        $selectedFromDatabase = $this->_scopeConfig->getValue(self::XML_SELECTED_DATABASE, $storeScope);
        $enableProfilersFromXml = $this->_scopeConfig->getValue(self::XML_ENABLE_PROFILERS_FROM_XML, $storeScope);

        if($enableProfilersFromXml == 1){
            if ($selectedFromXml) {
                $profilersSelected = $this->_profilers->getProfilersSelected($selectedFromXml,$enableProfilersFromXml,$configPath); 
            }
        }else{ 
            if ($selectedFromDatabase) {
                $profilersSelected = $this->_profilers->getProfilersSelected($selectedFromDatabase,$enableProfilersFromXml); 
            }
        }
        return $profilersSelected;
    }

}

