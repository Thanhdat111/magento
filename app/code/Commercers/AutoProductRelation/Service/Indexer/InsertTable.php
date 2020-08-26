<?php

namespace Commercers\AutoProductRelation\Service\Indexer;
use Magento\Backend\App\Action;
use Commercers\AutoProductRelation\Model\ResourceModel\Rule\CollectionFactory;

class InsertTable extends \Magento\Framework\App\Action\Action {
    const DAYS_FOR_BESTSELLER = 'section_cross_sell/group_commercers_auto_product_relation_general/enable_auto_product_relation_general_day_consider';
    protected $catalogProductVisibility;
    protected $scopeConfig;
    protected $date;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        CollectionFactory $collectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Attribute\Repository $attributeFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ){
        $this->_collectionFactory = $collectionFactory;
        $this->sqlBuilder = $sqlBuilder;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->attributeFactory = $attributeFactory;
        $this->scopeConfig = $scopeConfig;
        $this->date = $date;
        parent::__construct($context);
        
    }

    public function execute(){
        $valueDay = $this->scopeConfig->getValue(self::DAYS_FOR_BESTSELLER, \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
        $today = $this->date->gmtDate();
        $day = date("Y-m-d", strtotime(date("Y-m-d", strtotime($today)) . " - " . $valueDay . " day"));
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $indexerTable = $resource->getTableName('cv_autorelation_index');
        $SalesOrderTable = $resource->getTableName('sales_order_item');
        $cataloginventoryTable = $resource->getTableName('cataloginventory_stock_item');
        $connection->truncateTable($indexerTable); //gives table name with prefix
        //Select Data from table
        //Insert Data into table
        $fields = array();
        $dataField = $connection->describeTable($resource->getTableName('catalog_product_entity'));
        foreach ($dataField as $value) {
            $fields[] = $value['COLUMN_NAME'];
        }
        $conditionAttributes = $this->getConditionAttributes();
        $actionAttributes = $this->getActionAttributes();
        $select = $connection->select();

        $select->from(array('e' => $resource->getTableName('catalog_product_entity')));

        $checkFieldCondition = array();

        $conditionAttributes = array_unique($conditionAttributes);
        $actionAttributes = array_unique($actionAttributes);
        $attributes = array_merge($conditionAttributes, $actionAttributes);
        $attributes = array_diff($attributes, array('attribute_set_id'));
        $attributes = array_unique($attributes);
        //print_r($attributes);exit;
        foreach ($attributes as $attributeCode) {
            if ($attributeCode != 'category_ids') {
                $fields[] = $attributeCode;
                //$productCollection->addAttributeToSelect(array('*'));

                $attribute = $this->attributeFactory->get($attributeCode);
                if ($attribute->getBackend()->getType() == 'static')
                    continue;

                $tableAlias = 'p_' . $attributeCode;
                $fieldCode = $attributeCode;
                $fieldAlias = $tableAlias . '.value';
                $condition = array();
                $condition[] = $connection->quoteInto(
                        $connection->quoteColumnAs("{$tableAlias}.attribute_id", null) . ' = ?', $attribute->getId()
                );
                $condition[] = "e.entity_id = {$tableAlias}.entity_id";

                $condition = $this->joinAttribtue($attribute, $condition, $tableAlias, $fieldAlias, $fieldCode);
                $select->joinLeft(
                        [$tableAlias => $attribute->getBackend()->getTable()], '(' . implode(') AND (', $condition) . ')', [$fieldCode => $fieldAlias]
                );
            }
        }
        $dataField = array_unique($fields);
        $insertQuery = $connection->insertFromSelect($select, $indexerTable, $dataField, \Magento\Framework\DB\Adapter\AdapterInterface::INSERT_ON_DUPLICATE);
        $connection->query($insertQuery);
        $sqlQtyOrder = "Update " . $indexerTable . " maintable ,
                (SELECT product_id ,created_at,SUM(qty_ordered) as qty_ordered FROM " . $SalesOrderTable . " GROUP BY product_id ) as count_orders
                SET maintable.qty_ordered = count_orders.qty_ordered 
                WHERE maintable.entity_id = count_orders.product_id AND count_orders.created_at > ".$day." ";
        $connection->query($sqlQtyOrder);
        $sqlQtyProduct = "Update ".$indexerTable." maintable ,
                (SELECT product_id , qty as qty_product FROM ".$cataloginventoryTable.") as count_qty
                SET maintable.qty = count_qty.qty_product
                WHERE maintable.entity_id = count_qty.product_id";
        $connection->query($sqlQtyProduct);
        
    }

    public function getDefaultStoreId() {
        return \Magento\Store\Model\Store::DEFAULT_STORE_ID;
    }

    protected function joinAttribtue($attribute, &$condition, $tableAlias, $fieldAlias, $fieldCode) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $storeId = 1;

        if ($storeId != $this->getDefaultStoreId() && !$attribute->isScopeGlobal()) {
            /**
             * Add joining default value for not default store
             * if value for store is null - we use default value
             */
            $defCondition = '(' . implode(') AND (', $condition) . ')';
            $defAlias = $tableAlias . '_default';
            $defAlias = $this->getConnection()->getTableName($defAlias);
            $defFieldAlias = str_replace($tableAlias, $defAlias, $fieldAlias);
            $tableAlias = $this->getConnection()->getTableName($tableAlias);

            $defCondition = str_replace($tableAlias, $defAlias, $defCondition);
            $defCondition .= $connection->quoteInto(
                    " AND " . $connection->quoteColumnAs("{$defAlias}.store_id", null) . " = ?", $this->getDefaultStoreId()
            );

            $this->getSelect()->{$method}(
                    [$defAlias => $attribute->getBackend()->getTable()], $defCondition, []
            );

            $method = 'joinLeft';
            $fieldAlias = $this->getConnection()->getCheckSql(
                    "{$tableAlias}.value_id > 0", $fieldAlias, $defFieldAlias
            );
            $this->_joinAttributes[$fieldCode]['condition_alias'] = $fieldAlias;
            $this->_joinAttributes[$fieldCode]['attribute'] = $attribute;
        } else {
            $storeId = $this->getDefaultStoreId();
        }
        $condition[] = $connection->quoteInto(
                $connection->quoteColumnAs("{$tableAlias}.store_id", null) . ' = ?', $storeId
        );
        return $condition;
    }

    public function getConditionAttributes() {
        $rules = $this->_collectionFactory->create();
        $conditionAttributes = array();
        foreach ($rules as $rule) {
            $conditions = $rule->getConditions();
            foreach ($conditions->getConditions() as $condition) {
                $this->getChildConditionAttributes($condition, $conditionAttributes);
            }
        }
        return $conditionAttributes;
    }

    public function getActionAttributes() {
        $rules = $this->_collectionFactory->create();
        $conditionAttributes = array();
        foreach ($rules as $rule) {

            $conditions = $rule->getActions();

            foreach ($conditions->getActions() as $condition) {
                $this->getChildConditionAttributes($condition, $conditionAttributes);
            }
        }
        return $conditionAttributes;
    }

    protected function getChildConditionAttributes($condition, &$conditionAttributes) {

        if (!empty($condition->getAttribute())) {
            $conditionAttributes[] = $condition->getAttribute();
        }
        if ($conditions = $condition->getConditions()) {
            foreach ($conditions as $condition) {

                $this->getChildConditionAttributes($condition, $conditionAttributes);
            }
        }
    }

}
