<?php
class Commercers_Autocategory_Model_Rule_Condition_Combine extends Mage_Rule_Model_Condition_Combine {
    public function __construct()
    {
        parent::__construct();
        $this->setType('autocategory/rule_condition_combine');
    }

    public function getNewChildSelectOptions()
    {
        $productCondition = Mage::getModel('autocategory/rule_condition_product');
        $productAttributes = $productCondition->loadAttributeOptions()->getAttributeOption();
        $attributes = array();
        foreach ($productAttributes as $code=>$label) {
            $attributes[] = array('value'=>'autocategory/rule_condition_product|'.$code, 'label'=>$label);
        }
        $conditions = array(
            array('value'=>'autocategory/rule_condition_combine', 'label'=>Mage::helper('catalogrule')->__('Conditions Combination')),
            array('label'=>Mage::helper('catalogrule')->__('Product Attribute'), 'value'=>$attributes),
        );
        $conditions = array_merge_recursive($conditions, array(
            array('value'=>'autocategory/rule_condition_combine', 'label'=>Mage::helper('catalogrule')->__('Conditions combination')),
        ));
        return $conditions;
    }

    public function prepareConditionSql()
    {
        $wheres = array();
        foreach ($this->getConditions() as $condition) {
            /** @var $condition Mage_Rule_Model_Condition_Abstract */
            $wheres[] = $condition->prepareConditionSql();
        }

        if (empty($wheres)) {
            return '';
        }
        $delimiter = $this->getAggregator() == "all" ? ' AND ' : ' OR ';
        return ' (' . implode($delimiter, $wheres) . ') ';
    }
}