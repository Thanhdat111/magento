<?php
/**
 *  Commercers Vietnam
 *  Toan Dao 
 */
namespace Commercers\AutoProductRelation\Model\ResourceModel\AutoRelationProcessLog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    public function _construct(){
    $this->_init("Commercers\AutoProductRelation\Model\AutoRelationProcessLog","Commercers\AutoProductRelation\Model\ResourceModel\AutoRelationProcessLog");
            /* Lop Model + Lop Resource Model*/
    }
     public function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()->join(
            ['rule' => $this->getTable('cv_auto_product_relattion_rule')],
            'main_table.rule_id = rule.rule_id',
            ['name'=>'rule.name']
        );
        return $this;
    }
    
}