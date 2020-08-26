<?php
/**
 *  Commercers Vietnam
 *  Nguyen Duc Hieu 
 */
namespace Commercers\Workshop\Controller\Adminhtml\Note;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Note extends \Magento\Backend\App\Action
{   
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory resultPageFactory
     */
    public function __construct(
	    \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    /**
     * Default customer account page
     *
     * @return void
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Commercers_Workshop::notedashboard');
        $resultPage->addBreadcrumb(__('Workshop'), __('Workshop'));
        $resultPage->getConfig()->getTitle()->prepend(__('Workshop'));

        return $resultPage;
    }
}