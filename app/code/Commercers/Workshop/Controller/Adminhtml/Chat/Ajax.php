<?php
/**
 *  Commercers Vietnam
 *  Nguyen Duc Hieu 
 */
namespace Commercers\Workshop\Controller\Adminhtml\Chat;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Ajax extends \Magento\Backend\App\Action
{   
    protected $authSession;

    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Commercers\Workshop\Model\WorkshopChatFactory $workshopChat
    ) {
        $this->workshopChat = $workshopChat;
        $this->authSession = $authSession;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }


    public function execute()
    {
        if ($this->getRequest()->isAjax()) 
        {
            $message = $this->getRequest()->getParam('message');
            $idTask = $this->getRequest()->getParam('idTask');
            $currentAdminId = $this->authSession->getUser()->getId();
            $workshopChat = $this->workshopChat->create();
            $workshopChat->addData([
                'fk_workshop_task_id' => $idTask,
                'fk_admin_id' => $currentAdminId,
                'message' => $message,
                'created_at' => date('Y-m-d H:i:s')
            ])->save();
            $result = $this->resultJsonFactory->create();
            return $result->setData(['success' => true]);
        }
    }
}