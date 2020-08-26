<?php

namespace Commercers\WarehouseManagement\Controller\Adminhtml\Warehouse;
class ReBooking extends \Commercers\WarehouseManagement\Controller\Adminhtml\Warehouses
{
	public function execute()
	{
		$resultPage = $this->resultPageFactory->create();
		$resultPage->getConfig()->getTitle()->prepend((__('Re-Booking')));
		return $resultPage;
	}


}