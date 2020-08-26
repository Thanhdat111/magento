<?php


namespace Commercers\Profilers\Controller\Adminhtml\Import\File;

use Magento\Backend\App\Action;

class Check extends Action {

    protected $_resultPageFactory;
    
    
    public function __construct(
    \Magento\Backend\App\Action\Context $context, 
            \Magento\Framework\View\Result\PageFactory $resultPageFactory,
            \Magento\Framework\Filesystem\DirectoryList $dir,
            \Commercers\Profilers\Service\Xml $xmlService,
            \Commercers\Profilers\Service\Data\Process\Xml\Output $outputProcessor,
            \Commercers\Profilers\Model\ProfilersFactory $profilerFactory
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_dir = $dir;
        $this->xmlService = $xmlService;
        $this->outputProcessor = $outputProcessor;
        $this->profilerFactory = $profilerFactory;
        parent::__construct($context);
    }

    public function execute() {
        
        $resultPage = $this->_resultPageFactory->create();
        
        if($this->getRequest()->getParam('profiler_id')){
            $profiler = $this->profilerFactory->create();
            $profiler = $profiler->load($this->getRequest()->getParam('profiler_id'));

            if ($ediFile = $this->getRequest()->getParam('file_path', false)) {
             //   echo get_class($resultPage->getLayout()->getBlock('review_data'));exit;
                
                $rootDir = $this->_dir->getRoot();
                $varDir = $rootDir . DIRECTORY_SEPARATOR . 'var';
                $testFile = $varDir . DIRECTORY_SEPARATOR . $ediFile;

                $data = $this->outputProcessor->toArray($testFile, $profiler->getFormat(), 1);
                //echo '<pre>';print_r($data);exit;    
                
                $resultPage->getLayout()->getBlock('review_data')->setReviewData($data);
                
                
            }
        }
        
        
        
        

        $resultPage->getConfig()->getTitle()->prepend(__('Review EDI Structure'));
        return $resultPage;
    }

}
