<?php
namespace Commercers\Workshop\Controller\Adminhtml\Note;

class EditNote extends \Magento\Backend\App\Action
{
     protected $resultPageFactory;
     public function __construct(
	     \Magento\Backend\App\Action\Context $context,
          \Magento\Framework\View\Result\PageFactory $resultPageFactory

     ) {
          $this->resultPageFactory = $resultPageFactory;
          parent::__construct($context);

     }
     protected function _isAllowed()
     {
          return true;
     }
     public function execute()
     {
          $this->_view->loadLayout();
          $this->_view->renderLayout();
     }
}