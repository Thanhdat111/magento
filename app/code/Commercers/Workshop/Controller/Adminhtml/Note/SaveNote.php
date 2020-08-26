<?php
/**
 *  Commercers Vietnam
 *  Nguyen Duc Hieu 
 */
namespace Commercers\Workshop\Controller\Adminhtml\Note;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class SaveNote extends \Magento\Backend\App\Action
     {   
     /**
          * @var \Magento\Framework\View\Result\PageFactory
          */
     protected $resultPageFactory;
     protected $authSession;

     /**
          * @param \Magento\Backend\App\Action\Context $context
          * @param \Magento\Framework\View\Result\PageFactory resultPageFactory
          */
     public function __construct(
	      \Magento\Backend\App\Action\Context $context,
          \Magento\Framework\View\Result\PageFactory $resultPageFactory,
          \Commercers\Workshop\Model\WorkshopNote $workshopNote,
          \Magento\Backend\Model\Auth\Session $authSession
     )
     {
          parent::__construct($context);
          $this->authSession = $authSession;
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
          $entityId = $this->getRequest()->getParam('id');
          $note = $this->getRequest()->getParam('addnote')['note'];
          $adminUserId = $this->authSession->getUser()->getUserId();
          $responsibleAdminId = $this->getRequest()->getParam('addnote')['fk_responsible_admin_id'];
          $responsibleAdmin = $this->getRequest()->getParam('addnote')['responsible_admin'];
          $createdAt = date('Y-m-d H:i:s');

          if (($responsibleAdminId == -1) && ($responsibleAdmin == NULL)) {
               // Mage::getSingleton('adminhtml/session')->addError(Mage::helper('workshop')->__('Bitte Verantwortlichen auswählen'));
               $this->_redirect('*/*/note');
          } elseif ($entityId != NULL) {
               //speichern, wenn Notiz bearbeitet
               $notizen = $this->workshopNote->load($entityId);
               $notizen->setNote($note)
                         ->setFkAuthorAdminId($adminUserId)
                         ->setFkResponsibleAdminId($responsibleAdminId)
                         ->setResponsibleAdmin($responsibleAdmin);
               $notizen->save();
               // Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('workshop')->__('note saved'));
               $this->_redirect('*/*/note');
          } else {
               //speichern, wenn Notiz neu angelegt
               $notizen = $this->workshopNote;
               $notizen->setNote($note)
                         ->setFkAuthorAdminId($adminUserId)
                         ->setFkResponsibleAdminId($responsibleAdminId)
                         ->setResponsibleAdmin($responsibleAdmin)
                         ->setCreatedAt($createdAt);
               $notizen->save();
               // Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('workshop')->__('note saved'));
               $this->_redirect('*/*/note');
          }
     }
}