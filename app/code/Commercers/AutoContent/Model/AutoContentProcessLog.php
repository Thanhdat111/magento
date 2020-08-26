<?php
namespace Commercers\AutoContent\Model;

class AutoContentProcessLog extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Commercers\AutoContent\Model\ResourceModel\AutoContentProcessLog'); /*định nghĩa lớp ResourceModel liên kết*/
    }
}
