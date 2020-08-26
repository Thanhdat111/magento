<?php

namespace Commercers\Profilers\Model\Option;

class OptionProfilerStatus implements \Magento\Framework\Data\OptionSourceInterface
{
    public function toOptionArray()
    {
        $arr = [
            '1' => 'Enable',
            '0' => 'Disable'
        ];
        foreach($arr as $key => $value){
            $options[] = [
                'value' => $key,
                'label' => $value
            ];
        }
        return $options;
    }
    
}