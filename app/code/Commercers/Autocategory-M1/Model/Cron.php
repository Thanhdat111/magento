<?php
/**
 * Created by PhpStorm.
 * User: niken
 * Date: 6/11/16
 * Time: 11:28
 */
class Commercers_Autocategory_Model_Cron {

    public function assignProduct(){
        $helper = Mage::helper('commercers_autocategory');

        $maxitems = Mage::getStoreConfig('autocategory/general/maxitems');
        $rules = Mage::getModel('autocategory/rule')->getCollection();
        $rules->getSelect()
            ->where('is_enabled=1')
            ->order(array('last_update ASC'))
            ->limit($maxitems);
       
        foreach ($rules as $rule){
            //$time_start = $helper->microtime_float();
            
            $categoryId = $rule->getCategoryId();
            //check category
            $category = Mage::getModel('catalog/category')->load($categoryId);
            if((int)$category->getId() <= 0){
              continue;
            }
            
            //var_dump($categoryId);exit;
            $productIds = array();
            $isFlatEnable = Mage::getStoreConfig('autocategory/general/flattable');

            $attr = unserialize($rule->getConditionsSerialized());
            $conditions = $rule->getConditions();
            $where = $conditions->prepareConditionSql();

            $qtyCondition = $rule->getStockQty();

            $readConnection = Mage::getSingleton('core/resource')->getConnection('core_read');

            if($isFlatEnable){
                $storeId = $helper->getStoreIds($categoryId);
                $cpfTable = Mage::getSingleton('core/resource')->getTableName('catalog/product_flat').'_'.$storeId ;

                $query = $readConnection->select('*')
                    ->from(
                        array('cpf'=>$cpfTable)
                    )
                    ->where(new Zend_Db_Expr($where))
                ;

                // Check qty condition
                if(!is_null($qtyCondition)){
                    $query->join(
                        array('cataloginventory_stock_item'),
                        'cpf.entity_id=cataloginventory_stock_item.product_id'
                    )
                        ->where('qty '.$qtyCondition)
                    ;
                }
                if(!$rule->getRuleUpdate()){
                    $query->where('entity_id > '.$rule->getLastIndex());
                }
                //echo $query; exit;
//            $time_end = $helper->microtime_float();
//            $time = $time_end - $time_start;
//            echo 'Time: '.$time;exit;

            } else {
                //$time_start = $helper->microtime_float();
                $queryAllAttribute = $readConnection
                    ->select()
                    ->from(
                        array('ce'=>'catalog_product_entity'),
                        array('ce.entity_id','ce.attribute_set_id','ce.type_id')
                    )
                    ->joinLeft(
                        array('ea'=>'eav_attribute'),
                        'ce.entity_type_id = ea.entity_type_id',
                        array('attribute_id'=>'ea.attribute_id','attribute_code'=>'ea.attribute_code')
                    )
                    ->columns(
                        array(
                            'value' => new Zend_Db_Expr('(CASE ea.backend_type
                           WHEN \'varchar\' THEN ce_varchar.value
                           WHEN \'int\' THEN ce_int.value
                           WHEN \'text\' THEN ce_text.value
                           WHEN \'decimal\' THEN ce_decimal.value
                           WHEN \'datetime\' THEN ce_datetime.value
                           ELSE ea.backend_type END)'),
                        )
                    )
                    ->joinLeft(
                        array('ce_varchar'=>'catalog_product_entity_varchar'),
                        'ce.entity_id = ce_varchar.entity_id
                        AND ea.attribute_id = ce_varchar.attribute_id
                        AND ea.backend_type = \'varchar\'',
                        array()
                    )
                    ->joinLeft(
                        array('ce_int'=>'catalog_product_entity_int'),
                        'ce.entity_id = ce_int.entity_id
                        AND ea.attribute_id = ce_int.attribute_id
                        AND ea.backend_type = \'int\'',
                        array()
                    )
                    ->joinLeft(
                        array('ce_text'=>'catalog_product_entity_text'),
                        'ce.entity_id = ce_text.entity_id
                        AND ea.attribute_id = ce_text.attribute_id
                        AND ea.backend_type = \'text\'',
                        array()
                    )
                    ->joinLeft(
                        array('ce_decimal'=>'catalog_product_entity_decimal'),
                        'ce.entity_id = ce_decimal.entity_id
                        AND ea.attribute_id = ce_decimal.attribute_id
                        AND ea.backend_type = \'decimal\'',
                        array()
                    )
                    ->joinLeft(
                        array('ce_datetime'=>'catalog_product_entity_datetime'),
                        'ce.entity_id = ce_datetime.entity_id
                        AND ea.attribute_id = ce_datetime.attribute_id
                        AND ea.backend_type = \'datetime\'',
                        array()
                    )
                ;
                // Check qty condition
                if(!is_null($qtyCondition)){
                    $queryAllAttribute->join(
                        array('cataloginventory_stock_item'),
                        'ce.entity_id=cataloginventory_stock_item.product_id'
                    )
                        ->where('qty '.$qtyCondition)
                    ;
                }
                if(!$rule->getRuleUpdate()){
                    $queryAllAttribute->where('ce.entity_id > '.$rule->getLastIndex());
                }
                $rowAttributes = $helper->recursive_array_search($attr,'attribute');
                $rowAttributes = array_unique($rowAttributes);

                $rowValues = '';
                foreach ($rowAttributes as $rowAttribute){
                    $rowValues .= 'MAX(IF(attribute_code = \''.$rowAttribute.'\', value, NULL)) AS `'.$rowAttribute.'`,';
                }
                $rowValues = substr($rowValues, 0, -1);
                $queryRowtoColumn = 'SELECT entity_id,attribute_set_id,type_id';
                $queryRowtoColumn .= ($rowValues=='')? '': ',';
                $queryRowtoColumn .= $rowValues.' FROM ('.$queryAllAttribute.') AS flat GROUP BY entity_id';

                $query = 'SELECT * FROM ('.$queryRowtoColumn.') AS cpf';
                $query .= ' WHERE '.$where;
                //echo $query;exit;

            }
            //Mage::log($query, null, 'comvn_debug_auto_category_query.log', true);
            try {
                $results = $readConnection->fetchAll($query);
            } catch (Exception $e){
                $helper->sendMail($e->getMessage(),$categoryId);
                continue;
            }
            
            foreach($results as $result){
               // Mage::log($result['entity_id'], null, 'comvn_debug_auto_category_query.log', true);
                $productIds[] = $result['entity_id'];
            }

            // Delete old records

            $writeConnection = Mage::getSingleton('core/resource')->getConnection('core_write');
            if($rule->getRuleUpdate()) {
                $delQuery = 'DELETE FROM `catalog_category_product` WHERE category_id =' . $categoryId;
                $writeConnection->query($delQuery);
            }
            // Insert new products
            $items = count($productIds);
            $i = 1; $lastIndex = 0;

            if($items){
                //Enable Category
                $writeConnection->query("UPDATE `catalog_category_entity_int` SET `value`=1 WHERE (`attribute_id`=42 AND `entity_id`=$categoryId)");
                foreach ($productIds as $productId){

                    $writeConnection->query("REPLACE INTO `catalog_category_product` (`category_id`, `product_id`, `position`) VALUES ($categoryId, $productId, 0)");
                    if($i === $items){
                        $lastIndex = $productId;
                    }
                    $i++;
                }
            } else {

                if(!$rule->getLastIndex()) {
                    //Disable Category

                    $writeConnection->query("UPDATE `catalog_category_entity_int` SET `value`=0 WHERE (`attribute_id`=42 AND `entity_id`=$categoryId)");
                }
                $lastIndex = $rule->getLastIndex();
            }
            //Update log
            $helper->updateLog($categoryId,$rule,$lastIndex);
        }

        // Flush cache and Reindex
        $helper->reIndexAll();
        $helper->flushCache();
    }

}
