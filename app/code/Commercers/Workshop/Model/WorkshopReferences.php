<?php
namespace Commercers\Workshop\Model;

class WorkshopReferences extends \Magento\Framework\Model\AbstractModel
{
    const TYPE_ORDER_REFERENCE = 1;
    const TYPE_PRODUCT_REFERENCE = 2;
    
    public function _construct()
    {
        $this->_init('Commercers\Workshop\Model\ResourceModel\WorkshopReferences');
    }
}