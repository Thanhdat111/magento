<?php

namespace Commercers\Workshop\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;

class EditRefundInputTitle extends \Magento\Backend\App\Action {

    protected $resultJsonFactory;
    protected $workshopRefund;

    public function __construct(
          Context  $context,
          \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
          \Commercers\Workshop\Model\WorkshopRefundsFactory $workshopRefund
     ) {
          $this->workshopRefund = $workshopRefund;
          $this->resultJsonFactory = $resultJsonFactory;
          parent::__construct($context);
     }


     public function execute() {
          $idTask = $this->getRequest()->getParam('idTask');
          $refundId = $this->getRequest()->getParam('refundId');
          // $productId = $this->getRequest()->getParam('productId');
          $toPrice = $this->getRequest()->getParam('price');
          $toPrice = (float) str_replace(',', '.', $toPrice);
          if($refundId === null || !is_numeric($refundId) || !is_numeric($toPrice) || $toPrice < 0){
               $result = $this->resultJsonFactory->create();
               return $result->setData(['success' => true]);
          }
          $refund = $this->workshopRefund->create()->load($refundId);
          if(is_numeric($refundId)){
               // $fromPrice = $refund->getAmount();
               $refund->setAmount($toPrice);
               $refund->save();
          }
          // echo "updating";exit;
          $result = $this->resultJsonFactory->create();
          return $result->setData(['success' => true]);
     } 

}