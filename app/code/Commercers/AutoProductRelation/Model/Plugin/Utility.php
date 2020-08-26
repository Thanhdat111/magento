<?php

namespace Commercers\AutoProductRelation\Model\Plugin;

class Utility {
	protected $crossSellFollowCollectionFactory;
	protected $messageManager;
	public function __construct(
		\Magento\Framework\Message\ManagerInterface $messageManager,
		\Commercers\AutoProductRelation\Model\ResourceModel\CrossSellFollow\CollectionFactory $crossSellFollowCollectionFactory
	){
		$this->messageManager = $messageManager;
		$this->crossSellFollowCollectionFactory = $crossSellFollowCollectionFactory;
	}
	public function afterCanProcessRule(\Magento\SalesRule\Model\Utility $subject,$result,$rule,$address){
		 $couponCode = $address->getQuote()->getCouponCode();
		 $customerEmail = $address->getQuote()->getCustomerEmail();
		 $crossSellFollowCollection =$this->crossSellFollowCollectionFactory->create();
		 $crossSellFollowCollection->addFieldToFilter('promotion_code',array('eq'=>$couponCode));
		 
		 foreach($crossSellFollowCollection as $item){
			 if($item->getData()['customer_email'] == $customerEmail){
				 return $result;
			 }
		 }
	}
}