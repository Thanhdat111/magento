<?php

namespace Commercers\WarehouseManagement\Controller\Adminhtml\Warehouse;
class Log extends \Commercers\WarehouseManagement\Controller\Adminhtml\Warehouses
{
	public function execute()
	{
		$resultPage = $this->resultPageFactory->create();
		$resultPage->getConfig()->getTitle()->prepend((__('Warehouse Log')));
		return $resultPage;
	}


}