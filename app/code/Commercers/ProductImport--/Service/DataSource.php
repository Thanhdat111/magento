<?php

namespace Commercers\ProductImport\Service;

class DataSource {
    
    public function __construct(
            \Magento\Catalog\Model\Product\Action $productAction,
            \Magento\Catalog\Model\ProductFactory $productFactory,
            \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollection,
            \Magento\Eav\Model\Config $eavConfig,
            \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
            ) {
        
        $this->productAction = $productAction;
        $this->productFactory = $productFactory;
        $this->productCollection = $productCollection;
        $this->_eavConfig = $eavConfig;
        $this->stockRegistry = $stockRegistry;
    }

    protected $_specialAttributes = array('website_id', 'attribute_set_id','category_id');
    

    
    public function execute($data){
        
        $items = $data['items']['item'];
        
        $proceededSkus = array(
            'new' => array(),
            'updated' => array(),
            'errors' => array()
        );
        
        
        foreach($items as $item){
            //print_r($item);exit;
            $storeId = isset($item['store_id']) ? $item['store_id'] : 0;
            $product = $this->productFactory->create();
            
            $addNew = isset($item['add_new']) ? $item['add_new'] : true;            
            $identifyAttributeCode = isset($item['identify_attribute']) ? $item['identify_attribute'] : 'sku';
                        
            if(isset($item[$identifyAttributeCode])){
                
                $indentifyValue = $item[$identifyAttributeCode];
                
                $poductId = $this->findAProductByIdentify($identifyAttributeCode, $item[$identifyAttributeCode]);
                
                if($poductId){
                    
                    try{     
                        $product->load($poductId);
                
                        if(isset($item['qty'])){
                            $qty = $item['qty'];
                            $stockItem = $this->stockRegistry->getStockItemBySku($product->getSku());
                            $stockItem->setQty($qty);
                            $stockItem->setIsInStock((bool)$qty); // this line
                            $this->stockRegistry->updateStockItemBySku($product->getSku(), $stockItem);
                            $proceededSkus['updated'][] = $indentifyValue;
                            
                        }
                        /*
                        echo $poductId;
                        echo $product->getSku();
                        echo '<pre>';print_r($item);exit;
                         * 
                         */
                        //unset($item['sku']);
                        unset($item[$identifyAttributeCode]);
                        
                        foreach($item as $key => $value){
                            if(!in_array($poductId, $this->_specialAttributes)){

                                if(!$this->isProductAttributeExists($key)){

                                    unset($item[$key]);
                                }
                            }

                        }
                        
                        //echo '<pre>'; print_r($item); exit;
                        if(count($items)){
                            $this->productAction->updateAttributes(array($product->getId()), $item, $storeId);
                            $proceededSkus['updated'][] = $indentifyValue;
                        }
                            
                        
                        

                    } catch (Exception $ex) {
                        $proceededSkus['error'][$indentifyValue] =$ex->getMessage();
                    }
                }else{
                    if($addNew == true){
                        $product->save();
                        $product->setData($item)->save();
                        $proceededSkus['new'][] = $sku;
                    }
                }
                
            }else{
                $proceededSkus['error'][] = 'Can not find identify attribtue value';
            }                        
            
        }
        return $proceededSkus;
        
    }
    
    protected function findAProductByIdentify($attributeCode, $value){
        $collection = $this->productCollection->create();
        $collection->addAttributeToFilter($attributeCode, $value);
        if($collection->getSize()){
            return $collection->getFirstItem()->getId();
        }
        return false;
    }


    protected function isProductAttributeExists($field)
    {
        try{
            $attr = $this->_eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $field);
 
            return ($attr && $attr->getId()) ? true : false;
        } catch (Exception $ex) {
            return false;
        }
        
    }
}
