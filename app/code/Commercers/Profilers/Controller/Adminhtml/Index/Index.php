<?php
namespace Commercers\Profilers\Controller\Adminhtml\Index;
 
use Magento\Framework\Controller\ResultFactory;
 
class Index extends \Magento\Backend\App\Action
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        //echo $this->getRequest()->getFullActionName();exit;
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $currentProfilerId = $this->_getSession()->setProfilerId(null);
        return $resultPage;
    }
}