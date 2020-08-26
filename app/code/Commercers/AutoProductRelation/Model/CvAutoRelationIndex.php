<?php
/**
 *  Commercers Vietnam
 *  Duc Hieu
 */
namespace Commercers\AutoProductRelation\Model;

class CvAutoRelationIndex extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Commercers\AutoProductRelation\Model\ResourceModel\CvAutoRelationIndex'); /*định nghĩa lớp ResourceModel liên kết*/
    }
}
