<?php
namespace Commercers\Workshop\Controller\Frontend;

class WorkshopNew extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
	protected $customerSession;
	protected $_storeManager; 
	protected $product; 

	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Catalog\Model\ProductFactory $product,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\View\Result\PageFactory $pageFactory)
	{
		$this->product = $product; 
		$this->_storeManager = $storeManager; 
		$this->customerSession = $customerSession;
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	public function execute()
	{
		if(!$this->customerSession->isLoggedIn()){
			$this->_redirect('customer/account/');
		}
		$resultPage = $this->_pageFactory->create();
		$resultPage->getConfig()->getTitle()->set('Waffenneukauf');
		return $resultPage;
	}
}