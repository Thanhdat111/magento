<?php
namespace Commercers\Profilers\Block\Adminhtml\Form\Rule\Edit\Export;

class OutputFormat extends \Magento\Backend\Block\Template
{
    
    protected $_template = 'Commercers_Profilers::form/export/outputFormat.phtml';
    
    public function getOutputFormat(){

    	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

    	if (isset($this->loadedData)) {
    		return $this->loadedData;
    	}
    	$itemId = $this->_request->getParam('id');
    	
    	$profiler = $objectManager->create('Commercers\Profilers\Model\Profilers')->load($itemId);
        
    	return $profiler->getData('export_output_template');
    }
    public function getProfilerJsonFormConfig(){

        $configs = array(
            'show_fields_available' => $this->getUrl('*/export/showfieldsavailable'),
            'get_xls_template' => $this->getUrl('*/export/action_test')
        );


        return json_encode($configs);

    }
    
}