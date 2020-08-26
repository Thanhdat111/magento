<?php
namespace Commercers\Workshop\Model;

class WorkshopChat extends \Magento\Framework\Model\AbstractModel
{
    public function _construct()
    {
        $this->_init('Commercers\Workshop\Model\ResourceModel\WorkshopChat');
    }
}