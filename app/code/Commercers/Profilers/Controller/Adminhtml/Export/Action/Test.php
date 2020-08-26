<?php

namespace Commercers\Profilers\Controller\Adminhtml\Export\Action;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Commercers\Profilers\Service\DataSource\Factory as DataSourceFactory;

class Test extends \Magento\Backend\App\Action {

    protected $_profilerFactory;
    
    public function __construct(
            Action\Context $context,
             \Commercers\Profilers\Model\ProfilersFactory $profilerFactory,
            \Magento\Framework\View\Result\PageFactory $resultPageFactory,
            DataSourceFactory $dataSourceFactory
    ) {
        $this->_profilerFactory = $profilerFactory;
        $this->_resultPageFactory = $resultPageFactory;
        $this->dataSourceFactory = $dataSourceFactory;
        parent::__construct($context);
    }

    public function execute() {
        $dataInTextarea = $this->getRequest()->getParam('dataInTextarea');

        $profilerId = $this->getRequest()->getParam('profiler_id');
        $id = $this->getRequest()->getParam('id');

        $profiler = $this->_profilerFactory->create()->load($profilerId);

        $dataSource = $this->dataSourceFactory->get($profiler->getDataSource());
        $process = \Magento\Framework\App\ObjectManager::getInstance()->get("\Commercers\Profilers\Service\Profiler\Process\Export");
        $data = $dataSource->getItemById($id, $profiler);
        $resultPage = $this->_resultPageFactory->create();
        if ($data == false) {
            $output = false;
        } else {
            $output = $process->getOutput($dataInTextarea, $data);
        }

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_RAW);

        return $resultJson->setContents($output);
    }

}
