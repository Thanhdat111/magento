<?php

namespace Commercers\Profilers\Service;

class Xml {

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

    public function arrayToXml($data) {

        $xml = null;
        $this->parseXml($data, $xml);
        return $xml->asXML();
    }

    function xmlsafe($s, $intoQuotes = 0) {
        if ($intoQuotes)
            return str_replace(array('&', '>', '<', '"'), array('&amp;', '&gt;', '&lt;', '&quot;'), $s);
        // SAME AS htmlspecialchars($s)
        else
            return str_replace(array('&', '>', '<'), array('&amp;', '&gt;', '&lt;'), $s);
        // SAME AS htmlspecialchars($s,ENT_NOQUOTES)
    }

   

    protected function parseXml($data, &$xml) {

        
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                if (!is_numeric($key)) {
                    if (is_string($value)) {

                        $value = $this->xmlsafe($value);
                        $child = $xml->addChild($key);
                        $node = dom_import_simplexml($child);
                        $node->appendChild($node->ownerDocument->createCDATASection($value));
                    } else {
                        if (!is_object($value)) {
                           
                            $xml->addChild($key, $value);
                        }
                    }
                }
            } else {
                
                
                
                //case : associated array
                if ($this->isAssoc($value)) {
                    if (!is_numeric($key)) {                    
                            $parentAssociatedTag = $this->createXmlNode($xml, $key);
                    }
                    $this->parseXml($value, $parentAssociatedTag);
                } else {
                    
                    foreach ($value as $valueItem) {
                        
                        if (!is_numeric($key)) {                    
                            $parentAssociatedTag = $this->createXmlNode($xml, $key);
                        }
                        $this->parseXml($valueItem, $parentAssociatedTag);
                        
                    }
                }
            }
        }
    }

    protected function createXmlNode(&$xml, $name) {
        if ($xml === null) {


            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<" . $name . "/>");
            //echo $name; exit;
            return $xml;
        } else {

            return $xml->addChild($name);
        }
        //return $xml;
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
