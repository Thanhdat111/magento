<?php

namespace Commercers\AutoProductRelation\Model\Services;
use Magento\Backend\App\Action;
use Commercers\AutoProductRelation\Model\ResourceModel\Rule\CollectionFactory;
use Commercers\AutoProductRelation\Model\Services\ProductRepositoryService;
class AutoLinkeds extends \Magento\Framework\App\Action\Action {
   const MAXIMUM_LIMIT_ITEM = 'section_cross_sell/group_commercers_auto_product_relation_general/enable_auto_product_relation_general_maximum_cross_selling_items';
   const MAXIMUM_LIMIT_CRON = 'section_cross_sell/group_commercers_auto_product_relation_general/enable_auto_product_relation_general_maximum_items_per_cron';

    protected $builderTable;
    protected $productRepositoryService;
    protected $autoRelationProcessLog;
    protected $resourceConnection;
    protected $logger;
    protected $emailLog;
    protected $scopeConfig;
    protected $messages = array();
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        CollectionFactory $collectionFactory,
        ProductRepositoryService $productRepositoryService,
        \Commercers\AutoProductRelation\Model\Rule\Condition\Sql\Builder $sqlBuilder,
        \Commercers\AutoProductRelation\Service\Indexer\BuildTable $builderTable,
        \Commercers\AutoProductRelation\Model\ResourceModel\CvAutoRelationIndex\CollectionFactory $productCollectionFactory,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Commercers\AutoProductRelation\Logger\Logger $logger,
        \Commercers\AutoProductRelation\Helper\EmailLog $emailLog,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ){
        $this->_collectionFactory = $collectionFactory;
        $this->productRepositoryService = $productRepositoryService;
        $this->sqlBuilder = $sqlBuilder;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->builderTable = $builderTable;
        $this->resourceConnection = $resourceConnection;
        $this->logger = $logger;
        $this->emailLog = $emailLog;
        $this->scopeConfig = $scopeConfig;
        
        parent::__construct($context);
        
    }

    public function execute(){
        $ruleProducts = array();
        $conditionProductIds = array();
        $actionProductIds = array();
        $rules = $this->_collectionFactory->create()->setOrder('sort_order', 'DESC');
        foreach ($rules as $rule) {
            if($rule->getIsActive() == 1){
                $ruleId[] = $rule->getId();
                $conditionProductIds[] =  $this->getConditionsProductIds($rule);
                $actionProductIds[] = $this->getActionProductIds($rule);
            }
        }
        for($i = 0; $i < count($conditionProductIds);$i++){
            $ruleProducts[] = array('rule_id' =>$ruleId[$i],'conditions'=>$conditionProductIds[$i],'actions'=>$actionProductIds[$i]);
        }
       $this->productRepositoryService->execute($ruleProducts);
        if( $this->messages){
            $this->emailLog->sendEmail( $this->messages);
        }
    }
    public function getConditionsProductIds($rule) {
        $messageLog = '';
        $products = $this->productCollectionFactory->create();
        //$products->addAttributeToSelect('*');
        $conditions = $rule->getConditions();
        $this->sqlBuilder->attachConditionToCollection($products, $conditions);
        if ($products->getSize() == 0) {
            $arrayCondition = $rule->getConditions()->asArray();
            if (isset($arrayCondition['conditions'])) {
                foreach ($arrayCondition['conditions'] as $value) {
                    $attributes = $value['attribute'];
                    if($attributes == 'features_bags'){
                        $attributes = 'Features';
                    }
                    $attribute[] = $attributes;
                }
                $attrError = implode(",", $attribute);
                $messageLog = 'Rule ID: ' . $rule->getRuleId() . '-"Apply To Products":Can not find the products that meet all the conditions for ' . $attrError;                   
                $this->messages[] = $messageLog;
            }
        }
        if ($products->getData()) {
            $limitMaximum = $this->scopeConfig->getValue(self::MAXIMUM_LIMIT_CRON, \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $products->getSelect()->limit($limitMaximum);           
            $products->getSelect()->reset(\Zend\Db\Sql\Select::COLUMNS);
            $insertQuery = $products->getSelect()->columns('entity_id');
            $connection = $this->resourceConnection->getConnection();
            $productIds = $connection->fetchAll($insertQuery);
            return $productIds;
        } else {
            if ($messageLog) {
                $this->logger->info($messageLog);
            }
            return false;
        }
    }

    public function getActionProductIds($rule) {
        $messageLog = '';
        $products = $this->productCollectionFactory->create();
        $actions = $rule->getActions();
        $arrayAction = $rule->getActions()->asArray();
        $priorityBy = $rule->getPriorityBy();
        $this->sqlBuilder->attachConditionToCollection($products, $actions);
        if ($products->getSize() == 0) {
            $arrayAction = $rule->getActions()->asArray();
            if (isset($arrayAction['conditions'])) {
                foreach ($arrayAction['conditions'] as $value) {
                    $attributes = $value['attribute'];
                    if($attributes == 'features_bags'){
                        $attributes = 'Features';
                    }
                    $attribute[] = $attributes;
                }
                $attrError = implode(",", $attribute);
                $messageLog = 'Rule ID: ' . $rule->getRuleId() . '-"Generating cross Selling Condition": Can not find the products that meet all the conditions for ' . $attrError;
                $this->messages[] = $messageLog;
            }
        }
        if ($products->getData()) {
            if ($priorityBy == 1 || $priorityBy == 3) {
                $products->getSelect()->order('qty_ordered DESC');
            }
            if ($priorityBy == 2) {
                $products->getSelect()->order('qty DESC');
            }
            $limitMaximum = $this->scopeConfig->getValue(self::MAXIMUM_LIMIT_ITEM, \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $limit = $rule->getMaxMatchedItems();
            if($limit > $limitMaximum){
                $limit = $limitMaximum;
            }
            $products->getSelect()->limit($limit);
            $products->getSelect()->reset(\Zend\Db\Sql\Select::COLUMNS);
            $insertQuery = $products->getSelect()->columns('entity_id');
            $connection = $this->resourceConnection->getConnection();
            $productIds = $connection->fetchAll($insertQuery);
            return $productIds;
        }  else {
            if ($messageLog) {
                $this->logger->info($messageLog);
            }
            return false;
        }
    }
}
