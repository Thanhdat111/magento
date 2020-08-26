<?php

namespace Commercers\Workshop\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;

class AskForWeaponMarkedearly extends \Magento\Backend\App\Action {

    protected $resultJsonFactory;
    protected $workshopTask;
    protected $config;
    protected $customerFactory;
    protected $mail;

    public function __construct(
          Context  $context,
          \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
          \Commercers\Workshop\Model\WorkshopTaskFactory $workshopTask,
          \Commercers\Workshop\Helper\Config $config,
          \Commercers\Workshop\Helper\Email $mail,
          \Magento\Customer\Model\CustomerFactory $customerFactory
     ) {
          $this->customerFactory = $customerFactory;
          $this->config = $config;
          $this->mail = $mail;
          $this->workshopTask = $workshopTask;
          $this->resultJsonFactory = $resultJsonFactory;
          parent::__construct($context);
     }


     public function execute() {
          $idTask = $this->getRequest()->getParam('idTask');
          if($idTask){
              $workshopTask = $this->workshopTask->create()->load($idTask);
              $customer = $this->customerFactory->create()->load($workshopTask->getFkCustomerId());
              $workshopTask->addData([
                  'additional_status_ask_for_weapon_marked_early' => true,
                  'last_changed' => date('Y-m-d H:i:s')
              ])->save();
              $sendMail = $this->config->isSendMailOnWaitingForWeapon();
              if($sendMail === true){
                  $this->mail->sendEmail($this->config->getEmailTemplateId('email_template_waiting_for_weapon'),$customer,$idTask);
              }
              $result = $this->resultJsonFactory->create();
              return $result->setData(['success' => true]);
          }

     } 

}