<?php

namespace Commercers\Profilers\Model\Option;

class FtpType implements \Magento\Framework\Data\OptionSourceInterface
{
    public function toOptionArray()
    {
        $arr = [
            'ftp' => 'fpt',
            'sftp' => 'sftp'
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