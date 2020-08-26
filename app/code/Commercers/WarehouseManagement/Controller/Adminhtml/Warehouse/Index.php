<?php

namespace Commercers\WarehouseManagement\Controller\Adminhtml\Warehouse;
class Index extends \Commercers\WarehouseManagement\Controller\Adminhtml\Warehouses
{
	public function execute()
	{
//               echo $this->getRequest()->getFullActionName();exit;
		$resultPage = $this->resultPageFactory->create();
		$resultPage->getConfig()->getTitle()->prepend((__('Warehouse Management')));
		return $resultPage;
	}


}