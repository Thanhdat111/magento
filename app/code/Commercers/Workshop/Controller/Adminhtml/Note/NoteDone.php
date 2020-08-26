<?php
/**
 *  Commercers Vietnam
 *  Nguyen Duc Hieu 
 */
namespace Commercers\Workshop\Controller\Adminhtml\Note;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class NoteDone extends \Magento\Backend\App\Action
     {   
     /**
          * @var \Magento\Framework\View\Result\PageFactory
          */
     protected $resultPageFactory;

     /**
          * @param \Magento\Backend\App\Action\Context $context
          * @param \Magento\Framework\View\Result\PageFactory resultPageFactory
          */
     public function __construct(
	     \Magento\Backend\App\Action\Context $context,
          \Magento\Framework\View\Result\PageFactory $resultPageFactory,
          \Commercers\Workshop\Model\WorkshopNote $workshopNote
     )
     {
          parent::__construct($context);
          $this->workshopNote = $workshopNote;
          $this->resultPageFactory = $resultPageFactory;
     }
     /**
          * Default customer account page
          *
          * @return void
          */
     public function execute()
     {
          $id = $this->getRequest()->getParam('pk_entity_id');
          $closedAt = date('Y-m-d H:i:s');
          $workshopNote = $this->workshopNote->load($id);
          $workshopNote->setClosedAt($closedAt)->save();
          $this->_redirect('*/*/note');
     }
}