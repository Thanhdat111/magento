<?php

/*
  Commercers Viet Nam
  Nhan Dep Trai
 */

namespace Commercers\Profilers\Controller\Adminhtml\Export;

use Magento\Backend\App\Action;

class ShowFieldsAvailable extends Action {

    protected $_resultPageFactory;
    public function __construct(
    \Magento\Backend\App\Action\Context $context, 
            \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute() {
        //echo $this->getRequest()->getFullActionName();exit;
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Review Available Attributes'));
        return $resultPage;
    }

  

}
