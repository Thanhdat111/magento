<?php
/**
 * Created by PhpStorm.
 * User: niken
 * Date: 6/13/16
 * Time: 10:40
 */
class Commercers_Autocategory_Block_Adminhtml_Log extends Mage_Adminhtml_Block_Widget_Grid_Container {
    public function __construct(){
        $this->_controller = 'adminhtml_log';
        $this->_blockGroup = 'autocategory';
        $this->_headerText = Mage::helper('commercers_autocategory')->__('Log History');
        parent::__construct();
        $this->_removeButton('add');
    }

}