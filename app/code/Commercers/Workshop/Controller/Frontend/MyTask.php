<?php
namespace Commercers\Workshop\Controller\Frontend;

class MyTask extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;

	public function __construct(
		\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $pageFactory) {
		parent::__construct($context);
		$this->_pageFactory = $pageFactory;
	}

	public function execute() {
		$resultPage = $this->_pageFactory->create();
		$resultPage->getConfig()->getTitle()->set('Mein Werkstattauftrag');
		return $resultPage;
	}
}