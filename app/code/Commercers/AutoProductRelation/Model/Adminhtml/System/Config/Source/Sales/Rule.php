<?php
namespace Commercers\AutoProductRelation\Model\Adminhtml\System\Config\Source\Sales;

class Rule {
	protected $saleRuleCollectionFactory;
	
	public function __construct(
		\Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory $saleRuleCollectionFactory
	){
			$this->saleRuleCollectionFactory = $saleRuleCollectionFactory;
	}
//	public function toOptionArray(){
//		$saleRules = $this->saleRuleCollectionFactory->create()->toOptionArray();        
//        return $saleRules;
//	}
	public function toOptionArray(){
		$collection = $this->saleRuleCollectionFactory->create();   
		$saleRules = array();
		foreach($collection as $item){
			$saleRules[] = ['value' => $item->getData()['rule_id'], 'label' => $item->getData()['name']];
		}
        return $saleRules;
	}
}