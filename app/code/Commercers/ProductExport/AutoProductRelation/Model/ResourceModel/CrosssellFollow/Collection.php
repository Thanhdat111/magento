<?php
/**
 *  Commercers Vietnam
 *  Toan Dao 
 */
namespace Commercers\AutoProductRelation\Model\ResourceModel\CrosssellFollow;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    public function _construct(){
    $this->_init("Commercers\AutoProductRelation\Model\CrosssellFollow","Commercers\AutoProductRelation\Model\ResourceModel\CrosssellFollow");
            /* Lop Model + Lop Resource Model*/
    }
}