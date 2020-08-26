<?php
namespace Commercers\Workshop\Model;

class WorkshopClaims extends \Magento\Framework\Model\AbstractModel
{
    public function _construct()
    {
        $this->_init('Commercers\Workshop\Model\ResourceModel\WorkshopClaims');
    }
}