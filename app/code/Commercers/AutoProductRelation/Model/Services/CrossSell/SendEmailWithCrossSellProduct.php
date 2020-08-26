<?php

namespace Commercers\AutoProductRelation\Model\Services\CrossSell;

class SendEmailWithCrossSellProduct {

    protected $productFactory;
    protected $orderCollectionFactory;
    protected $emailHelper;
    protected $imageHelper;
    protected $date;
    protected $storeManager;
    protected $authSession;
    protected $crossSellFollowFactory;
    protected $couponFactory;
    protected $productRecommendation;
    public function __construct(
            \Magento\Catalog\Model\ProductFactory $productFactory, 
            \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory, 
            \Commercers\AutoProductRelation\Helper\Email $emailHelper, 
            \Magento\Catalog\Helper\Image $imageHelper,
            \Magento\Framework\Stdlib\DateTime\DateTime $date,
            \Magento\Store\Model\StoreManagerInterface $storeManager,
            \Magento\Backend\Model\Auth\Session $authSession,
            \Commercers\AutoProductRelation\Model\CrossSellFollowFactory $crossSellFollowFactory,
            \Magento\SalesRule\Model\CouponFactory $couponFactory,
            \Commercers\AutoProductRelation\Model\ProductRecommendationFactory $productRecommendation

    ) {
        $this->productFactory = $productFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->emailHelper = $emailHelper;
        $this->imageHelper = $imageHelper;
        $this->date = $date;
	    $this->storeManager = $storeManager;
        $this->authSession = $authSession;
        $this->crossSellFollowFactory = $crossSellFollowFactory;
        $this->couponFactory = $couponFactory;
        $this->productRecommendation = $productRecommendation;
    }

    public function execute($idSendMail, $orderId, $customerEmail = '') {
        $data = array();
        $crossSellFollow = $this->crossSellFollowFactory->create()->load($orderId, 'order_id');
        $referralCode = $crossSellFollow->getReferralCode();
        $couponCode =  $crossSellFollow->getPromotionCode();
        $crossSellProductArray = $this->getUrlCrossSellProduct($orderId, $customerEmail);
        $sentAt = $this->date->gmtDate();
        $expire = $this->couponFactory->create()->load($couponCode,'code');
        $expireDay = $expire->getExpirationDate();
        if ($crossSellProductArray && $referralCode) {
            $data = [
                'crossSellProduct' => $crossSellProductArray,
                'referralCode' => $referralCode,
                'customerMail' => $customerEmail,
                'url' => $this->storeManager->getStore()->getBaseUrl(),
                'expireDay' => $expireDay,
                'orderId' => $orderId
            ];
            try {
                if ($customerEmail) {
                    $this->emailHelper->sendEmail($data);
                    $status = 1;
                    $this->updateStatus($idSendMail, $sentAt, $status);
                    $this->ProductRecommendationLog($orderId,$couponCode);
                }
            } catch (Exception $ex) {
                $status = 2;
                $this->updateStatus($idSendMail, $sentAt, $status);
            }
        } else {
            $status = 3;
            $this->updateStatus($idSendMail, $sentAt = '', $status);
        }
        return $data;
    }

    protected function getProductIdFromOrders($orderId) {
        $order = $this->orderCollectionFactory->create()
                ->addFieldToFilter('entity_id', array('eq' => $orderId))
                ->getFirstItem();
        $items = $order->getAllItems();
        $data = array();
        foreach ($items as $item) {
            $data[] = $item->getData('product_id');
        }
        $result = array();
        foreach (array_count_values($data)as $key => $value) {
            $result[] = $key;
        }
        return $result;
    }

    public function getCrossSellProductIds($orderId) {
        $productIds = $this->getProductIdFromOrders($orderId);

        $crossSellProductId = array();
        foreach ($productIds as $productId) {
            //get product by Id
            $product = $this->productFactory->create()->load($productId);
            //get cross-sell of product
            foreach ($product->getCrossSellProducts() as $crossSellProduct) {
                $crossSellProductId[] = $crossSellProduct->getId();
            }
        }
        $crossSellProductId = array_count_values($crossSellProductId);
        $result = array();
        foreach ($crossSellProductId as $key => $value) {
            $result[] = $key;
        }
        return $result;
    }

    protected function getUrlCrossSellProduct($orderId, $customerEmail) {
        $result = array();
        $crossSellProductIds = $this->getCrossSellProductIds($orderId);
        foreach ($crossSellProductIds as $crossSellProductId) {
            $product = $this->productFactory->create()->load($crossSellProductId);
            $result[$product->getSku()] = ['nameProduct' => $product->getName(), 'productId' => $crossSellProductId, 'customerEmail' => $customerEmail,'image'=>$product->getImage()];
        }
        return $result;
    }

    public function updateStatus($idSendMail, $sentAt, $status) {
        $crossSellFollow = $this->crossSellFollowFactory->create()->load($idSendMail);
        $crossSellFollow->setSentAt($sentAt);
        $crossSellFollow->setStatus($status);
        $crossSellFollow->save();
    }
    public function ProductRecommendationLog($orderId,$couponCode) {
        $productCrossSell = $this->getCrossSellProductIds($orderId);
        $productSimpleId = array();
        foreach($productCrossSell as $productId){
           $product = $this->productFactory->create()->load($productId);
           if($product->getTypeId() === 'configurable'){
                $_children = $product->getTypeInstance()->getUsedProducts($product);
                foreach ($_children as $child){
                    $productSimpleId[] = (int)$child->getId();
                }
           }
        }
        $jsonProductCrossSell = json_encode(['product_id' => $productCrossSell,'product_simple_id'=>$productSimpleId]);
        $checkProductRecommendationLog = $this->productRecommendation->create()->load($couponCode,'coupon_code')->getId();
        if(!$checkProductRecommendationLog){
            $productRecommendationLog = $this->productRecommendation->create();
            $productRecommendationLog->addData([
                'coupon_code' => $couponCode,
                'product_linked'=> $jsonProductCrossSell
            ]);
            $productRecommendationLog->save();
        }
    }
}
