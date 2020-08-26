<?php

namespace Commercers\Profilers\Controller\Adminhtml\Import;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Commercers\Profilers\Service\DataSource\Import\Factory as DataSourceFactory;

class Test extends \Magento\Backend\App\Action {

    protected $_profilerFactory;
    
    public function __construct(
            Action\Context $context,
             \Commercers\Profilers\Model\ProfilersFactory $profilerFactory,
            \Magento\Framework\View\Result\PageFactory $resultPageFactory,
            DataSourceFactory $dataSourceFactory,
            \Magento\Framework\Filesystem\DirectoryList $dir
    ) {
        $this->_profilerFactory = $profilerFactory;
        $this->_resultPageFactory = $resultPageFactory;
        $this->dataSourceFactory = $dataSourceFactory;
        $this->_dir = $dir;
        parent::__construct($context);
    }

    public function execute() {
        $xsltTemplate = $this->getRequest()->getParam('xslt_template');

        $profilerId = $this->getRequest()->getParam('profiler_id');
        $id = $this->getRequest()->getParam('id');
        $filePath = $this->getRequest()->getParam('file_path');
        $rootDir = $this->_dir->getRoot();
        $varDir = $rootDir . DIRECTORY_SEPARATOR . 'var';
        $testFile = $varDir . DIRECTORY_SEPARATOR . $filePath;
        
        if($filePath && file_exists($testFile)  ){
            $profiler = $this->_profilerFactory->create()->load($profilerId);
        
            $process = \Magento\Framework\App\ObjectManager::getInstance()->get("\Commercers\Profilers\Service\Profiler\Process\Import");
            $profiler->setData("import_input_template",$xsltTemplate);
            $data = $process->parseDataFromFile($testFile, $profiler);

            $resultPage = $this->_resultPageFactory->create();
            if ($data == false) {
                $output = false;
            } else {
                $output = print_r($data, true);
            }
        }else{
            $output = 'Can not find a test file!!';
        }

        

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_RAW);

        return $resultJson->setContents($output);
    }

}
