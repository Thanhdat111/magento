<?php
namespace Commercers\Profilers\Model;

class ProfilersProcessLog extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Commercers\Profilers\Model\ResourceModel\ProfilersProcessLog'); /*định nghĩa lớp ResourceModel liên kết*/
    }
}
