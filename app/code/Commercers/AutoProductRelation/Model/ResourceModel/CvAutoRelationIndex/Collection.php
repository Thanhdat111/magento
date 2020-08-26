<?php
/**
 *  Commercers Vietnam
 *  Duc Hieu
 */
namespace Commercers\AutoProductRelation\Model\ResourceModel\CvAutoRelationIndex;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    public function _construct(){
    $this->_init("Commercers\AutoProductRelation\Model\CvAutoRelationIndex","Commercers\AutoProductRelation\Model\ResourceModel\CvAutoRelationIndex");
            /* Lop Model + Lop Resource Model*/
    }
}