<?php
/**
 *  Commercers Vietnam
 *  Toan Dao 
 */
namespace Commercers\AutoProductRelation\Model;

class AutoRelationProcessLog extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Commercers\AutoProductRelation\Model\ResourceModel\AutoRelationProcessLog'); /*định nghĩa lớp ResourceModel liên kết*/
    }
}
