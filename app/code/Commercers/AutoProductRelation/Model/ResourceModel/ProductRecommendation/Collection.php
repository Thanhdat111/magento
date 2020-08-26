<?php
/**
 *  Commercers Vietnam
 *  Toan Dao
 */
namespace Commercers\AutoProductRelation\Model\ResourceModel\ProductRecommendation;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    public function _construct(){
        $this->_init("Commercers\AutoProductRelation\Model\ProductRecommendation","Commercers\AutoProductRelation\Model\ResourceModel\ProductRecommendation");
        /* Lop Model + Lop Resource Model*/
    }
}