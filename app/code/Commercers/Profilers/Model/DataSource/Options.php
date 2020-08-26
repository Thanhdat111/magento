<?php

namespace Commercers\Profilers\Model\DataSource;

class Options implements \Magento\Framework\Data\OptionSourceInterface
{
    
    public function __construct(array $dataSources = array() ) {
        $this->dataSources = $dataSources;
    }


    public function toOptionArray()
    {
       
        $options = array(); 
        foreach($this->dataSources as $key => $value){
            $options[] = [
                'value' => $key,
                'label' => $value
            ];
        }
        return $options;
    }
    
}