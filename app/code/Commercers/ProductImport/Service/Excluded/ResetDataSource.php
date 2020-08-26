<?php

namespace Commercers\ProductImport\Service\Excluded;

class ResetDataSource {
    
    protected $connection = false;

    public function __construct(
            \Magento\Catalog\Model\Product\Action $productAction,
            \Magento\Catalog\Model\ProductFactory $productFactory,
            \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
            \Magento\Eav\Model\Config $eavConfig,
            \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
             \Magento\Framework\App\ResourceConnection $resource
            ) {
        
        $this->productAction = $productAction;
        $this->productFactory = $productFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->_eavConfig = $eavConfig;
        $this->stockRegistry = $stockRegistry;
         $this->_resource = $resource;
    }

    protected $_specialAttributes = array('website_id', 'attribute_set_id','category_id');
    

    protected function getConnection()
    {
        if (!$this->connection) {
            $this->connection = $this->_resource->getConnection('core_write');
        }
        return $this->connection;
    }
    
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
            //$product = $this->productFactory->create();
            $identifyAttributeCode = isset($item['identify_attribute']) ? $item['identify_attribute'] : 'sku';
                        
            if( isset( $item[$identifyAttributeCode] ) ) {
                
                $productId = $this->findAProductByIdentify($identifyAttributeCode, $item[$identifyAttributeCode]); 
                if($productId && $item['qty'] > 0 ){
                    $this->updateStockItem($productId, $item['qty']);
                    $logTable = $this->_resource->getTableName('stock_reset_excluded_items_log'); 
                    $this->getConnection()->insertOnDuplicate( $logTable, [ 'product_id' => $productId ] );
                }
            }
        }
        
        $updateStockSql = "

            UPDATE cataloginventory_stock_item SET qty = 0.0000,is_in_stock = 0,stock_status_changed_auto = 1,low_stock_date = NOW()
            WHERE product_id NOT IN ( SELECT product_id FROM stock_reset_excluded_items_log  )

        ";

        $this->getConnection()->query($updateStockSql);

        $updateStockStatusSql = "

            UPDATE cataloginventory_stock_status SET qty = 0.0000,stock_status = 0
            
            WHERE product_id NOT IN ( SELECT product_id FROM stock_reset_excluded_items_log  )

        ";

        $this->getConnection()->query($updateStockStatusSql);

        $truncateLogTable = "TRUNCATE TABLE stock_reset_excluded_items_log";

        $this->getConnection()->query($truncateLogTable);

        //return $proceededSkus;
        
    }

    protected function updateStockItem($productId, $qty){
        $updateStockSql = "

            UPDATE cataloginventory_stock_item SET `qty` = {$qty} ,is_in_stock = 1 ,stock_status_changed_auto = 1
            WHERE product_id = {$productId}

        ";

        $this->getConnection()->query($updateStockSql);

        $updateStockStatusSql = "

            UPDATE cataloginventory_stock_status SET qty =  {$qty} , stock_status = 1
            
            WHERE product_id = {$productId}

        ";

        $this->getConnection()->query($updateStockStatusSql);

        
    }
    
    protected function findAProductByIdentify($attributeCode, $value){
        $collection = $this->productCollectionFactory->create();
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
