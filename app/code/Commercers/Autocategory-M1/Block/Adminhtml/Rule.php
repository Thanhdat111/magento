<?php
/**
 * Created by PhpStorm.
 * User: niken
 * Date: 4/14/16
 * Time: 14:51
 */
class Commercers_Autocategory_Block_Adminhtml_Rule extends Mage_Adminhtml_Block_Widget_Grid_Container {
    public function __construct(){
        $this->_controller = 'adminhtml_rule';
        $this->_blockGroup = 'autocategory';
        $this->_headerText = Mage::helper('commercers_autocategory')->__('Rule Condition Manager');
        $this->_addButtonLabel = Mage::helper('commercers_autocategory')->__('Add Rule');
        parent::__construct();
    }
    
}