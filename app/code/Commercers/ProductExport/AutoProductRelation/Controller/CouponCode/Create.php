<?php

namespace Commercers\AutoProductRelation\Controller\CouponCode;

class Create extends \Magento\Framework\App\Action\Action {

    protected $pageFactory;
    protected $generateCouponCodeService;

    public function __construct(
    \Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $pageFactory,
	\Commercers\AutoProductRelation\Model\Services\CartCoupon\GenerateCouponCodesService $generateCouponCodeService
    ) {
        $this->pageFactory = $pageFactory;
        $this->generateCouponCodeService = $generateCouponCodeService;
        return parent::__construct($context);
    }

    public function execute() {
        $this->generateCouponCodeService->execute();
        
    }

}
