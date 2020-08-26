<?php

namespace Commercers\AutoProductRelation\Model\Services\CrossSell;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ObjectManager;

class CrossSellFollowUp implements ObserverInterface {

    protected $_order;
    protected $crossSellFollow;
    protected $generateCouponCodesService;
    public function __construct(
    \Commercers\AutoProductRelation\Model\Services\CartCoupon\GenerateCouponCodesService $generateCouponCodesService,      
    \Magento\Sales\Api\Data\OrderInterface $order,
    \Commercers\AutoProductRelation\Model\CrossSellFollowFactory $crossSellFollow
    ) {
        $this->generateCouponCodesService = $generateCouponCodesService;
        $this->_order = $order;
        $this->crossSellFollow = $crossSellFollow;
    }

    /**
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer) {
        $couponCode = $this->generateCouponCodesService->execute();
        $referralCode = rand();
        $orderId = $observer->getEvent()->getOrderIds();
        $order = $this->_order->load($orderId);
       // echo "<pre>";print_r($order->getData());exit;
        $customerEmail = $order->getCustomerEmail();
        //$dataSendEMail = $this->sendEmailWithCrossSellProduct->execute();
        $crossSellFollow = $this->crossSellFollow->create();
        $crossSellFollow->addData([
            'order_id' => $order->getId(),
            'customer_email' => $customerEmail,
            'promotion_code' => $couponCode,
            'referral_code' => $referralCode,
            'created_at' => $order->getCreatedAt(),
            'sent_at' => '',
            'status' => 0
        ])->save();
    }

}
