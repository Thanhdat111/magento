<?php
/**
 * Created by PhpStorm.
 * User: niken
 * Date: 6/8/16
 * Time: 23:27
 */
class Commercers_Autocategory_Helper_Data extends Mage_Core_Helper_Abstract {
    public function updateLog($categoryId,$rule,$lastIndex){
        $configModel = Mage::getModel('autocategory/rule')->load($categoryId,'category_id');
        $now = Mage::getModel('core/date')->date('Y-m-d H:i:s');
        $configModel->setIsEnabled($rule->getIsEnabled());
        $configModel->setLastUpdate($now);
        $configModel->setRuleUpdate(0);
        $configModel->setLastIndex($lastIndex);
        $configModel->save();
    }
    public function getAllowedAttributes(){
        $isFlatEnable = Mage::getStoreConfig('autocategory/general/flattable');
        $attributes = array();
        if($isFlatEnable){
            $attributes = Mage::getStoreConfig('autocategory/general/attributes_flat');
        } else {
            $attributes = Mage::getStoreConfig('autocategory/general/attributes_noflat');
        }

        if($attributes){
            //return array_merge(array('type_id'),explode(',', $attributes));
            return explode(',', $attributes);
        }
        return array();
    }
    public function getStoreIds($categoryId){
        $category = Mage::getModel('catalog/category')->load($categoryId);
        $storeIds = $category->getStoreIds();
        return $storeIds[1];
    }
    public function microtime_float(){
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
    public function reIndexAll(){
        try {
            $indexer = Mage::getModel('index/indexer')->getProcessByCode('catalog_category_product');
            $indexer->reindexEverything();
        } catch (Exception $e) {
            Mage::log($e->getMessage(),null,'auto_category.log');
        }
    }

    public function flushCache()
    {
        Mage::app()->getCacheInstance()->flush();
        Mage::app()->cleanCache();
    }
    public function recursive_array_search($array, $currentKey) {
        $result = array();
        foreach($array as $key=>$value) {
            if (is_array($value)) {
                $result = array_merge($result,$this->recursive_array_search($value,$currentKey));
            } else {
                if($key==$currentKey && $value!=null && $value!='attribute_set_id' && $value!='type_id'){
                    $result[] = $value;
                }
            }
        }

        return $result;
    }
    public function sendMail($message,$categoryId){
        $receiverArray = Mage::getStoreConfig('autocategory/debug/email');
        $reveivers = explode(',', $receiverArray);

        $category = Mage::getModel('catalog/category')->load($categoryId);
        $coll = $category->getResourceCollection();
        $pathIds = $category->getPathIds();
        $coll->addAttributeToSelect('name');
        $coll->addAttributeToFilter('entity_id', array('in' => $pathIds));
        $result = '';
        foreach ($coll as $cat) {
            $result .= $cat->getName().'/';
        }

        $html = 'Category link: '.$result;
        $html .= '<br />Category ID: '.$categoryId;
        $html .= '<br />'.$message;

        $mail = new Zend_Mail('UTF-8');
        $mail->setBodyHtml($html);
        $mail->setFrom('info@commercers.com');

        $mail->setSubject('Commercers Autocategory Error Notification');

        foreach($reveivers as $reveiver){
            $mail->addTo($reveiver,$reveiver);
            try{
                $mail->send();
            }catch (Exception $e){
                Mage::log($e->getMessage(),null,'auto_category.log');
            }
        }
    }
}