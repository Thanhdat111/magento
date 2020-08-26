<?php

namespace Commercers\Profilers\Controller\Adminhtml\Import;

class Save extends \Magento\Backend\App\Action
{ 
    public function execute()
    {
        $this->_redirect('*/index/listing');
        
    }
}
