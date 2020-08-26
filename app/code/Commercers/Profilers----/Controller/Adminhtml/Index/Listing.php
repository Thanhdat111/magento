<?php
/**
 *  Commercers Vietnam
 *  Nguyen Duc Hieu 
 */
namespace Commercers\Profilers\Controller\Adminhtml\Index;

class Listing extends \Magento\Framework\App\Action\Action
{
    protected function _isAllowed(){
        return true;
    }

    public function execute(){
        //echo $this->getRequest()->getFullActionName();exit;
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}