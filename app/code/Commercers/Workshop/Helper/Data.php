<?php

namespace Commercers\Workshop\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
     private $_requested_workshop_tasks = array();
     
	protected $customerSession;
	protected $customerFactory;
	protected $_storeManager; 
	protected $product; 
	protected $productFactory; 
	protected $workshopTask; 
	protected $workshopReference; 
     protected $workshopChat; 
     protected $orderFactory;
     protected $config;
     protected $resource;
     protected $status;
     protected $type;
     protected $imageHelper;
     protected $workshopClaims;

	public function __construct(
          \Magento\Customer\Model\Session $customerSession,
          \Magento\Customer\Model\CustomerFactory $customerFactory,
          \Magento\Catalog\Model\ProductFactory $productFactory,
          \Magento\Catalog\Model\Product $product,
          \Commercers\Workshop\Helper\Config $config,
          \Magento\Sales\Api\Data\OrderInterfaceFactory $orderFactory,
          \Magento\Store\Model\StoreManagerInterface $storeManager,
		\Commercers\Workshop\Model\WorkshopClaims $workshopClaims,
		\Commercers\Workshop\Model\WorkshopTaskFactory $workshopTask,
		\Commercers\Workshop\Model\WorkshopReferencesFactory $workshopReference,
          \Commercers\Workshop\Model\WorkshopChatFactory $workshopChat,
          \Magento\Framework\App\ResourceConnection $resource,
          \Commercers\Workshop\Model\Source\Options\Status $status,
          \Commercers\Workshop\Model\Source\Options\Type $type,
          \Magento\Catalog\Helper\Image $imageHelper
	){
          $this->orderFactory = $orderFactory;
          $this->imageHelper = $imageHelper;
          $this->status = $status;
          $this->type = $type;
          $this->resource = $resource;
          $this->productFactory = $productFactory; 
          $this->product = $product; 
          $this->config = $config; 
		$this->workshopTask = $workshopTask;
		$this->workshopClaims = $workshopClaims;
		$this->workshopReference = $workshopReference;
		$this->workshopChat = $workshopChat;
		$this->_storeManager = $storeManager; 
		$this->customerSession = $customerSession;
		$this->customerFactory = $customerFactory;
     }
     
     public function createWorkshopTask(
          $type,
          $customer,
          $message,
          $storeId = null,
          $approvalId = null,
          $productId = null,
          $adminId = null,
          $status = null,
          $orderIncrementId = null,
          $weaponLimitation = null,
          $weaponManufacturer = null,
          $weaponDescription = null,
          $chosenCarrier = null
     ) {
          //check the customer parameter, if its a nzumeric value, then try load the customer
          if (is_numeric($customer)) {
               $customer = $this->customerFactory->create()->load($customer);
          }
          //create task and fill in data
          $task = $this->workshopTask->create();
          $task->setFkCustomerId($customer->getId());
          $task->setType($type);
          if ($storeId === null) {
               //when no store given, set DE as default
               $storeId = $this->_storeManager->getStore()->getId();
          }
          $task->setFkStoreId($storeId);
          if ($approvalId === null) {
               $approvalId = $this->config->getDefaultApprovalId();
          }
          $task->setApprovalID($approvalId);
          //just cast to int if given
          if (is_numeric($productId)) {
               $productId = (int) $productId;
               $productName = $this->productFactory->create()->load($productId)->getName();
          } else {
               //when the given id is not numeric - use null
               $productId = null;
               $productName = null;
          }
          $task->setFkProductId($productId);
          $task->setReferenceProductName($productName);
          //just cast to int if given (this will only be given, when task was created in admin panel)
          if (is_numeric($adminId)) {
               $adminId = (int) $adminId;
          } else {
               //when the given id is not numeric - use null
               $adminId = null;
          }
          $task->setFkAdminId($adminId);
          //check the status and if not valid, set to "new"
          // if (!$this->is_status_valid($type)) {
          //      $status = Fairnet_Workshop_Helper_Status::STATUS_TASK_NEW;
          // }
          $task->setStatus($status);
          //check if order id exists, if not -> set null, becasue of wrong data
          $order = $this->orderFactory->create()->load($orderIncrementId, 'increment_id');
          if (!is_numeric($order->getId())) {
               $order_id = null;
          } else {
               $order_id = $order->getId();
          }
          $task->setOrderIncrementId($orderIncrementId);
          //check the user input, if not given, set to false (means: user didnt want a Joule limit FSK 14-alike)
          if (!is_bool($weaponLimitation)) {
               $weaponLimitation = false;
          }
          $task->setWeaponLimitation($weaponLimitation);
          //check the user input, if no string given, set to null
          if (!is_string($weaponManufacturer) || strlen($weaponManufacturer) <= 0) {
               $weaponManufacturer = null;
          }
          $task->setWeaponManufacturer($weaponManufacturer);
          //check the user input, if no string given, set to null
          if (!is_string($weaponDescription) || strlen($weaponDescription) <= 0) {
               $weaponDescription = null;
          }
          $task->setWeaponDescription($weaponDescription);
          //check the user input, if incorrect values are given, set to
          //null (currently only 'dpd' and 'dhl' are allowed)
          if ($chosenCarrier !== 'dpd' && $chosenCarrier !== 'dhl') {
               $chosenCarrier = null;
          }
          $task->setChosenParcelService($chosenCarrier);
          //set other default values
          $task->setAdditionalStatusWeaponReceived(0);
          $task->setAdditionalStatusWeaponDate(null);
          $task->setAdditionalStatusReadyForExecution(0);
          $task->setAdditionalStatusTaskProcessed(0);
          $task->setCreatedAt(date("Y-m-d H:i:s"));
          $task->setOfferPrice(null);
          //now lets save the task
          $task->save();
          //create the first chat entry for this task
          if ($adminId === null) {
               //its a task created by customer (so the first message is from a customer)
               $chat_entry = $this->createChatEntry($task->getId(), $customer->getId(), null, $message);
          } else {
               //its a task created by an admin (so the first message is from an admin)
               $chat_entry = $this->createChatEntry($task->getId(), null, $adminId, $message);
          }
          //reference order to task in reference table
          if (is_numeric($order->getId())){
               $references = $this->workshopReference->create();
               $references->setFkWorkshopTaskId($task->getId());
               $references->setReferenceType(1);
               $references->setReferenceId($order_id);
               $references->setCreatedAt(date('Y-m-d H:i:s'));
               $references->setComment('initial Order');
               $references->save();
          }
          //return the task      
          return $task;
     }

     public function createChatEntry($taskId, $customerId, $adminId, $message) {
          //get current date in mysql format
          $date = date('Y-m-d H:i:s');
           //create chat and fill in data
          $chat = $this->workshopChat->create();
          $chat->setFkWorkshopTaskId($taskId);
          $chat->setFkCustomerId($customerId);
          $chat->setFkAdminId($adminId);
          $chat->setMessage($message);
          $chat->setCreatedAt($date);
          $chat->save();
          return $chat;
     }

     public function getProduct(){
          return $this->product;
     }

     public function getCurrentCustomer(){
          return $this->customerSession->getCustomer();
     }

     public function getResource(){
          return $this->resource;
     }

     public function getStatus(){
          return $this->status->toOptionArray();
     }

     public function getType(){
          return $this->type->toOptionArray();
     }

     public function getImageProduct(){
          return $this->imageHelper;
     }

     public function isShowCancelTask($task)
     {
          //admin should be able to mark as cancelled (if not yet marked as such)
          return  (int) $task->getStatus() !== \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_COMPLETED
                    && (int) $task->getStatus() !== \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_CANCELED 
                    && (int) $task->getStatus() !== \Commercers\Workshop\Model\Source\Options\Status::STATUS_BILLING_CLAIM;
     }

     public function getClaimsToTask($task,$id){
          $collection = $this->getClaims($this->workshopClaims,$id,true);
          $collection->addFieldToFilter('payed_status', array('eq' => 0));
          return $collection;
     }

     public function getClaims($model,$id,$as_collection = false){
          if (!is_numeric($id)) {
               return null;
          }
          $collection = $model->getCollection();
          $collection->addFieldToFilter('fk_workshop_task_id', $id);
          if ($as_collection) {
               return $collection;
          }
          return $collection->getItems();
     }

     public function getWorkshopTaskForId($id, $store_id = null)
     {
          if (isset($this->_requested_workshop_tasks[$id]) && $this->_requested_workshop_tasks[$id] !== null) {
               return $this->_requested_workshop_tasks[$id];
          }
          //go on and query the requested supplier
          if (is_numeric($id)) {
               //grab the store
               $store = null;
               if ($store_id !== null) {
                    $store = $this->_storeManager->getStore($store_id);
               }
               //if still no store -> grab default store
               if ($store === null) {
                    $store = $this->_storeManager->getStore();
               }
               $workshop = $this->workshopTask->create()
                         ->load((int) $id);
               if (
                         $id !== null &&
                         $workshop->getId() > 0
               ) {
                    //workshp found
                    $this->_requested_workshop_tasks[$id] = $workshop;
                    return $workshop;
               }
          }
          //still no workshop found -> return null
          return null;
     }

     public function checkCustomerToTaskId($task_id, $customer_id = null)
     {
          if ($customer_id == NULL){
          $customer_id = $this->customerSession->getCustomer()->getId();
          }
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $workshopTask  = $objectManager->get('\Commercers\Workshop\Model\WorkshopTask');
          $task_model = $workshopTask->getCollection();
          $task_model->addFieldToFilter('fk_customer_id', $customer_id);
          foreach ($task_model as $task){
               $tasks_customer[] = $task->getId(); 
          }
          //check if task_id from url is in task array of the customer
          if (!(in_array($task_id, $tasks_customer))){
               //$url = $this->getUrl('workshop/front/redirectWrongCustomer');
               //Mage::app()->getFrontController()->getResponse()->setRedirect($url);
               return false;
          }
     }
}