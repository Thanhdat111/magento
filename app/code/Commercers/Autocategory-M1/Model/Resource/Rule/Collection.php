<?php
/**
 * Created by PhpStorm.
 * User: niken
 * Date: 5/8/16
 * Time: 14:42
 */
class Commercers_Autocategory_Model_Resource_Rule_Collection extends Mage_CatalogRule_Model_Resource_Rule_Collection {
    public function _construct(){
        $this->_init("autocategory/rule");
    }
}