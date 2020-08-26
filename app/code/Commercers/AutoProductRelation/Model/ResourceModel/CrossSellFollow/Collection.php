<?php
/**
 *  Commercers Vietnam
 *  Toan Dao 
 */
namespace Commercers\AutoProductRelation\Model\ResourceModel\CrossSellFollow;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    public function _construct(){
    $this->_init("Commercers\AutoProductRelation\Model\CrossSellFollow","Commercers\AutoProductRelation\Model\ResourceModel\CrossSellFollow");
            /* Lop Model + Lop Resource Model*/
    }
}