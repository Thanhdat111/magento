<?php

namespace Commercers\Profilers\Controller\Adminhtml\Export\Action\Get;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class DataProfiler extends \Magento\Backend\App\Action {

    protected $_profilerFactory;
    
    public function __construct(
            Action\Context $context,
             \Commercers\Profilers\Model\ProfilersFactory $profilerFactory
    ) {
        $this->_profilerFactory = $profilerFactory;
        parent::__construct($context);
    }

    public function execute() { 
        $profilerId = $this->getRequest()->getParam('profiler_id');
        $profiler = $this->_profilerFactory->create()->load($profilerId);
        
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        return $resultJson->setData($profiler->getData());
    }
}
