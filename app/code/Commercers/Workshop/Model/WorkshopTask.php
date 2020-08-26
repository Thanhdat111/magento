<?php
namespace Commercers\Workshop\Model;

class WorkshopTask extends \Magento\Framework\Model\AbstractModel
{
    public function _construct()
    {
        $this->_init('Commercers\Workshop\Model\ResourceModel\WorkshopTask');
    }
}