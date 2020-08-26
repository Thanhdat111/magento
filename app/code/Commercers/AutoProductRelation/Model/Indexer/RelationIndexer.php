<?php
namespace Commercers\AutoProductRelation\Model\Indexer;

use Commercers\AutoProductRelation\Model\ResourceModel\Rule\CollectionFactory;

class RelationIndexer implements \Magento\Framework\Indexer\ActionInterface, \Magento\Framework\Mview\ActionInterface
{
	protected $builderTable;
	protected $catalogProductVisibility;

	public function __Construct(
		\Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
		\Commercers\AutoProductRelation\Service\Indexer\BuildTable $builderTable,
		CollectionFactory $collectionFactory,
		\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
	){
		$this->catalogProductVisibility = $catalogProductVisibility;
		$this->builderTable = $builderTable;
		$this->_collectionFactory = $collectionFactory;
		$this->productCollectionFactory = $productCollectionFactory;
	}
	/*
	 * Used by mview, allows process indexer in the "Update on schedule" mode
	 */
	public function execute($ids){
		//code here!
	}

	/*
	 * Will take all of the data and reindex
	 * Will run when reindex via command line
	 */
	public function executeFull(){
		//code here!
                $conditionAttributes= array();
                $actionAttributes = array();
		$rules = $this->_collectionFactory->create();      
		foreach ($rules as $rule) {
		    $products = $this->productCollectionFactory->create();
                    //get conditions
                    $conditions = $rule->getConditions();
                    $conditionAttributes[] = $rule->getConditions()->asArray();
                    //get actions
                    $actions = $rule->getActions();
                    $actionAttributes[] = $rule->getActions()->asArray();
                    
            }
            //echo "<pre>";print_r($conditionAttributes);exit;
            if($conditionAttributes || $actionAttributes){
                $this->builderTable->build($conditionAttributes,$actionAttributes);
            }
	}
   
   
	/*
	 * Works with a set of entity changed (may be massaction)
	 */
	public function executeList(array $ids){
		//code here!
	}
   
   
	/*
	 * Works in runtime for a single entity using plugins
	 */
	public function executeRow($id){
		//code here!
	}
}