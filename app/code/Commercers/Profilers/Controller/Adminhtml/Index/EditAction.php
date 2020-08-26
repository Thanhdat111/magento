<?php

namespace Commercers\Profilers\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;

class EditAction extends \Magento\Backend\App\Action
{
	protected $_registry;
	protected $_coreSession;

	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Magento\Framework\Session\SessionManagerInterface $coreSession
	){
		$this->_pageFactory = $pageFactory;
		$this->_coreSession = $coreSession;
		return parent::__construct($context);
	}

    public function execute()
    {
    	$id = $this->getRequest()->getParam('id');
    	
        $this->_getSession()->setProfilerId($id);
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
