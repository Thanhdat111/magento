<?php
/**
 * Created by PhpStorm.
 * User: niken
 * Date: 6/8/16
 * Time: 23:28
 */
class Commercers_Autocategory_Model_Rule extends Mage_CatalogRule_Model_Rule {
    public function _construct() {
        $this->_init('autocategory/rule');
    }
    public function getConditionsInstance()  {
        return Mage::getModel('autocategory/rule_condition_combine');
    }
}