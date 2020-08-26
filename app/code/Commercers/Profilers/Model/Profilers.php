<?php
namespace Commercers\Profilers\Model;

class Profilers extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Commercers\Profilers\Model\ResourceModel\Profilers'); /*định nghĩa lớp ResourceModel liên kết*/
    }
}
