<?php

namespace Commercers\Profilers\Model\Product\AttributeSet;

class Options implements \Magento\Framework\Data\OptionSourceInterface
{
    public function toOptionArray()
    {
        $arr = [
            'csv' => 'csv',
            'xml' => 'xml'
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