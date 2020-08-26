<?php
namespace Commercers\ProductExport\Service;

class Export 
{
	protected $_profilersExport;
	protected $_file;
	protected $_profilerProductExport;
	protected $_domFactory;
	protected $_processLog;

	public function __Construct(
		\Magento\Framework\Filesystem\Io\File $file,
		\Magento\Framework\App\Filesystem\DirectoryList $directoryList,
		\Commercers\ProductExport\Model\Config\Source\SelectProfilersExport $profilerProductExport,
		\Magento\Framework\DomDocument\DomDocumentFactory $domFactory,
		\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
		\Commercers\Profilers\Service\Log $processLog
	){
		$this->_file = $file;
		$this->_directoryList = $directoryList;
		$this->_domFactory = $domFactory;
		$this->_profilerProductExport = $profilerProductExport;
		$this->_productCollectionFactory = $productCollectionFactory;  
		$this->_processLog = $processLog;
	}

	public function execute(){
		$collection = $this->_productCollectionFactory->create();
    	$collection->addAttributeToSelect('*');
    	$data = $collection->getData();
    	
    	$profilerExport = $this->_profilerProductExport->getProfilersSelected();
		foreach($profilerExport as $value){
			$xslDoc = $value['outputformat'];
			$id_profiler = $value['id'];
			$ftp = $value['ftp'];
			$enableLocal = $value['enablelocal'];
			$enableFtp = $value['enableftp'];
			$idPrimaryProfiler = $value['id_primary'];
		}
		$localFolder = $ftp['localfolder'];
		$localFolderFtp = $ftp['localfolderftp'];

		//write log to start export
		$dataProcessLog = $this->_processLog->execute($idPrimaryProfiler);
		
		try {
			$dom = new \DOMDocument();
			$xsl = new \XSLTProcessor;
			$dom->loadXML($xslDoc);
			$xsl->importStyleSheet($dom);
			$xmlSource = $this->_domFactory->create();
			// $xsl->loadXML($xslDoc);
			$xmlSource->loadXML($this->arrayToXml($data));

			$out = $xsl->transformToXML( $xmlSource );
			//echo "<pre>";echo $out;
			$fileName = $id_profiler . "_csv.csv";
			if($enableLocal == 1){
				$filePathLocal = DIRECTORY_SEPARATOR . $localFolder;
				$this->writeFileFromOutputFromat($filePathLocal,$out,$fileName);
			}
			if($enableFtp == 1){
				$filePathFtp = DIRECTORY_SEPARATOR . $localFolderFtp;
				$this->writeFileFromOutputFromat($filePathFtp,$out,$fileName);
			}
			$status = 1;
			$message = "EXPORT PRODUCT TO FILE SUCCESS!";
		} catch (Exception $e) {
			$status = 1;
			$message = "EXPORT PRODUCT TO FILE FAILED!";
		}
		//write log to end export
		$this->_processLog->executeAfter($dataProcessLog['process_id'],$status,$message);
		
		//print_r($this->arrayToXml($stockLogData));
		//exit;
	}

	public function writeFileFromOutputFromat($filePath,$out,$fileName){
		$path = $this->_directoryList->getPath('var').$filePath;
		if (!is_dir($path)) {
			$this->_file->mkdir($path, 0775);
		}
		$this->_file->open(array('path'=>$path));
		$this->_file->write($fileName, $out, 0666);
	}

	public function arrayToXml($array, $rootElement = null, $xml = null) { 
		$_xml = $xml; 

		    // If there is no Root Element then insert root 
		if ($_xml === null) { 
			$_xml = new \SimpleXMLElement($rootElement !== null ? $rootElement : '<items/>'); 
		} 

		    // Visit all key value pair 
		foreach ($array as $k => $v) { 

		        // If there is nested array then 
			if (is_array($v)) {  

		            // Call function for nested array 
				$this->arrayToXml($v, '<item/>', $_xml->addChild('item')); 
			} 

			else { 

		            // Simply add child element.  
				//echo $k;
				$_xml->addChild($k, $v); 
			} 
		} 

		return $_xml->asXML(); 
	} 

}