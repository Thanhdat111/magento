<?php

namespace Commercers\WarehouseManagement\Controller\Adminhtml\Warehouse;

use Magento\Framework\Controller\ResultFactory;

class EditAction extends \Commercers\WarehouseManagement\Controller\Adminhtml\Warehouses
{

    public function execute()
    {
        //echo $this->getRequest()->getFullActionName();exit;
    	$id = $this->getRequest()->getParam('id');
    	//$this->_coreSession->start();
		//$this->_coreSession->setMessage($id);
        if($id)
		$this->_getSession()->setId($id);
        $resultPage = $this->resultPageFactory->create();
		$resultPage->getConfig()->getTitle()->prepend((__('Warehouse Management')));
		return $resultPage;
    }
}
