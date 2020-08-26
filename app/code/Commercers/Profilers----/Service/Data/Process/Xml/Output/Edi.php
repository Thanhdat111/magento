<?php

namespace Commercers\Profilers\Service\Data\Process\Xml\Output;

class Edi {

    public function __construct(
       
        \Commercers\Profilers\Service\Xml $xml    
    ) {
        
        $this->xmlService = $xml;
    }
    
    public function execute($fileName, $numberOfItemsToShow = false) {
        
        $data = $this->toArray($fileName);
       // print_r($data); exit;
        //var_dump($this->xmlService->arrayToXml($data));exit;
        return $this->xmlService->arrayToXml($data);
    }
    
    public function toArray($fileName, $numberOfItemsToShow = false){
        
        $message = \Metroplex\Edifact\Message::fromFile($fileName);
        //echo $fileName; exit;
        $allowedSegmentCodes = array("LIN", 'PRI', 'QTY','PIA','IMD');
        $itemSegmentCode = 'LIN';
        $segmentIndex = 0;
        $tagCodeIndex = 0;
        
        foreach ($message->getAllSegments() as $segment) {
            //print_r($segment);
            $elemntKeys = [];
            $segmentCode = $segment->getSegmentCode();
            if (!in_array($segmentCode, $allowedSegmentCodes)) {
                continue;
            }
            
            $segmentData = array();
           
            if ($segment->getSegmentCode() == $itemSegmentCode) {
                $segmentIndex = $segment->getElement(0);
            }
            
            if($numberOfItemsToShow !== false){
                if($segmentIndex > $numberOfItemsToShow){
                    break;
                }
            }
            
            $firstValue = '';
            
            foreach ($segment->getAllElements() as  $element) {

                if(!isset($elemntKeys[$segmentCode])){

                    $elemntKeys[$segment->getSegmentCode()] = 0;

                }

                if (is_array($element)) {
                    foreach ($element as $subKey => $subElement) {
                        if (is_integer($subKey)) {
                            $element['val_' . $subKey] = $subElement;
                            unset($element[$subKey]);
                        }

                        if (!$firstValue)
                            $firstValue = $subElement;
                    }
                }else {
                    if (!$firstValue)
                        $firstValue = $element;
                }
                 
                $segmentData['e_' . $elemntKeys[$segmentCode] ] = $element;
                $elemntKeys[$segmentCode]++;
            }
            /*
            if($segment->getSegmentCode() == 'PIA'){
                    var_dump($key);print_r($segmentData['e_' . $key] );
                }
            */    
            if ($segment->getSegmentCode() != $itemSegmentCode && !is_numeric($firstValue) ) {
                
                  $tagCode = $segment->getSegmentCode() . '_' . $firstValue;

            } else {
                
                $tagCode = $segment->getSegmentCode();
            }
            if(isset($data['segments']['segment'][$segmentIndex - 1][$tagCode])){
                //$tagCode = $tagCode . '_'. $tagCodeIndex;
                //$tagCodeIndex++;
                $data['segments']['segment'][$segmentIndex - 1][$tagCode] = array_merge($data['segments']['segment'][$segmentIndex - 1][$tagCode], $segmentData);
            }else{
                $data['segments']['segment'][$segmentIndex - 1][$tagCode] = $segmentData;
            }
            
            //if($segmentIndex > 10)                break;
        }
       //echo '<pre>';print_r($data);exit;
        return $data;
    }

}
