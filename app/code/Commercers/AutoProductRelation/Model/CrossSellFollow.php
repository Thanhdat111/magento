<?php
/**
 *  Commercers Vietnam
 *  Toan Dao 
 */
namespace Commercers\AutoProductRelation\Model;

class CrossSellFollow extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Commercers\AutoProductRelation\Model\ResourceModel\CrossSellFollow'); /*định nghĩa lớp ResourceModel liên kết*/
    }
}
