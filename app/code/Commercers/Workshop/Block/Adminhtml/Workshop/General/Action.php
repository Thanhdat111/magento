<?php

namespace Commercers\Workshop\Block\Adminhtml\Workshop\General;
 
class Action extends \Magento\Backend\Block\Template
{
     protected $_template = 'Commercers_Workshop::general/action.phtml';

     const SYS_XML_BACKEND = 'backend_group';
     const SYS_XML_SECTION = 'workshop_options';

     public function getTask(){
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $task = $objectManager->create('\Commercers\Workshop\Model\WorkshopTask');
          $idTask = $this->getRequest()->getParam('id');
          return $task->load($idTask);
     }

     public function getClaimsCollection(){
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $claims = $objectManager->create('\Commercers\Workshop\Model\ResourceModel\WorkshopClaims\CollectionFactory')->create()
                    ->addFieldToFilter('payed_status',['eq' => 0]);
          return $claims;
     }

     public function getRefundsCollection(){
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $refunds = $objectManager->create('\Commercers\Workshop\Model\ResourceModel\WorkshopRefunds\CollectionFactory')->create()
                    ->addFieldToFilter('payed_status',['eq' => 0]);
          return $refunds;
     }

     public function getStatusProduct($id){
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $product = $objectManager->create('Magento\Catalog\Model\ProductRepository')->getById($id);
          return $product->getStatus();
          // print_r($product->getStatus());exit;
     }

     public function getApprovalIds(){
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $attribute = $objectManager->create('\Magento\Eav\Model\Config')->getAttribute('catalog_product', 'age_status');
          $options = $attribute->getSource()->getAllOptions();
          return $options;
     }

     public function isShowChangeApproval(){
          return  (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_NEW
               || (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_REOPENED
               || (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_OFFER_CREATED
               || (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_OFFER_CREATED_FREE
          ;
     }

     public function getCurrentId(){
          return $this->getRequest()->getParam('id');
     }

     public function isShowWaitingForWeapon(){
          return $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_OFFER_ACCEPTED;
     }

     public function cancelTask(){
          $checkbox = $this->getParamFromRequest('checkbox');
          $task = $this->getTask();
          // $this->cancel_workshop_task($task, false, true);
          if ($checkbox == 'true'){
               $customerId = $task->getFkCustomerId();
               $customer = Mage::getModel('customer/customer')->load($customerId);
               // $this->sendMailToCustomer('email_template_cancelled', $customer, $task->getId());
           }
     }

     public function isShowAskForWeaponMarkedEarly(){
          return (
                    (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_NEW
                    || (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_REOPENED
                    || (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_OFFER_CREATED
                    || (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_OFFER_CREATED_FREE
               )
               && !$this->getTask()->getAdditionalStatusAskForWeaponMarkedEarly()
               && !$this->getTask()->getAdditionalStatusWeaponReceived()
          ;
     }

     public function isShowCreateClaimRefundFields(){
          return  (int)$this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_FINISHED
               && (bool)$this->getTask()->getAdditionalStatusTaskWillStayInBudget() === false
          ;
     }

     public function isShowWillStayInBudgetRemove(){
          return  $this->isShowWillStayInBudget() &&
               (bool)$this->getTask()->getAdditionalStatusTaskWillStayInBudget() === true
          ;
     }

     public function isShowWillStayInBudget(){
          return  (
                    (int)$this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_OFFER_CREATED
                    || (int)$this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_WAITING_FOR_WEAPON
               )
               && (int)$this->getTask()->getType() !== \Commercers\Workshop\Model\Source\Options\Type::TYPE_NEW
               && (bool)$this->getTask()->getAdditionalStatusWeaponReceived() === true
               && (bool)$this->getTask()->getAdditionalStatusTaskProcessed() === true
          ;
     }

     public function isShowCreateClaimRefundFieldsEarly(){
          return  (int)$this->getTask()->getStatus() !== \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_CANCELED
               && (int)$this->getTask()->getStatus() !== \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_COMPLETED
               && (int)$this->getTask()->getStatus() !== \Commercers\Workshop\Model\Source\Options\Status::STATUS_BILLING_BALANCED
               && (int)$this->getTask()->getStatus() !== \Commercers\Workshop\Model\Source\Options\Status::STATUS_OFFER_CREATED_FREE
               && (int)$this->getTask()->getStatus() !== \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_NEW
               && (int)$this->getTask()->getStatus() !== \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_REOPENED
               && (bool)$this->getTask()->getAdditionalStatusTaskWillStayInBudget() == false
          ;
     }

     public function isShowWeaponReceived(){
          return  (bool) $this->getTask()->getAdditionalStatusWeaponReceived() === false
               && (
                    (int) $this->getTask()->getType() === \Commercers\Workshop\Model\Source\Options\Type::TYPE_REPAIR_AND_TUNING
                    || (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_WAITING_FOR_WEAPON
               )
               && (int) $this->getTask()->getStatus() !== \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_COMPLETED
               && (int) $this->getTask()->getStatus() !== \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_CANCELED
          ;
     }

     public function isShowReopenTask(){
          return  (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_COMPLETED
               || (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_CANCELED
          ;
     }

     public function isShowCancelTask()
     {
          return  (int) $this->getTask()->getStatus() !== \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_COMPLETED
                    && (int) $this->getTask()->getStatus() !== \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_CANCELED 
                    && (int) $this->getTask()->getStatus() !== \Commercers\Workshop\Model\Source\Options\Status::STATUS_BILLING_CLAIM;
     }

     public function getParamFromRequest($name = null){
          if($name !== null){
               return $this->getRequest()->getParam($name, null);
          }
          return $this->getRequest()->getParams();
     }

     public function isShowCreateOfferFields(){
          return (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_NEW
                || (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_REOPENED
        ;
     }

     public function isShowMarkAsProcessed(){
          return  (bool) $this->getTask()->getAdditionalStatusTaskProcessed() === false && (
               (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_EXECUTE
               || (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_OFFER_ACCEPTED
               || (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_WAITING_FOR_PAYMENT
               || (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_OFFER_CREATED_FREE
               ||  (
                    //when waiting for weapon status is active, but weapon was received
                    (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_WAITING_FOR_WEAPON
                    && (bool) $this->getTask()->getAdditionalStatusWeaponReceived() === true
               )
               ||  (
                    //when customer has not yet accepted teh offer but task is already processed
                    (int) $this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_OFFER_CREATED
                    && (bool) $this->getTask()->getAdditionalStatusWeaponReceived() === true
               )
          );
     }

     public function isShowMarkTaskAsFinished(){
          return  (int)$this->getTask()->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_EXECUTE
                && (bool)$this->getTask()->getAdditionalStatusTaskProcessed()
                && (bool)$this->isAutomaticSetTaskFinished()
        ;
     }

     public function isAutomaticSetTaskFinished($store_id = null)
     {
          return $this->getBackendConfigValue('automatic_set_task_finished', $store_id) === '1';
     }

     public function getBackendConfigValue($field, $store_id = null)
     {
          return Mage::getStoreConfig(sefl::SYS_XML_SECTION/sefl::SYS_XML_BACKEND/$field, $store_id);
     }
}