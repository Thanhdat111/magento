<?php
/**
 *  Commercers Vietnam
 *  HieuND 
 */
namespace Commercers\Workshop\Block;

class CustomerAccount extends \Magento\Framework\View\Element\Template
{
     protected $resource;
     protected $scopeConfig;
     protected $_productCollectionFactory;
     protected $_productRepositoryFactory;
     protected $helper;
     protected $order;
     protected $workshopChat;
     protected $workshopReference;
     protected $workshopRefund;

	public function __construct(
          \Magento\Framework\View\Element\Template\Context $context,
          \Magento\Framework\App\ResourceConnection $resource,
          \Commercers\Workshop\Model\WorkshopTask $workshopTask,
          \Commercers\Workshop\Model\WorkshopChat $workshopChat,
          \Magento\Sales\Model\Order $order,
          \Commercers\Workshop\Model\WorkshopReferences $workshopReference,
          \Commercers\Workshop\Model\WorkshopRefunds $workshopRefund,
          \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
          \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
          \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
          \Commercers\Workshop\Helper\Data $helper
     ){
          $this->workshopRefund = $workshopRefund;
          $this->workshopTask = $workshopTask;
          $this->workshopChat = $workshopChat;
          $this->workshopReference = $workshopReference;
          $this->resource = $resource;
          $this->order = $order;
          $this->helper = $helper;
          $this->_productCollectionFactory = $productCollectionFactory; 
          $this->_productRepositoryFactory = $productRepositoryFactory;
          $this->scopeConfig = $scopeConfig;
          parent::__construct($context);
          $this->setTemplate('Commercers_Workshop::sidebar/custom_account.phtml');
     }

     public function getTitleConfig(){
          return 'Workshop';
     }

     public function getIdCustomer(){
          return $this->helper->getCurrentCustomer()->getId();
     }

     public function getTasksToCustomer($customerId){
          $catalogProduct = $this->helper->getResource()->getTableName('catalog_product_entity_varchar');
          $tasksCollection = $this->workshopTask->getCollection();
          $tasksCollection->addFieldToFilter('fk_customer_id', array('eq' => $customerId))
                    ->addFieldToSelect('fk_product_id')
                    ->addFieldToSelect('type')
                    ->addFieldToSelect('weapon_description')
                    ->addFieldToSelect('status')
                    ->addFieldToSelect('pk_entity_id');
                    // print_r($tasksCollection->getData());
          // //join product table
          $tasksCollection->getSelect()
                    ->joinLeft($catalogProduct . ' AS cp',
                         "cp.entity_id = main_table.fk_product_id AND cp.attribute_id = 71",
                         array('productname' => 'value'));
          $tasksCollection->getSelect()->group('pk_entity_id');
          $tasksCollection->setOrder('pk_entity_id', 'DESC');

          $task = clone $tasksCollection;
          $count = $task ->count();
          $pagemax = ceil($count/10);

          $page = $this->getRequest()->getParam('page'); 

          if ($page == NULL){
               $page = 1;
               $tasksCollection-> getSelect()->limit(10);
          }else{
               $tasksCollection-> getSelect()->limit(10,($page*10-10));
          }
          // print_r($tasksCollection->getData());exit;
          $table = array($tasksCollection, $count, $pagemax);

          return $table;
     }
     
     public function getStatus(){
          return $this->helper->getStatus();
     }

     public function getType(){
          return $this->helper->getType();
     }

     public function getProduct(){
          return $this->helper->getProduct();
     }

     public function getImageProduct(){
          return $this->helper->getImageProduct();
     }

     public function getOrder(){
          return $this->order;
     }

     public function getParamFromRequest($name = null)
     {
         if ($name !== null) {
             return $this->getRequest()->getParam($name, null);
         }
         return $this->getRequest()->getParams();
     }

     public function getWorkshopTask(){
          return $this->workshopTask;
     }

     public function checkCustomerToTaskId($taskId, $customerId = null)
     {
          if ($customerId == NULL){
               $customerId = $this->helper->getCurrentCustomer()->getId();
          }
          $taskModel = $this->workshopTask->getCollection();
          $taskModel->addFieldToFilter('fk_customer_id', $customerId);
          foreach ($taskModel as $task){
               $tasksCustomer[] = $task->getId(); 
          }
          //check if taskId from url is in task array of the customer
          if (!(in_array($taskId, $tasksCustomer))){
               $url = $this->getUrl('workshop/front/redirectWrongCustomer');
               //$this->getFrontController()->getResponse()->setRedirect($url);
               return false;
          }
     }

     public function hasOrderBeenSend($taskId)
     {
          $reference = $this->workshopReference->getCollection();
          $reference->addFieldToFilter('fk_workshop_task_id', array('eq' => $taskId))
                    ->addFieldToFilter('reference_type', array('eq' => '1'));
          
          if (count($reference) > 0) {
               $orderIds = array();
               $orderModel = $this->order;
               foreach ($reference as $corOrder) {
                    $orderId = $corOrder->getReferenceId();
                    $order = $orderModel->load($orderId);
                    if ($order->getStatus() == 'complete') {
                         array_push($orderIds, $orderId);
                    } else {
                         array_push($orderIds, null);
                    }
               }
               return $orderIds;
          } else {
               return null;
          }
     }

     public function getStatusToTask($taskId)
     {
          //load workshop
          $workshoptask = $this->workshopTask->load($taskId);
          $status = $workshoptask->getStatus();
          return $status;
     }

     public function getTaskTypeToTask($taskId){
          //load workshop
          $task = $this->workshopTask->load($taskId);
          $taskType = $this->getStatus()[$task->getStatus()]['label'];
          return $taskType;
     }

     public function is_show_fe_waiting_for_weapon_block($task)
     {
          return  (
                    (int) $task->getStatus() == \Commercers\Workshop\Model\Source\Options\Status::STATUS_WAITING_FOR_WEAPON
                    || $task->getAdditionalStatusAskForWeaponMarkedEarly()
               )
               && $task->getAdditionalStatusWeaponReceived() === '0'
          ;
     }

     public function isShowCancelTask($task){
          return $this->helper->isShowCancelTask($task);
     }

     public function getClaimsToTask($task,$id){
          return $this->helper->getClaimsToTask($task,$id);
     }

     public function getRefundsToTask($taskId){
          $refunds = $this->workshopRefund->getCollection();
          $refunds->addFieldToFilter('fk_workshop_task_id', array('eq'=> $taskId));
          $refunds->addFieldToFilter('payed_status', array('eq' => 0));
          return $refunds;
     }

     public function getCreatedProductForClaim($claim)
     {
          $comment = $claim->getComment();
          preg_match('/[0-9]+/', $comment, $productId);
          return $this->product->load($productId[0]);
     }

     public function getChatsToTask($taskId)
     {
          // $sort_order = $this->helper->get_default_chat_entries_sort_order(); //tam thoi set = true
          $sort_order = true;
          $chat_collection = $this->workshopChat->getCollection();
          $chat_collection->addFieldToFilter('fk_workshop_task_id', array('eq' => $taskId))
                    ->addFieldToSelect('message')
                    ->addFieldToSelect('created_at')
                    ->addFieldToSelect('fk_customer_id');
          if ($sort_order){
               $chat_collection->setOrder('created_at', 'ASC');
          }
          else{
               $chat_collection->setOrder('created_at', 'DESC');
          }
          return $chat_collection;
     }
}