<?php

namespace Commercers\AutoProductRelation\Controller\Adminhtml\Index;
use Magento\Backend\App\Action;

class ToanTester extends \Magento\Framework\App\Action\Action {
    protected $generateCouponCodesService;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Commercers\AutoProductRelation\Model\Services\CartCoupon\GenerateCouponCodesService $generateCouponCodesService      
    ){
        $this->generateCouponCodesService = $generateCouponCodesService;
        parent::__construct($context);
    }

    public function execute(){ 
        $abc = $this->generateCouponCodesService->execute();
        print_r($abc);exit;
    }
   
}