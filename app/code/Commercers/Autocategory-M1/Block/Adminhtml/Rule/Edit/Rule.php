<?php
/**
 * Created by PhpStorm.
 * User: niken
 * Date: 5/16/16
 * Time: 21:34
 */
class Commercers_Autocategory_Block_Adminhtml_Rule_Edit_Rule extends Mage_Adminhtml_Block_Widget_Form{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $helper = Mage::helper('commercers_autocategory');

        $model = Mage::registry('autocategory_rule');
        if(!$model)
            $model = Mage::getModel('autocategory/rule');
        
        $model->getConditions()->setJsFormObject('rule_conditions_fieldset');

        $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
            ->setTemplate('promo/fieldset.phtml')
            ->setNewChildUrl($this->getUrl('adminhtml/promo_catalog/newConditionHtml/form/rule_conditions_fieldset'));

        $fieldset = $form->addFieldset('autocategory_config_form', array('legend'=>Mage::helper('catalog')->__('General')));

        $fieldset->addField(
            'is_enabled',
            'select',
            array(
                'label' => $helper->__('Enable'),
                'name' => 'is_auto_enabled',
                'values' => array(
                    '1' => 'Yes',
                    '0' => 'No',
                ),
            )
        );
        $fieldset->addField(
            'run_now',
            'select',
            array(
                'label' => $helper->__('Top Priorties'),
                'name' => 'run_now',
                'values' => array(
                    '1' => 'Yes',
                    '0' => 'No',
                ),
            )
        );
        $fieldset->addField(
            'stock_qty',
            'text',
            array(
                'label' => $helper->__('Stock Qty Condition'),
                'name' => 'stock_qty',
            )
        );


        $fieldset = $form->addFieldset('rule_conditions_fieldset', array(
            'legend'=>$helper->__("Filter Conditions")
        ))->setRenderer($renderer);

        $fieldset->addField('conditions', 'text', array(
            'name' => 'rule[conditions]',
            'label' => $helper->__('Rule Condition'),
            'title' => $helper->__('Rule Condition'),
            'required' => true,

        ))->setRule($model)->setRenderer(Mage::getBlockSingleton('rule/conditions'));

        $form->setValues($model->getData());

        return parent::_prepareForm();

    }
}