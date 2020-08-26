<?php


namespace Commercers\Profilers\Service;

class Ouput
{
    /**
     * 
     * @param string $xslDoc
     * @return \XSLTProcessor
     */
    public function getrocessor($xslDoc){
        
        $dom = new \DOMDocument();
        
        $xsl = new \XSLTProcessor();
        
        $dom->loadXML($xslDoc);
       
        $xsl->importStyleSheet($dom);
        
        $xsl->registerPHPFunctions();
        
        return $xsl;
    }
}