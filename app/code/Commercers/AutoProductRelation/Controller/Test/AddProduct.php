<?php

namespace Commercers\AutoProductRelation\Controller\Test;
use Magento\Backend\App\Action;
class AddProduct extends \Magento\Framework\App\Action\Action {
    protected $generateCouponCodesService;
    protected $checkoutSession;
    public function __construct(
\Magento\Framework\App\Action\Context $context,
\Commercers\AutoProductRelation\Model\Services\CartCoupon\GenerateCouponCodesService $generateCouponCodesService,
 \Magento\Checkout\Model\Session $checkoutSession
) {     
    $this->generateCouponCodesService = $generateCouponCodesService;
    $this->checkoutSession = $checkoutSession;
    parent::__construct($context);
}

    public function execute() {
        $productIds = $this->getRequest()->getParam('crosssell_products');
        foreach($productIds as $productId) {
            try {
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $product = $objectManager->create('\Magento\Catalog\Model\Product')->load($productId);
                //echo $product->getPrice();exit;
                $cart = $objectManager->create('Magento\Checkout\Model\Cart');
                $params = array(
                    //'product' => $productId,
                    'qty' => 1
                );
                $cart->addProduct($product, $params);
                $cart->save();
                $cart->getQuote()->setTotalsCollectedFlag(false)->collectTotals()->save();
                $couponCode = $this->generateCouponCodesService->execute();
                $this->messageManager->addSuccess(__('Item added to the cart successfully.'));
            } catch (\Exception $e) {
                $this->messageManager->addError(__('Cannot add this product to cart.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('/');
            }
        }
        $this->checkoutSession->getQuote()->setCouponCode($couponCode)->collectTotals()->save();
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('checkout/cart');
        return $resultRedirect;
    }

}
