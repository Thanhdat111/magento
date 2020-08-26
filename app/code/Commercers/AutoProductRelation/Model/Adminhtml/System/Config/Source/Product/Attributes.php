<?php
namespace Commercers\AutoProductRelation\Model\Adminhtml\System\Config\Source\Product;

class Attributes {
	protected $productAttributeCollectionFactory;
	
	public function __construct(
		\ Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $productAttributeCollectionFactory
	){
			$this->productAttributeCollectionFactory = $productAttributeCollectionFactory;
	}
	public function toOptionArray(){
		$collection = $this->productAttributeCollectionFactory->create();     
		$attributes = array();
		foreach ($collection as $item) {
			$attributes[] = ['value' => $item->getData()['attribute_code'], 'label' => $item->getData()['frontend_label']];
        }		
        return $attributes;
	}
}