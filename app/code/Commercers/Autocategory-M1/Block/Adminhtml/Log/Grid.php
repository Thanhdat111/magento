<?php
/**
 * Created by PhpStorm.
 * User: niken
 * Date: 6/13/16
 * Time: 10:40
 */
class Commercers_Autocategory_Block_Adminhtml_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid {


    public function __construct() {
        parent::__construct();
        $this->setId('logGrid');
        $this->setDefaultSort('last_update');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('autocategory/rule')->getCollection()
            ->addFieldToFilter('is_enabled',array('eq'=>1))
        ;
        $collection->getSelect()
            ->join(
                array('catalog_category_entity_varchar'),
                'main_table.category_id=catalog_category_entity_varchar.entity_id',
                array('catalog_category_entity_varchar.value'=>'catalog_category_entity_varchar.value')
            )
            ->where('attribute_id = 41')
            ->group('category_id')
        ;
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('category_name', array(
            'header'    => Mage::helper('commercers_autocategory')->__('Category Name'),
            'width'     => '100px',
            'index'     => 'catalog_category_entity_varchar.value',
        ));
        $this->addColumn('last_update', array(
            'header'    => Mage::helper('commercers_autocategory')->__('Last Update'),
            'width'     => '100px',
            'index'     => 'last_update',
        ));
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}