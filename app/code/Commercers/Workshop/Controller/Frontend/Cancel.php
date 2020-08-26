<?php
namespace Commercers\Workshop\Controller\Frontend;

use Magento\Framework\Controller\ResultFactory;

class Cancel extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
	protected $customerSession;
	protected $workshopTask;
	protected $helper;

	public function __construct(
		\Magento\Backend\App\Action\Context $context,
          \Magento\Customer\Model\Session $customerSession,
          \Commercers\Workshop\Helper\Data $helper,
          \Commercers\Workshop\Model\WorkshopTaskFactory $workshopTask,
		\Magento\Framework\View\Result\PageFactory $pageFactory)
	{
		$this->helper = $helper;
		$this->workshopTask = $workshopTask;
		$this->customerSession = $customerSession;
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	public function execute()
	{
		if(!$this->customerSession->isLoggedIn()){
			$this->_redirect('customer/account/');
		}
		$taskId = $this->getRequest()->getPost('task_id');
          //get the task data
          $task = $this->helper->getWorkshopTaskForId($taskId);
          //from here on, we can assume that $task is available
          $customer_allowed = $this->helper->checkCustomerToTaskId($taskId);
          if ($customer_allowed === false){
          //dont send message
               return false;
          }
          //last parameter: is_called_from_backend = false (for logging user admin)
          //$$this->helper->cancel_workshop_task($task, false, true, null, false);
          $task->setStatus(\Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_CANCELED);
          $task->save();
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
		$resultRedirect->setPath('workshop/frontend/myworkshoptask');
		return $resultRedirect;
	}
}