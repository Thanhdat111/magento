<?php

namespace Commercers\Workshop\Block\Adminhtml\Workshop\General;
 
class Information extends \Magento\Backend\Block\Template
{
     const STATUS_TASK_NEW = 1;

     private $_requestedWorkshopTasks = array();

     protected $_template = 'Commercers_Workshop::general/information.phtml';

     public function getTask(){
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $task = $objectManager->create('\Commercers\Workshop\Model\WorkshopTask');
          $idTask = $this->getRequest()->getParam('id');
          return $task->load($idTask);
     }

     public function getUrlEditCustomer(){
          return $this->getUrl("customer/index/edit",array('id' => $this->getTask()->getFkCustomerId()));
     }

     public function getFirstname(){
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $customers = $objectManager->create('\Magento\Customer\Model\Customer');
          $customer = $customers->load($this->getTask()->getFkCustomerId());
          return $customer->getFirstname();
     }

     public function getLastname(){
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $customers = $objectManager->create('\Magento\Customer\Model\Customer');
          $customer = $customers->load($this->getTask()->getFkCustomerId());
          return $customer->getLastname();
     }

     public function getType(){
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $type = $objectManager->create('\Commercers\Workshop\Model\Source\Options\Type');
          $type = $type->toOptionArray();
          return $type[$this->getTask()->getType()];
     }

     public function getStatus(){
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $status = $objectManager->create('\Commercers\Workshop\Model\Source\Options\Status');
          $status = $status->toOptionArray();
          return $status[$this->getTask()->getStatus()];
     }

     public function getOrderNumber(){
          $orderIncrementId = $this->getTask()->getOrderIncrementId();
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $order = $objectManager->create('\Magento\Sales\Model\Order')->loadByIncrementId($orderIncrementId);
          if(!is_numeric($order->getId())){
               $orderId = null;
               $orderLink = null;
          }else{
               $orderId = $order->getId();
               $orderLink = $this->getUrl("sales/order/view",array('order_id' => $orderId));
          }
          if ($orderLink !== null) {
               $orderIdText = '<a href = "' . $orderLink . '">#'.$orderIncrementId.'</a>';
          } else {
               $orderIdText = 'RNr. '.$orderIncrementId;
          }
          return $orderIdText;
     }

     public function getChosenParcelService(){
          $chosenParcelService = '<p id="sales_order_grid_table">'
          . '<span'
          . ' title="' . strtoupper($this->getTask()->getChosenParcelService()) . '"'
          . ' class="' . strtolower($this->getTask()->getChosenParcelService()) . '">'
          . '<span>' . strtoupper($this->getTask()->getChosenParcelService()) . '</span>'
          . '</span>'
          . '</p>';
          return $chosenParcelService;
     }

     public function getProductById($idProduct){
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $product = $objectManager->create('Magento\Catalog\Model\Product')->load($idProduct);
          return $product;
     }

     public function getTypeButton(){
          $task = $this->getWorkshopTaskForId($this->getRequest()->getParam('id'));
          // print_r($task->getData());exit;
          $typeArray = $this->getTypeValues(false, true);
          $typeButtonHtml = '';
          if($this->getTask()->getType() == \Commercers\Workshop\Model\Source\Options\Type::TYPE_NEW){
               if ($this->isSwitchWorkshopTaskTypePossible($task)) {
                    $typeButtonHtml .= $this->_getTypeSwitchButtonHtml(\Commercers\Workshop\Model\Source\Options\Type::TYPE_REPAIR_AND_TUNING, $typeArray, $task);
                    $typeButtonHtml .= '<br><br>' . $this->_getTypeSwitchButtonHtml(\Commercers\Workshop\Model\Source\Options\Type::TYPE_SPARE_PART_ORDER, $typeArray, $task);
                    $typeButtonHtml .= '<br><br>' . $this->_getTypeSwitchButtonHtml(\Commercers\Workshop\Model\Source\Options\Type::TYPE_SPARE_PART_IMPORT, $typeArray, $task);
               }
          }
          if($this->getTask()->getType() == \Commercers\Workshop\Model\Source\Options\Type::TYPE_REPAIR_AND_TUNING){
               if ($this->isSwitchWorkshopTaskTypePossible($task)) {
                    $typeButtonHtml .= $this->_getTypeSwitchButtonHtml(\Commercers\Workshop\Model\Source\Options\Type::TYPE_SPARE_PART_ORDER, $typeArray, $task);
                    $typeButtonHtml .= '<br><br>' . $this->_getTypeSwitchButtonHtml(\Commercers\Workshop\Model\Source\Options\Type::TYPE_SPARE_PART_IMPORT, $typeArray, $task);
               }
          }
          if($this->getTask()->getType() == \Commercers\Workshop\Model\Source\Options\Type::TYPE_SPARE_PART_ORDER){
               if ($this->isSwitchWorkshopTaskTypePossible($task)) {
                    $typeButtonHtml .= $this->_getTypeSwitchButtonHtml(\Commercers\Workshop\Model\Source\Options\Type::TYPE_REPAIR_AND_TUNING, $typeArray, $task);
                    $typeButtonHtml .= '<br><br>' . $this->_getTypeSwitchButtonHtml(\Commercers\Workshop\Model\Source\Options\Type::TYPE_SPARE_PART_IMPORT, $typeArray, $task);
               } else {
                    $typeButtonHtml = $this->_getTypeSwitchButtonHtml(\Commercers\Workshop\Model\Source\Options\Type::TYPE_SPARE_PART_IMPORT, $typeArray, $task);
               }
          }
          return $typeButtonHtml;
     }

