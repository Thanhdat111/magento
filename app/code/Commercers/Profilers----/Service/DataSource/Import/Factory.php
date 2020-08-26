<?php

namespace Commercers\Profilers\Service\DataSource\Import;

class Factory {
    
    
    public function __construct($dataSources) {
        
        $this->dataSources = $dataSources;
    }

    
    public function get($type){
       if(isset($this->dataSources[$type])){
            
           return \Magento\Framework\App\ObjectManager::getInstance()->get($this->dataSources[$type]);
       }
       return false;
    }
    
}