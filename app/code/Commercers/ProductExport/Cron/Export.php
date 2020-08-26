<?php
namespace Commercers\ProductExport\Service;

class Export 
{
	protected $_stockLogFactory;
	protected $_domFactory;
	protected $_dir;
	protected $_profilerExport;
	protected $_file;

	public function __Construct(
		\Commercers\StockLog\Model\ResourceModel\StockLog\CollectionFactory $stockLogFactory,
		\Magento\Framework\DomDocument\DomDocumentFactory $domFactory,
		\Commercers\Ftp\Model\Service\Ftp $dir,
		\Commercers\Receiving\Model\Config\Source\SelectProfilersExport $profilerExport,
		\Magento\Framework\Filesystem\Io\File $file,
		\Magento\Framework\App\Filesystem\DirectoryList $directoryList
	){
		$this->_stockLogFactory = $stockLogFactory;
		$this->_domFactory = $domFactory;
		$this->_dir = $dir;
		$this->_profilerExport = $profilerExport;
		$this->_file = $file;
		$this->_directoryList = $directoryList;
	}

	public function execute(){
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

		$productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');

		$collection = $productCollection->create()
		            ->addAttributeToSelect('*')
		            ->load();

		$data = $collection->getData();
		foreach($data as $value){
			echo "<pre>";print_r($value);
		}
		exit;
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