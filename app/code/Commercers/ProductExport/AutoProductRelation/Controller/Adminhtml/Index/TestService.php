<?php

namespace Commercers\AutoProductRelation\Controller\Adminhtml\Index;
use Magento\Backend\App\Action;
use Commercers\AutoProductRelation\Model\Services\ProductRepositoryService;
class TestService extends \Magento\Framework\App\Action\Action {

    protected $productRepositoryService;
    public function __construct(
    \Magento\Framework\App\Action\Context $context,
    ProductRepositoryService $productRepositoryService
	){
        $this->productRepositoryService = $productRepositoryService;
        parent::__construct($context);
    }

    public function execute() {
        $productId = 1;
        $linkedProductIds = [3,33,25,24,27];
        $linkTypeId = 1;
        $data = [
            'productId' => $productId,
            'linkedProductIds' => $linkedProductIds,
            'linkTypeId' => $linkTypeId
        ]; 
	$this->productRepositoryService->execute($data);	
		
//		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//		//Send Email
//		
//		$crossSellProduct = $objectManager->create('Commercers\AutoProductRelation\Model\Services\CrossSell\SendEmailWithCrossSellProduct');
//		$crossSellProduct->execute();
		
		//generate coupon code
		//$generateCouponService = $objectManager->create('Commercers\AutoProductRelation\Model\Services\CartCoupon\GenerateCouponCodesService');
		
		//$generateCouponService->execute();
    }

}
