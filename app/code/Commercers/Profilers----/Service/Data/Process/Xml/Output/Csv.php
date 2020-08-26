<?php

namespace Commercers\Profilers\Service\Data\Process\Xml\Output;

class Csv {
    
    
    public function execute($filename){
        
        //need to have a header
        
        return file_get_contents($filename);
    }
    
    
}