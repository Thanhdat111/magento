<?php

namespace Commercers\Workshop\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;

class EditClaimInputTitle extends \Magento\Backend\App\Action {

    protected $resultJsonFactory;
    protected $workshopTask;
    protected $workshopClaim;
    protected $product;

    public function __construct(
          Context  $context,
          \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
          \Commercers\Workshop\Model\WorkshopTaskFactory $workshopTask,
          \Commercers\Workshop\Model\WorkshopClaimsFactory $workshopClaim,
          \Magento\Catalog\Model\ProductFactory $product
     ) {
          $this->workshopTask = $workshopTask;
          $this->workshopClaim = $workshopClaim;
          $this->product = $product;
          $this->resultJsonFactory = $resultJsonFactory;
          parent::__construct($context);
     }


     public function execute() {
          $idTask = $this->getRequest()->getParam('idTask');
          $claimId = $this->getRequest()->getParam('claimId');
          $productId = $this->getRequest()->getParam('productId');
          $toPrice = $this->getRequest()->getParam('price');
          $toPrice = (float) str_replace(',', '.', $toPrice);
          $claim = $this->workshopClaim->create()->load($claimId);
          $product = $this->product->create()->load($productId);
          if(is_numeric($claim->getPkEntityId()) && is_numeric($product->getId())){
               // $fromPrice = $product->getPrice();
               $claim->setAmount($toPrice);
               $claim->save();
               $product->setPrice($toPrice);
               $product->save();
          }
          // echo "updating";exit;
          $result = $this->resultJsonFactory->create();
          return $result->setData(['success' => true]);
     } 

}