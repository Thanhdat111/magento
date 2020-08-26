<?php

namespace Commercers\AutoProductRelation\Model\Services\CrossSell;

class SendEmailWithCrossSellProduct {

    protected $productFactory;
    protected $orderCollectionFactory;
    protected $emailHelper;
    protected $imageHelper;
	protected $storeManager;
    public function __construct(
    \Magento\Catalog\Model\ProductFactory $productFactory, 
            \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory, 
            \Commercers\AutoProductRelation\Helper\Email $emailHelper, 
            \Magento\Catalog\Helper\Image $imageHelper,
			\Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->productFactory = $productFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->emailHelper = $emailHelper;
        $this->imageHelper = $imageHelper;
		$this->storeManager = $storeManager;
    }

    public function execute() {
        $crossSellProductArray = $this->getUrlCrossSellProduct();
        
       // echo '<pre>';print_r($crossSellProductArray); exit;
        try {
            $this->emailHelper->sendEmail($crossSellProductArray);
        } catch (Exception $ex) {
            
        }
    }

    protected function getProductIdFromOrders() {
        $orders = $this->orderCollectionFactory->create()->addAttributeToSelect('*');
        $data = array();
        foreach ($orders as $order) {
            foreach ($order->getAllItems() as $item) {
                $data[] = $item->getData('product_id');
            }
        }
        $result = array();
        foreach (array_count_values($data)as $key => $value) {
            $result[] = $key;
        }
        
        return $result;
    }

    protected function getCrossSellProductIds() {
        $productIds = $this->getProductIdFromOrders();

        $crossSellProductId = array();
        foreach ($productIds as $productId) {
            //get product by Id
            $product = $this->productFactory->create()->load($productId);
            //get cross-sell of product
            foreach ($product->getCrossSellProducts() as $crossSellProduct) {
                $crossSellProductId[] = $crossSellProduct->getID();
            }
        }
        //
        $crossSellProductId = array_count_values($crossSellProductId);
        $result = array();
        foreach ($crossSellProductId as $key => $value) {
            $result[] = $key;
        }
        return $result;
    }

    protected function getCrossSellProduct() {
        $crossSellProductIds = $this->getCrossSellProductIds();
        $result = array();
       // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $store = $this->storeManager->getStore();
        $url = $this->getUrlCreateCouponCodes().'commercers/couponcode/create';
        //get Sku and small image
        foreach ($crossSellProductIds as $crossSellProductId) {
            $product = $this->productFactory->create()->load($crossSellProductId);
            $imageUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $product->getData('small_image');

            $result[$product->getSku()] = [$product->getName(), $imageUrl, $url];
        }

        return $result;
    }

    protected function getUrlCreateCouponCodes() {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        //$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $store = $this->storeManager->getStore();
        $url = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK);
        return $url;
    }
    
    protected function getUrlCrossSellProduct(){
        $result = array();
        $crossSellProductIds = $this->getCrossSellProductIds();
        foreach ($crossSellProductIds as $crossSellProductId) {
            $product = $this->productFactory->create()->load($crossSellProductId);
			$urlCreateCoupon = $this->getUrlCreateCouponCodes().'commercers/couponcode/create';
            $result[$product->getSku()] = [$product->getName(),$product->getUrlInStore(),$urlCreateCoupon];
        }
        return $result;
    }
}
