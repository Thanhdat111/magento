<?php

namespace Commercers\AutoProductRelation\Controller\Adminhtml\Index;
use Magento\Backend\App\Action;
use Commercers\AutoProductRelation\Model\ResourceModel\Rule\CollectionFactory;

class ToanTester extends \Magento\Framework\App\Action\Action {
    protected $catalogProductVisibility;

    public function __construct(
    \Magento\Framework\App\Action\Context $context,
    CollectionFactory $collectionFactory,
    \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
    \Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder,
    \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory        
    ){
        $this->_collectionFactory = $collectionFactory;
        $this->sqlBuilder = $sqlBuilder;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context);
        
    }

    public function execute() {
        $rules = $this->_collectionFactory->create();      
        echo '<pre>';
    foreach ($rules as $rule) {
        $products = $this->productCollectionFactory->create();
        $products->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        //print_r($products->getData());exit;
        $conditions = $rule->getConditions();
        $conditions->collectValidatedAttributes($products);
        
        $this->sqlBuilder->attachConditionToCollection($products, $conditions);
        echo $products->getSelect();
        echo print_r($rule->getConditions()->asArray());
//        foreach ($rule->getConditions() as $condition){
//            echo get_class($condition);
//            
//        }
    }
}
    

}
