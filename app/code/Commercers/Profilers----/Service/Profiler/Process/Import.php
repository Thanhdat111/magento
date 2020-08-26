<?php

namespace Commercers\Profilers\Service\Profiler\Process;

use Commercers\Profilers\Service\DataSource\Import\Factory as DataSourceFactory;



class Import extends \Commercers\Profilers\Service\Profiler\Process
{

    const SEPARATED_LIMIT = 100;
    protected $currentFilePath = false;
    public function __construct(
        DataSourceFactory $dataSourceFactory,
        \Magento\Framework\Filesystem\Io\File $file,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\DomDocument\DomDocumentFactory $domFactory,
        \Commercers\Profilers\Service\Log $processLog,
        \Commercers\Profilers\Service\Xml $xmlService,
        \Magento\Framework\Filesystem\Io\Ftp $ftp,
        \Commercers\Io\File $fileManagement,
        \Commercers\Profilers\Service\Data\Process\Xml\Output $outputProcessor    
    )
    {
        $this->dataSourceFactory = $dataSourceFactory;
        $this->_domFactory = $domFactory;
        $this->_file = $file;
        $this->_directoryList = $directoryList;
        $this->_processLog = $processLog;
        $this->xmlService = $xmlService;
        $this->ftp = $ftp;
        $this->fileManagement = $fileManagement;      
        $this->outputProcessor = $outputProcessor;
    }
    
    protected $_ftpProcessedFiles = array();

    public function getOutput($xslDoc, $xmlContent) {

        $dom = new \DOMDocument();
        $xsl = new \XSLTProcessor();
        $dom->loadXML($xslDoc);
        $xsl->importStyleSheet($dom);
        $xmlSource = $this->_domFactory->create();
        $xmlSource->loadXML($xmlContent, LIBXML_PARSEHUGE | LIBXML_NSCLEAN);
        $out = $xsl->transformToXML($xmlSource);

        return $out;
    }

    public function execute($profiler) {
        $files = $this->fileManagement->getFiles($profiler->getData());
        
        if (count($files['ftp'])) {
            
            foreach ($files['ftp'] as $file) {
                //print_r($file);exit;
                $dataProcessLog = $this->_processLog->execute($profiler->getId());
                $skus = $this->_execute($file['file'], $profiler);
                if (is_string($skus)) {
                    $message = $skus;
                } else {

                    $this->fileManagement->mvFilesOnFtp($file['ftp_file'], $profiler->getData());
                    $message = sprintf('Proceeded file %s - Skus: %s ', $file['file'], print_r($skus, true));
                    $status = 1;
                    $this->fileManagement->moveTmpFile($file['file'], $profiler->getData());




                }

                $this->_processLog->executeAfter($dataProcessLog['process_id'], $status, $message);
            }
        }

        if (count($files['local'])) {
            $limitFile = false;
            if($profiler->getData('local_nfiles_per_process')){
                $limitFile = $profiler->getData('local_nfiles_per_process');
            }
            $cnt = 0;
            foreach ($files['local'] as $file) {
                $cnt++;
                if($limitFile && $cnt > $limitFile){
                    break;
                }
                /** @var \FilesystemIterator $file */
                //print_r($file);exit;
                $filePath = (string)$file;
                
                $dataProcessLog = $this->_processLog->execute($profiler->getId());
                $skus = $this->_execute($filePath, $profiler);
                $message = sprintf('Proceeded file %s - Skus: %s ', $file->getFileName(), print_r($skus, true));
                $status = 1;
                $this->fileManagement->mvFilesOnLocal($filePath, $profiler->getData());
                $this->_processLog->executeAfter($dataProcessLog['process_id'], $status, $message);
            }
        }
    }

    protected function _execute($file, $profiler) {
        try {

            //echo $file; exit;
            $dataSource = $this->dataSourceFactory->get($profiler->getDataSource());

            $xmlContent = $this->outputProcessor->execute($file, $profiler->getFormat());
            $out = $this->getOutput($profiler->getData('import_input_template'), $xmlContent);
            $data = $this->xmlToArray($out);
            
            //print_r($data);exit;
            $skus = $dataSource->execute($data);
            if($profiler->getId() == 6 ){
                if(file_exists($file)){

                    $localRootPath = $this->_directoryList->getRoot();
                    $proceededFolder = $localRootPath . DIRECTORY_SEPARATOR. 'var/Ahlers/test/in/reset';
                    $lastedFile = $proceededFolder . DIRECTORY_SEPARATOR . 'lasted_file.edi';
                    if(file_exists($lastedFile)){
                        rm($lastedFile);
                    }
                    copy($file, $lastedFile);                
                }
            }
            
            


            return $skus;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
    
    public function parseDataFromFile($file, $profiler){
        
        $xmlContent = $this->outputProcessor->execute($file, $profiler->getFormat());
        
        $out = $this->getOutput($profiler->getData('import_input_template'), $xmlContent);
        $data = $this->xmlToArray($out);
        return $data;
    }
    
    public function xmlToArray($xmlData){
        
        $xml = new \SimpleXMLElement($xmlData);
        
        $data = array('items' => array('item' => array()));
        
        $items = $xml->xpath("/items/item");
        foreach($items as $item){
            $itemData = (array)$item;
            foreach($itemData as $key => &$value){
                $value = (string)$value;
            }
            $data['items']['item'][] = $itemData;
        }
        return $data;
    }

}
