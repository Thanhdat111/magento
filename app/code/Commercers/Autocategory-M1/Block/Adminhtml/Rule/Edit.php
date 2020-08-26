<?php
/**
 * Created by PhpStorm.
 * User: niken
 * Date: 4/14/16
 * Time: 17:24
 */
class Commercers_Autocategory_Block_Adminhtml_Rule_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{
    public function __construct()
    {
        parent::__construct();
        $this->setId('catalog_category_essential');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
    }
}