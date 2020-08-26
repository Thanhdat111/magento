<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Commercers\AutoProductRelation\Controller\CouponCode;

use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Framework\Exception\EmailNotConfirmedException;
use Magento\Framework\Exception\AuthenticationException;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\State\UserLockedException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AutoApply extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Customer\Api\AccountManagementInterface
     */
    protected $customerAccountManagement;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $formKeyValidator;

    /**
     * @var AccountRedirect
     */
    protected $accountRedirect;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\PhpCookieManager
     */
    private $cookieMetadataManager;

    protected $checkoutSession;
    protected  $_eventManager;
    protected $crossSellFollowCollection;
    protected $objectFactory;
    protected $productConfigurable;
    protected $productFactory;
    /**
     * @param Context $context
     * @param Session $customerSession
     * @param AccountManagementInterface $customerAccountManagement
     * @param CustomerUrl $customerHelperData
     * @param Validator $formKeyValidator
     * @param AccountRedirect $accountRedirect
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        AccountManagementInterface $customerAccountManagement,
        CustomerUrl $customerHelperData,
        Validator $formKeyValidator,
        AccountRedirect $accountRedirect,
        CustomerRepositoryInterface $customerRepository,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Framework\Event\Manager $eventManager,
        \Magento\Framework\DataObjectFactory $objectFactory,
        \Commercers\AutoProductRelation\Model\ResourceModel\CrossSellFollow\CollectionFactory $crossSellFollowCollection,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $productConfigurable,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory, 
        \Magento\Catalog\Model\ProductFactory $productFactory
            
    ) {
        $this->session = $customerSession;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->customerUrl = $customerHelperData;
        $this->formKeyValidator = $formKeyValidator;
        $this->accountRedirect = $accountRedirect;
        $this->customerRepository = $customerRepository;
        $this->checkoutSession = $checkoutSession;
        $this->customer = $customer;
        $this->_eventManager = $eventManager;
        $this->objectFactory = $objectFactory;
        $this->crossSellFollowCollection = $crossSellFollowCollection;
        $this->productConfigurable = $productConfigurable;
        $this->productFactory = $productFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        parent::__construct($context);
    }

    /**
     * Get scope config
     *
     * @return ScopeConfigInterface
     * @deprecated 100.0.10
     */
    private function getScopeConfig()
    {
        if (!($this->scopeConfig instanceof \Magento\Framework\App\Config\ScopeConfigInterface)) {
            return \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\App\Config\ScopeConfigInterface::class
            );
        } else {
            return $this->scopeConfig;
        }
    }

    /**
     * Retrieve cookie manager
     *
     * @deprecated 100.1.0
     * @return \Magento\Framework\Stdlib\Cookie\PhpCookieManager
     */
    private function getCookieManager()
    {
        if (!$this->cookieMetadataManager) {
            $this->cookieMetadataManager = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\Stdlib\Cookie\PhpCookieManager::class
            );
        }
        return $this->cookieMetadataManager;
    }

    /**
     * Retrieve cookie metadata factory
     *
     * @deprecated 100.1.0
     * @return \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    private function getCookieMetadataFactory()
    {
        if (!$this->cookieMetadataFactory) {
            $this->cookieMetadataFactory = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory::class
            );
        }
        return $this->cookieMetadataFactory;
    }

    /**
     * Login post action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute() {
        $dataRequest = $this->_urlDecode();
        $email = $dataRequest->getEmail();
        $referralCode = $dataRequest->getReferralCode();
        $orderId = (int)$dataRequest->getOrderId();
        $order = $this->orderCollectionFactory->create()
                ->addFieldToFilter('entity_id', array('eq' => $orderId))
                ->getFirstItem();
        $crossSellFollowCollection = $this->crossSellFollowCollection->create()
                ->addFieldToFilter('referral_code',array('eq'=>$referralCode))->getFirstItem();
        $couponCode = $crossSellFollowCollection->getPromotionCode();
        $customer = $this->customer;
        $customer->setWebsiteId($order->getStoreId());
        $customer->loadByEmail($email);
        $userId = $customer->getId();
        if($userId){
            $customer = $this->customerRepository->get($email);
            //echo $customer->getId(); exit;
            $this->session->setCustomerDataAsLoggedIn($customer);
            $this->session->regenerateId();
            if ($this->getCookieManager()->getCookie('mage-cache-sessid')) {
                $metadata = $this->getCookieMetadataFactory()->createCookieMetadata();
                $metadata->setPath('/');
                $this->getCookieManager()->deleteCookie('mage-cache-sessid', $metadata);
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $productId = $dataRequest->getProductId();
        $product = $this->productFactory->create()->load($productId);
        $productAttributeOptions = $this->productConfigurable->getConfigurableAttributesAsArray($product);
        if($productAttributeOptions){
            $url = $product->getProductUrl();
            $this->checkoutSession->getQuote()->setCouponCode($couponCode)->collectTotals()->save();
            $resultRedirect->setPath($url);
        }else{
            $this->addProductToCart($product);
            $this->checkoutSession->getQuote()->setCouponCode($couponCode)->collectTotals()->save();
//            $uri = 'crosssell/1/referralCode/'.$referralCode;
//            $uri = base64_encode($uri);
//            $resultRedirect->setPath('checkout/cart/',array('params' => $auri));
            $resultRedirect->setPath('checkout/cart/');
        }
        return $resultRedirect;
}
    public function addProductToCart($product){
    try{
        $objectManager=\Magento\Framework\App\ObjectManager::getInstance();
        $cart = $objectManager->create('Magento\Checkout\Model\Cart');  
        $params = array(
                        'qty'   =>  1           
                        );      
        $cart->addProduct($product, $params);
        $cart->save();
        $cart->getQuote()->setTotalsCollectedFlag(false)->collectTotals()->save();
        
        $this->messageManager->addSuccess(__('Item added to the cart successfully.'));
    }catch(\Exception $e){
        $this->messageManager->addError(__('Cannot add this product to cart.'));
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('/');
    }
       
    }
    protected function _urlDecode(){
        $param = $this->getRequest()->getParam('params');
        $param = base64_decode($param);
        $param = explode('/',$param);
        $object  = $this->objectFactory->create();
        $object->setProductId($param[1]);
        $object->setEmail($param[3]);
        $object->setReferralCode($param[5]);
        $object->setOrderId($param[7]);
        return $object;
    }
}
