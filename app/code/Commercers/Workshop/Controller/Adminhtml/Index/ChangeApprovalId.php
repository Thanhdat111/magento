<?php

namespace Commercers\Workshop\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;

class ChangeApprovalId extends \Magento\Backend\App\Action {

    protected $resultJsonFactory;
    protected $workshopTask;

    public function __construct(
          Context  $context,
          \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
          \Commercers\Workshop\Model\WorkshopTaskFactory $workshopTask
     ) {
          $this->workshopTask = $workshopTask;
          $this->resultJsonFactory = $resultJsonFactory;
          parent::__construct($context);
     }


     public function execute() {
          $approvalId = $this->getRequest()->getParam('approvalId');
          $idTask = $this->getRequest()->getParam('idTask');
          $workshopTask = $this->workshopTask->create()->load($idTask);
          $workshopTask->addData([
               'approval_id' => $approvalId
          ])->save();
          /** @var \Magento\Framework\Controller\Result\Json $result */
          $result = $this->resultJsonFactory->create();
          return $result->setData(['success' => true]);
     } 

}