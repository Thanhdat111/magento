<?php

namespace Commercers\Workshop\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;

class RefundInput extends \Magento\Backend\App\Action {

    protected $resultJsonFactory;
    protected $workshopTask;
    protected $workshopRefund;

    public function __construct(
          Context  $context,
          \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
          \Commercers\Workshop\Model\WorkshopTaskFactory $workshopTask,
          \Commercers\Workshop\Model\WorkshopRefundsFactory $workshopRefund
     ) {
          $this->workshopTask = $workshopTask;
          $this->workshopRefund = $workshopRefund;
          $this->resultJsonFactory = $resultJsonFactory;
          parent::__construct($context);
     }


     public function execute() {
          $idTask = $this->getRequest()->getParam('idTask');
          $price = $this->getRequest()->getParam('price');
          $noStatusUpdate = (bool)$this->getRequest()->getParam('noStatusUpdate');
          $price = (float) str_replace(',', '.', $price);
          if ($price <= 0) {
               return false;
          }
          $refund = $this->createNewRefund($idTask, $price, "Refund for task_id: " . $idTask);
          if(!$noStatusUpdate){
               $workshopTask = $this->workshopTask->create()->load($idTask);
               $workshopTask->addData([
                    'status' => \Commercers\Workshop\Model\Source\Options\Status::STATUS_BILLING_REFUND,
                    'last_changed' => date('Y-m-d H:i:s')
               ])->save();
          }
          $result = $this->resultJsonFactory->create();
          return $result->setData(['success' => true]);
     } 

     public function createNewRefund($idTask, $price, $comment){
          $claim = $this->workshopRefund->create();
          $claim->setFkWorkshopTaskId($idTask);
          $claim->setAmount($price);
          $claim->setPayedStatus(false);
          $claim->setComment($comment);
          $claim->setCreatedAt(date('Y-m-d H:i:s'));
          $claim->setLastChanged(date('Y-m-d H:i:s'));
          $claim->save();
          return $claim;
     }

}