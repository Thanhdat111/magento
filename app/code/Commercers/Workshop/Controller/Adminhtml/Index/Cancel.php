<?php

namespace Commercers\Workshop\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;

class Cancel extends \Magento\Backend\App\Action {

    protected $resultJsonFactory;
    protected $workshopTask;
    protected $workshopReferences;
    protected $config;
    protected $customerFactory;
    protected $productFactory;
    protected $mail;
    protected $order;

    public function __construct(
          Context  $context,
          \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
          \Commercers\Workshop\Model\WorkshopTaskFactory $workshopTask,
          \Commercers\Workshop\Model\WorkshopReferences $workshopReferences,
          \Commercers\Workshop\Helper\Config $config,
          \Commercers\Workshop\Helper\Email $mail,
          \Magento\Sales\Model\Order $order,
          \Magento\Customer\Model\CustomerFactory $customerFactory,
          \Magento\Catalog\Model\ProductFactory $productFactory
     ) {
          $this->productFactory = $productFactory;
          $this->customerFactory = $customerFactory;
          $this->config = $config;
          $this->mail = $mail;
          $this->order = $order;
          $this->workshopTask = $workshopTask;
          $this->workshopReferences = $workshopReferences;
          $this->resultJsonFactory = $resultJsonFactory;
          parent::__construct($context);
     }


     public function execute() {
          $idTask = $this->getRequest()->getParam('idTask');
          $checkboxCancelling = $this->getRequest()->getParam('checkboxCancelling');
          $workshopTask = $this->workshopTask->create()->load($idTask);
          $customer = $this->customerFactory->create()->load($workshopTask->getFkCustomerId());
          //set cancel order
          // $workshopTask->addData([
          //      'status' => \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_CANCELED,
          //      'offer_price' =>  null,
          //      'additional_status_ask_for_weapon_marked_early' =>  false,
          //      'additional_status_task_processed' =>  false,
          //      'additional_status_task_ready_for_execution' =>  false,
          //      'additional_status_weapon_received' =>  false,
          //      'additional_status_weapon_received_date' =>  null,
          //      'chosen_parcel_service' =>  null,
          //      'last_changed' => date('Y-m-d H:i:s')
          // ])->save();
          // //deactivate product
          // $references = $this->workshopReferences->getCollection()
          //                     ->addFieldToFilter('reference_type', ['eq'=> \Commercers\Workshop\Model\WorkshopReferences::TYPE_PRODUCT_REFERENCE]);
          // // print_r($references->getData());exit;
          // foreach ($references as $reference) {
          //      //try to save products
          //      try {
          //           if((int)$reference->getReferenceType() == \Commercers\Workshop\Model\WorkshopReferences::TYPE_ORDER_REFERENCE){
          //                $product = $this->order->create()->load($reference->getReferenceId());
          //           }
          //           if((int)$reference->getReferenceType() == \Commercers\Workshop\Model\WorkshopReferences::TYPE_PRODUCT_REFERENCE){
          //                $product = $this->productFactory->create()->load($reference->getReferenceId());
          //           }
          //           if ((int)$product->getStatus() !== \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED) {
          //                $product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED);
          //                $product->save();
          //           }
          //      } catch (Exception $e) {
          //          //what to do now?
          //      }
          //  }
          //reset task 
          if($checkboxCancelling == 'on'){
               //send mail
               // $this->mail->sendEmail($this->config->getEmailTemplateId('cancel_email_template'),$customer,$idTask);
          }
          //echo 'updating';exit;
          $result = $this->resultJsonFactory->create();
          return $result->setData(['success' => true]);
     } 

}