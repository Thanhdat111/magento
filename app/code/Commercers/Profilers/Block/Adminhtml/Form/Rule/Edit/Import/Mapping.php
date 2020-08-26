<?php
namespace Commercers\Profilers\Block\Adminhtml\Form\Rule\Edit\Import;

class Mapping extends \Magento\Backend\Block\Template
{
    /**
     * Block template.
     *
     * @var string
     */
    protected $_template = 'Commercers_Profilers::form/import/mapping.phtml';

    public function getMapping(){

    	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

    	if (isset($this->loadedData)) {
    		return $this->loadedData;
    	}
    	$itemId = $this->_request->getParam('id');
    	//Load product by product id
    	$profiler = $objectManager->create('Commercers\Profilers\Model\Profilers')->load($itemId);
        
        $this->setProfiler($profiler);

    	return $profiler->getData('import_input_template');
    }
}