<?php
/**
 *  Commercers Vietnam
 *  Nguyen Duc Hieu 
 */
namespace Commercers\Workshop\Controller\Frontend;

use Magento\Framework\Controller\ResultFactory;

class Message extends \Magento\Framework\App\Action\Action
{   
    protected $customerSession;

    public function __construct(
	    \Magento\Backend\App\Action\Context $context,
		\Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Commercers\Workshop\Model\WorkshopChatFactory $workshopChat
    ) {
        $this->workshopChat = $workshopChat;
        $this->customerSession = $customerSession;
		$this->_pageFactory = $pageFactory;
        parent::__construct($context);
    }


    public function execute()
    {
        $params = $this->getRequest()->getParams();
        if ($params) 
        {
            $idTask =  $params['task_id'];
            if(!isset($params['file_info'])){
                $params['file_info']  = null;
            }
            $customerId = $this->customerSession->getCustomerId();
            $workshopChat = $this->workshopChat->create();
            $workshopChat->addData([
                'fk_workshop_task_id' => $idTask,
                'fk_customer_id' => $customerId,
                'message' => $params['chat_message'],
                'link_file' => $params['file_info'],
                'created_at' => date('Y-m-d H:i:s')
            ])->save();
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setPath('workshop/frontend/myworkshoptask');
            return $resultRedirect;
        }
    }
}