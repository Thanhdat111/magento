<?php

namespace Commercers\Workshop\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;

class ClaimInput extends \Magento\Backend\App\Action {

    protected $resultJsonFactory;
    protected $workshopTask;
    protected $workshopClaims;
    protected $workshopReference;
    protected $config;
    protected $customerFactory;
    protected $mail;
    protected $productFactory;
    protected $product;
    protected $productRepository;
    protected $productCopier;

    public function __construct(
          Context  $context,
          \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
          \Commercers\Workshop\Model\WorkshopTaskFactory $workshopTask,
          \Commercers\Workshop\Model\WorkshopClaimsFactory $workshopClaims,
          \Commercers\Workshop\Model\WorkshopReferencesFactory $workshopReference,
          \Commercers\Workshop\Helper\Config $config,
          \Commercers\Workshop\Helper\Email $mail,
          \Magento\Customer\Model\CustomerFactory $customerFactory,
          \Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory,
          \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
          \Magento\Catalog\Model\ProductFactory $product,
          \Magento\Catalog\Model\Product\Copier $productCopier
     ) {
          $this->productCopier = $productCopier;
          $this->product = $product;
          $this->productFactory = $productFactory;
          $this->productRepository = $productRepository;
          $this->customerFactory = $customerFactory;
          $this->config = $config;
          $this->mail = $mail;
          $this->workshopReference = $workshopReference;
          $this->workshopTask = $workshopTask;
          $this->workshopClaims = $workshopClaims;
          $this->resultJsonFactory = $resultJsonFactory;
          parent::__construct($context);
     }


     public function execute() {
          $idTask = $this->getRequest()->getParam('idTask');
          $price = $this->getRequest()->getParam('price');
          $noStatusUpdate = $this->getRequest()->getParam('noStatusUpdate');
          $price = (float) str_replace(',', '.', $price);
          if($price < 0){
               return false;
          }
          //create a product 
          // $product = $this->createVirtualProduct($idTask, $price);
          //create new claim
          $this->createNewClaim($idTask, $price, 'Connected Product ID: product-id');
          //create reference
          $this->createNewReference($idTask);
          // set task to new status
          if(!$noStatusUpdate){
               $task = $this->workshopTask->create()->load($idTask);
               $task->addDatat([
                    'status' => \Commercers\Workshop\Model\Source\Options\Status::STATUS_BILLING_CLAIM,
                    'last_changed' => date('Y-m-d H:i:s'),
               ])->save();
          }
          //send mail to customer 
          $workshopTask = $this->workshopTask->create()->load($idTask);
          $customer = $this->customerFactory->create()->load($workshopTask->getFkCustomerId());
          $sendMail = $this->config->isSendMailOnBillingCreatedClaim();
          if($sendMail === true){
               // $this->mail->sendEmail($this->config->getEmailTemplateId('email_template_subscription'),$customer,$idTask);
          }
          // echo 'updating';exit;
          $result = $this->resultJsonFactory->create();
          return $result->setData(['success' => true]);
     }

     public function createNewReference($idTask){
          $reference = $this->workshopReference->create();
          $reference->addData([
               'fk_workshop_task_id' => $idTask,
               'reference_type' => \Commercers\Workshop\Model\WorkshopReferences::TYPE_PRODUCT_REFERENCE,
               'reference_id' => 'product-id',
               'created_at' => date('Y-m-d H:i:s'),
               'comment' => 'workshop-task-log-added-reference-product-product-id'
          ])->save();
     }

     public function createNewClaim($idTask, $price, $comment){
          $claim = $this->workshopClaims->create();
          $claim->addData([
               'fk_workshop_task_id' => $idTask,
               'amount' => $price,
               'payed_status' => false,
               'comment' => $comment,
               'created_at' => date('Y-m-d H:i:s'),
               'last_changed' => date('Y-m-d H:i:s')
          ])->save();
     }
     
     public function createVirtualProduct($idTask, $price){
          // $masterProductId = $this->config->getVirtualProductId();
          //example
          $masterProductId = 114741;
          //process
          $productIdSearch = null;
          $count = 0;
          while($productIdSearch === null){
               $count++;
               $wannabeSku = 'wsc_' . $idTask . '_v_' . $count;
               try {
                    $productIdSearch = $this->productRepository->get($wannabeSku)->getId();
               } catch (\Throwable $th) {
                    $productIdSearch = null;
               }
          }
          $wsData = array(
               'sku' => $wannabeSku,
               'name' => 'WS-Nachforderung #' . $idTask . '-' . $count,
               'description' => 'Nachforderung zu Ihrem Werkstattauftrag, vom ' . date('d.m.Y'),
               'short_description' => 'Nachforderung Werkstattauftrag',
               'price' => $price,
          );
          $sku = $wsData['sku'];
          $workshopTask = $this->workshopTask->create()->load($idTask);
          if(!$product = $this->product->create()->load($masterProductId)){
               return null;
          }
          try {
               // $clone = $product->setIsDuplicate(true);
               $clone = $this->productCopier->copy($product);
               $clone->setSku($sku);
               $clone->setName($wsData['name']);
               $clone->setBegadiApproval($workshopTask->getApprovalId());
               $clone->setDescription($wsData['description']);
               $clone->setShortDescription($wsData['short_description']);
               $clone->setWebsiteIds([1]);
               $clone->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL);
               $clone->setVisibility(2);
               $clone->setPrice($wsData['price']);
               $clone->setAttributeSetId(4); // Attribute set for products
               $clone->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
               // $clone->setUrlKey($clone->formatUrlKey($sku));
               $clone->setGroupPrice(array());
               $clone->setGroupPriceChanged(0);
               $clone->setStockData(
                                   array(
                                        'use_config_manage_stock' => 0,
                                        'backorders' => 0,
                                        'is_in_stock' => 1,
                                        'qty' => 1
                                   )
                              );
               $clone->getResource()->save($clone);
          } catch (Exception $e){
               echo $e->getMessage();
          }
          return $clone;
     }

}