<?php
/**
 *  Commercers Vietnam
 *  Toan Dao 
 */
namespace Commercers\AutoProductRelation\Model;

class CrosssellFollow extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Commercers\AutoProductRelation\Model\ResourceModel\CrosssellFollow'); /*định nghĩa lớp ResourceModel liên kết*/
    }
}
