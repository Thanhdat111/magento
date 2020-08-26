<?php

namespace Commercers\Workshop\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;

class SetTaskType extends \Magento\Backend\App\Action 
{
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
          $typeId = $this->getRequest()->getParam('typeId');
          $workshopTask = $this->workshopTask->create()->load($idTask);
          // $types = $this->get_type_values();

          if ($this->is_type_valid($typeId)) {
               $workshopTask->addData([
                    'type' => $typeId,
                    'last_changed' => date('Y-m-d H:i:s')
               ])->save();
          }
          $result = $this->resultJsonFactory->create();
          return $result->setData(['success' => true]);
     }
     
     public function is_type_valid($type) {
          return is_numeric($type) && array_key_exists($type, $this->get_type_values());
      }

     public function get_type_values($with_empty_option = false, $get_translated = false)
     {
          $status = array();
          if ($with_empty_option) {
               $status[''] = '';
          }
          $status[\Commercers\Workshop\Model\Source\Options\Type::TYPE_NEW] = 'type_new';
          $status[\Commercers\Workshop\Model\Source\Options\Type::TYPE_REPAIR_AND_TUNING] = 'type_repair_and_tuning';
          $status[\Commercers\Workshop\Model\Source\Options\Type::TYPE_SPARE_PART_ORDER] = 'type_spare_part_order';
          $status[\Commercers\Workshop\Model\Source\Options\Type::TYPE_SPARE_PART_IMPORT] = 'type_spare_part_import';

          //when translated status shoud be returned -> translate values
          if ($get_translated) {
               $translated_status = array();
               foreach ($status as $key => $value) {
                    $translated_status[$key] = $this->__($value);
               }
               return $translated_status;
          } else {
               return $status;
          }
     }

}