<?php
namespace Commercers\Workshop\Controller\Frontend;

class Index extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
	protected $customerSession;

	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Framework\View\Result\PageFactory $pageFactory)
	{
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
		$resultPage->getConfig()->getTitle()->set('Begadi Workshop');
		return $resultPage;
	}
}