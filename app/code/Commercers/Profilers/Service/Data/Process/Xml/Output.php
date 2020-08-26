<?php

namespace Commercers\Profilers\Service\Data\Process\Xml;

class Output {

    public function __construct(
        array $processors,
        \Commercers\Profilers\Service\Xml $xml    
    ) {
        $this->_processors = $processors;
        $this->_xmlHelper = $xml;
    }

    public function execute($filename, $type = 'xml', $numberOfItemsToShow = false) {
        //echo $filename; exit;
        if (file_exists($filename)) {
            if($processor = $this->getProceesor($type)){
                return $processor->execute($filename, $numberOfItemsToShow);
            }
            return file_get_contents($filename);
        }
        return false;
    }

    public function toArray($filename, $type = 'xml', $numberOfItemsToShow = false) {
        
        if (file_exists($filename)) {
            if($processor = $this->getProceesor($type)){
                return $processor->toArray($filename, $numberOfItemsToShow);
            }
            $this->_xmlHelper->xmlToArray(file_get_contents($filename)) ;
        }
        return false;
    }

    protected function getProceesor($type) {
        //print_r($this->_processors);exit;
        if (isset($this->_processors[$type])) {
            $processor = \Magento\Framework\App\ObjectManager::getInstance()->get($this->_processors[$type]);
            return $processor;
        }
    }

}
