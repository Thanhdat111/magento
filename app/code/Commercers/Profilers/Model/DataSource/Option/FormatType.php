<?php

namespace Commercers\Profilers\Model\DataSource\Option;

class FormatType implements \Magento\Framework\Data\OptionSourceInterface
{
    public function toOptionArray()
    {
        $arr = [
            'csv' => 'CSV',
            'xml' => 'XML',
            'edi' => 'EDI'
        ];
        foreach($arr as $key => $value){
            $options[] = [
                'value' => $key,
                'label' => $value
            ];
        }
        return $options;
    }
    //Commercers\Profilers\Model\Option\FormatType
}