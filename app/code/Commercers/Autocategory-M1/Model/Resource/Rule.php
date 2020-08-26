<?php
class Commercers_Autocategory_Model_Resource_Rule extends Mage_CatalogRule_Model_Resource_Rule {
    public function _construct()
    {
        $this->_init('autocategory/rule','rule_id');
    }
}