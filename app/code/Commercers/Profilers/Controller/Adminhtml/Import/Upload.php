<?php

namespace Commercers\Profilers\Controller\Adminhtml\Import;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
//use Commercers\Receiving\Service\Import\Csv;

class Upload extends Action
{
    protected $_mediaDirectory;
    protected $_fileUploaderFactory;
    protected $_ioFile;
    protected $_scopeConfig;
    protected $csv;
    public function __construct(
        Context $context,        
        \Magento\Framework\Filesystem $filesystem,
        \Commercers\Ftp\Model\Service\Ftp $dir,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Filesystem\Io\File $ioFile,
        \Commercers\Profilers\Model\ProfilersFactory  $dataProfilers,
        \Magento\Framework\Controller\ResultFactory $result,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
        //Csv $csv
    ) {
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_ioFile = $ioFile;
        $this->dir = $dir;
        $this->_dataProfilers = $dataProfilers;
        $this->resultRedirect = $result;
        $this->_scopeConfig = $scopeConfig;
        //$this->Csv = $csv;
        parent::__construct($context);
    }   

        public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        try{
            $target = $this->_mediaDirectory->getAbsolutePath('profilers/');
            $uploader = $this->_fileUploaderFactory->create(['fileId' => 'Import[import]']);
            /** Allowed extension types */
            //$uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'csv', 'doc']);
            /** rename file name if already exists */
            // $uploader->setAllowRenameFiles(true);
            /** upload file in folder "mycustomfolder" */
            $this->save();
            $result = $uploader->save($target);
            return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
            if ($result['file']) {
                $this->messageManager->addSuccess(__('File has been successfully uploaded')); 
            }
        } catch (\Exception $e) {
            //echo $e->getMessage();exit;
            $this->messageManager->addError($e->getMessage());
        }
    }
    public function save(){
        $path = '/home/comvn/public_html/pub/media/profilers/profilers.csv';
        $de = ',';
        $dataCsv = []; //$this->Csv->readCsvFile($path,$de);
        if(is_array($dataCsv)){
            foreach ($dataCsv as $key => $value) {
               if(is_array($value)){
                    foreach ($value as $value) {
                        $data = array_combine($value[0], $value[1]) ;
                        if($data){
                            $resultRedirect = $this->resultRedirect->create(ResultFactory::TYPE_REDIRECT);
                                        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                                        $model = $this->_dataProfilers->create();
                                        $model->addData(
                                            $data
                                            );
                                        $saveData = $model->save();
                                        $saveData = $model->unsetData();
                        }
                    }
                }
            }
        }
        if($saveData){
            $this->messageManager->addSuccess( __('Insert Record Successfully !') );
        }
        return $resultRedirect;
    }
}