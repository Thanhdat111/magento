<?php
namespace Commercers\Workshop\Controller\Adminhtml\Chat\Action\File;

use Magento\Backend\App\Action;

use Magento\Framework\Controller\ResultFactory;

use Magento\Framework\Filesystem;

class Upload  extends \Magento\Backend\App\Action { 
        private $fileSystem;
        private $fileUploaderFactory;
        protected $coreSession;
        protected $_storeManager;
        protected $fileUpload = array();
    public function __construct(
        Filesystem $fileSystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
       \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
       \Magento\Sales\Model\Order $order, 
        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
       ResultFactory $resultFactory,     
        Action\Context $context
        
    ) {
        
        parent::__construct($context);
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->fileSystem          = $fileSystem;
        $this->order = $order;
        $this->resultFactory = $resultFactory;
        $this->coreSession = $coreSession;
        $this->_storeManager = $storeManagerInterface;
    }
    
    public function execute(){
        $files = $this->getRequest()->getFiles('files');
        if($files){
            foreach($files as $data){
                $filesData = $data;
            }   
        }
        if ($filesData['name']) {
            $uploader = $this->fileUploaderFactory->create(['fileId' => $filesData]);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $uploader->setAllowCreateFolders(true);
            $mediaPath  = $this->fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath();
            $path  =  $mediaPath.'workshop/' ;
            $result = $uploader->save($path);
            $upload_document = 'workshop'.$uploader->getUploadedFilename();
            $filePath = 'pub/media/workshop'.$result['file'];
            $fileName = $result['name'];
            $baseUrl = $this->_storeManager->getStore()->getBaseUrl();
            $filePathFull = $baseUrl.$filePath;
            $fileFull=  array('fileName'=>$fileName,'filePath'=>$filePathFull);            
            $uploadFile = $this->coreSession->getPathUploadFiles();
            $uploadFile[] = $fileFull;
            $this->coreSession->setPathUploadFiles($uploadFile);
            $mediaUrl = $this ->_storeManager-> getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );     
            $result['url'] = $mediaUrl . 'workshop'. $result['file'];
        } else {
             $filePath = '';
             $this->coreSession->setPathUploadFiles($filePath);
        }
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        //$error =false;
        //$result = ['error'=>$error,'success'=> ($error) ? false : true ];
        $resultJson->setData($result);
        return $resultJson;
        //return json_encode($orderData);
    }
}
