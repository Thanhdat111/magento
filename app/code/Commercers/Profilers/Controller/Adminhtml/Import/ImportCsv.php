<?php
namespace Commercers\Profilers\Controller\Adminhtml\Import;
 
use Magento\Framework\Controller\ResultFactory;
 
class ImportCsv extends \Magento\Backend\App\Action
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
    	
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        return $resultPage;
    }
}