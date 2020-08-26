<?php

namespace Commercers\Profilers\Controller\Adminhtml\Index;

class DeleteAction extends \Magento\Backend\App\Action
{
    protected $_profilerFactory;

	public function __construct(
		\Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Commercers\Profilers\Model\ProfilersFactory $profilerFactory
	){
        $this->_pageFactory = $pageFactory;
        $this->_profilerFactory = $profilerFactory;
		return parent::__construct($context);
	}

    public function execute()
    {
    	$id = $this->getRequest()->getParam('id');
        $collection = $this->_profilerFactory->create()->load($id);
        $collection->delete();
        $this->_redirect('profilers/index/listing');
    }
}