     public function getTypeValues($withEmptyOption = false, $getTranslated = false)
     {
          $status = array();
          if ($withEmptyOption) {
               $status[''] = '';
          }
          $status[\Commercers\Workshop\Model\Source\Options\Type::TYPE_NEW] = 'type_new';
          $status[\Commercers\Workshop\Model\Source\Options\Type::TYPE_REPAIR_AND_TUNING] = 'type_repair_and_tuning';
          $status[\Commercers\Workshop\Model\Source\Options\Type::TYPE_SPARE_PART_ORDER] = 'type_spare_part_order';
          $status[\Commercers\Workshop\Model\Source\Options\Type::TYPE_SPARE_PART_IMPORT] = 'type_spare_part_import';

          //when translated status shoud be returned -> translate values
          if ($getTranslated) {
               $translatedStatus = array();
               foreach ($status as $key => $value) {
                    $translatedStatus[$key] = $value;
               }
               return $translatedStatus;
          } else {
               return $status;
          }
     }

     private function _getTypeSwitchButtonHtml($switchToTypeId, $typeArray, $task)
     {
          $typeButtonValue = 'workshop-task-type-switch-to' . $typeArray[$switchToTypeId];
          return $this->_getButtonString(
                    $typeButtonValue,
                    $typeButtonValue,
                    // "setLocation('" . $this->getUrl('*/index/settasktype/task_id/' . $task->getId()) . "type_id/" . $switchToTypeId . "')",
                    // $task->getId(),
                    $switchToTypeId,
                    $typeButtonValue
          );
     }

     public function isSwitchWorkshopTaskTypePossible($task){
          return
               (int) $task->getStatus() === \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_NEW
               && (int)$task->getAdditionalStatusAskForWeaponMarkedEarly() === 0
               && (int)$task->getAdditionalStatusTaskProcessed() === 0
               && (int)$task->getAdditionalStatusTaskReadyForExecution() === 0
               && (int)$task->getAdditionalStatusWeaponReceived() === 0
               && $task->getAdditionalStatusWeaponReceivedDate() === null
               && $task->getOfferPrice() === null
          ;
     }

     // protected function _getButtonString($id, $value, $onclick, $title)
     protected function _getButtonString($id, $value, $typeId,$title)
     {
          return '<input id="'.$id.'"'
                    . ' name="'.$id.'"'
                    . ' value="'.$value.'"'
                    // . ' onclick="'.$onclick.'"'
                    . ' data-typeid="'.$typeId.'"'
                    . ' class="action-default scalable save primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"'
                    . ' title="'.$title.'"'
                    . ' type="button">';
     }

     public function getWorkshopTaskForId($id)
    {
          if (isset($this->_requestedWorkshopTasks[$id]) && $this->_requestedWorkshopTasks[$id] !== null) {
               return $this->_requestedWorkshopTasks[$id];
          }
          //go on and query the requested supplier
          if (is_numeric($id)) {
               $workshop = $this->getTask((int) $id);
               if (
                    $id !== null &&
                    $workshop->getId() > 0
               ) {
                    //workshp found
                    $this->_requestedWorkshopTasks[$id] = $workshop;
                    return $workshop;
               }
          }
          return null;
     }
}