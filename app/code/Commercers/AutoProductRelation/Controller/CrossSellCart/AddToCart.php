<?php

namespace Commercers\AutoProductRelation\Controller\CrossSellCart;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory; 
class AddToCart extends \Magento\Framework\App\Action\Action {
    protected $_messageManager;
    public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Framework\Message\ManagerInterface $messageManager
    ) {     
        parent::__construct($context);
         $this->_messageManager = $messageManager;
    }
    public function execute() {
//        $params = $this->getRequest()->getParams();
//        $referralCode = $params['referralCode'];
//        $crosssell =  $params['crosssell'];
        $productIds = $this->getRequest()->getParam('crosssell_products');
        if($productIds == NULL){
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            $this->_messageManager->addErrorMessage(__('Please choose the items. Thank you.'));
            return $resultRedirect;
        }
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
                $this->messageManager->addSuccess(__('Item added to the cart successfully.'));
            } catch (\Exception $e) {
                $this->messageManager->addError(__('Cannot add this product to cart.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('/');
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();
//        $uri = 'crosssell/1/referralCode/'.$referralCode;
//        $uri = base64_encode($uri);
//        $resultRedirect->setPath('checkout/cart',['params'=>$uri]);
        $resultRedirect->setPath('checkout/cart/');
        return $resultRedirect;
    }
}
