<?php
namespace Commercers\Profilers\Service;

class Html {

    protected function isAssoc(array $arr) {
        if (array() === $arr)
            return false;
        //return array_keys($arr) !== range(0, count($arr) - 1);
        //$keyFirst = \array_key_first($arr);
        if(!is_array($arr)) return false;
        reset($arr);
        $keyFirst = key($arr);
        
        if(is_numeric($keyFirst)){
            return false;
        }
        return true;
    }

    public function arrayToHtml($data, $xpathTemplage = '') {
        ini_set("display_errors",1);
        $doc = new \DOMDocument;
        $currentXpath = [];
        $xpathTemplate = '<xsl:value-of select="{value}"/>';
        $this->toHtml($data, $doc, $currentXpath, $doc, $xpathTemplate);
        return $doc->saveHTML();
    }

    


    protected function toHtml($data, &$html, &$currentXpath, $doc, $xpathTemplate) {

        //echo '<pre>';print_r($data);
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                if (!is_numeric($key)) {
                    //$currentXpath .= '/'. $key;
                    $html->appendChild($this->createLiTag((string)$key, $doc));
                    $html->appendChild($this->createLiValueTag($value, $doc));
                    $html->appendChild($this->createLiXpathTag($this->getXpathOfElement($currentXpath, $key), $doc, $xpathTemplate));
                    
                }
                
            } else {
                //case : associated array
                if ($this->isAssoc($value)) {
                    
                    if (!is_numeric($key)) {                    
                           $parentAssociatedTag = $html->appendChild($this->createLiTag($key, $doc));
                           $parentAssociatedTag = $parentAssociatedTag->appendChild($this->createUlTag($key, $doc));
                           
                           $parentXpath = $currentXpath;
                           $parentXpath[] = $key;
                           
                    }
                    $this->toHtml($value, $parentAssociatedTag, $parentXpath, $doc, $xpathTemplate);
                    
                } else {
                    $currentXpath[] = $key;
                    foreach ($value as $valueItem) {
                        
                        if (!is_numeric($key)) {                    
                           $parentAssociatedTag = $html->appendChild($this->createLiTag($key, $doc));
                           $parentAssociatedTag = $parentAssociatedTag->appendChild($this->createUlTag($key, $doc));
                        }
                        $this->toHtml($valueItem, $parentAssociatedTag, $currentXpath, $doc, $xpathTemplate);
                        
                    }
                }
            }
        }
    }
    
    protected function getXpathOfElement($currentXpath, $key){
        if(count($currentXpath) > 2){
            unset($currentXpath[0]);
            unset($currentXpath[1]);
        }
        return implode("/", $currentXpath).'/'.$key;
    }


    protected function createUlTag($value, $doc){
        $element = $doc->createElement('ul');
        $element->setAttribute('class', 'nested sub');
        return $element;
    }
    
    protected function createLiTag($value, $doc){
        
        $spanElement = $doc->createElement('span', $value);
        $spanElement->setAttribute('class', 'attr-name  sub');
        $element = $doc->createElement('li');
        $element->appendChild($spanElement);
        return $element;
    }
    
    protected function createLiValueTag($value, $doc, $valueTemplate = false){
        
        
        if($valueTemplate){
            $value = str_replace('{value}', $value, $valueTemplate);
        }
        $element = $doc->createElement('li', $value);
        $element->setAttribute('class', 'value');
        return $element;
    }
    
    protected function createLiXpathTag($value, $doc, $valueTemplate = false){
        
        
        if($valueTemplate){
            $value = str_replace('{value}', $value, $valueTemplate);
        }
        $element = $doc->createElement('li');
        $spanElement = $doc->createElement('span', $value);
        $spanElement->setAttribute('class', 'xslt-path-of-value');
        
        //$copyElement = $doc->createElement('button', 'Copy');
        
        //$copyElement->setAttribute('onClick', 'document.copyXpathText(this)');
        //$copyElement->setAttribute('onClick', 'copyXpathText()');
        
        $element->appendChild($spanElement);
        //$element->appendChild($copyElement);
        $element->setAttribute('class', 'value');
        
        return $element;
    }
    
    

    

}
