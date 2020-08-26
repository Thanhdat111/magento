<?php
namespace Commercers\AutoProductRelation\Model\Adminhtml\System\Config\Source\Order;

class Status {
	protected $orderStatusCollectionFactory;
	
	public function __construct(
		\Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $orderStatusCollectionFactory
	){
			$this->orderStatusCollectionFactory = $orderStatusCollectionFactory;
	}
	public function toOptionArray(){
		$orderStatus = $this->orderStatusCollectionFactory->create()->toOptionArray();        
        return $orderStatus;
	}
	//public function __toOptionArray(){
		//$collection = $this->orderStatusCollectionFactory->create();        
		//$orderStatus = array();
		//foreach($collection as $item){
		//	$orderStatus[] = ['value' => $item->getData()['status'], 'label' => $item->getData()['label']];
		//}
        //return $orderStatus;
	//}
}