<?php

namespace Commercers\Profilers\Model\ImportExport;

class Options implements \Magento\Framework\Data\OptionSourceInterface
{
    public function toOptionArray()
    {
        $arr = [
            '1' => 'Import',
            '0' => 'Export'
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