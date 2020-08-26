<?php
/**
 * Created by PhpStorm.
 * User: niken
 * Date: 6/10/16
 * Time: 16:45
 */
class Commercers_Autocategory_Model_Observer {


    public function addCategoryTab($observer)
    {
        $isActive = Mage::getStoreConfig('autocategory/general/enabled');
        if ($isActive != 1) {
            return;
        }
        $category = Mage::registry('current_category');

        $ruleModel = Mage::getModel('autocategory/rule')->load($category->getId(),'category_id');
        //print_r($ruleModel->getData());exit;
        if(!Mage::registry('autocategory_rule')){

            Mage::register('autocategory_rule', $ruleModel);
                $ruleContent =  Mage::app()->getLayout()->createBlock('autocategory/adminhtml_rule_edit_rule')->toHtml();

            $tabs = $observer->getEvent()->getTabs();
            $tabs->addTab('auto_category', array(
                'label'     => Mage::helper('commercers_autocategory')->__('Auto Category'),
                'content'   => $ruleContent
            ));
        }

        

        

    }
    public function autoCategorySave(Varien_Event_Observer $observer) {
        $isActive = Mage::getStoreConfig('autocategory/general/enabled');
        if ($isActive != 1) {
            return;
        }

        try {
            $category = $observer->getEvent()->getCategory();
            $categoryId = $category->getId();
            //Mage::log($category,null,'auto_category.log');
            $post = Mage::app()->getRequest()->getPost();

            $data = (isset($post['rule'])) ? $post['rule'] : array();

            if (isset($categoryId)) {
                $ruleModel = Mage::getModel('autocategory/rule')->load($categoryId, 'category_id');
                $origConditions = $ruleModel->getConditions()->prepareConditionSql();

                $ruleModel->setCategoryId($categoryId);
                $ruleModel->addData($data)
                    ->loadPost($data);
                $currentConditions = $ruleModel->getConditions()->prepareConditionSql();
                //Mage::log(var_dump($currentConditions),null,'auto_category.log');
                if ($origConditions != $currentConditions) {
                    $ruleModel->setRuleUpdate(1);
                    $ruleModel->setLastIndex(0);
                }
                if (isset($post['is_auto_enabled']) && !empty($currentConditions)) {
                    $ruleModel->setIsEnabled($post['is_auto_enabled']);
                } else {
                    $ruleModel->setIsEnabled(0);
                }

                if(isset($post['stock_qty'])){
                    if ($ruleModel->getStockQty() != $post['stock_qty']) {
                        $ruleModel->setRuleUpdate(1);
                        $ruleModel->setLastIndex(0);
                    }
                    $ruleModel->setStockQty($post['stock_qty']);
                    if (isset($post['run_now'])) {
                        if ($post['run_now']) {
                            $ruleModel->setLastUpdate(null);
                        }
                    }
                }

                $ruleModel->save();
            } else {
                //Mage::log($ruleModel->getData(),null,'auto_category.log');
            }
        } catch (Exception $e){
            Mage::log($e->getMessage(),null,'auto_category.log');
        }

    }
}