<?php

namespace Commercers\Workshop\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;

class MarkTaskProcessed extends \Magento\Backend\App\Action {

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
          $idTask = $this->getRequest()->getParam('idTask');
          $workshopTask = $this->workshopTask->create()->load($idTask);
          $workshopTask->addData([
               'additional_status_task_processed' => 0,
               'last_changed' => date('Y-m-d H:i:s')
          ])->save();
          $result = $this->resultJsonFactory->create();
          return $result->setData(['success' => true]);
     } 

}