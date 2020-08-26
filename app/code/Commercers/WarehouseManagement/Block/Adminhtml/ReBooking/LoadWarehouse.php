<?php

namespace Commercers\WarehouseManagement\Block\Adminhtml\ReBooking;

class LoadWarehouse extends \Magento\Backend\Block\Template {

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\App\ResourceConnection $resource, 
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Commercers\WarehouseManagement\Model\ProductWarehouseLinkingFactory $productWarehouseLinkingFactory,
        \Commercers\WarehouseManagement\Model\WarehouseFactory $warehouseFactory
    ) {
        $this->resource = $resource;
        $this->scopeConfig = $scopeConfig;
        $this->productWarehouseLinkingFactory = $productWarehouseLinkingFactory;
        $this->warehouseFactory = $warehouseFactory;
        parent::__construct($context);
        $this->_assetRepository = $context->getAssetRepository();
    }

    public function getReceivingLocation($productId){
        $productWarehouseLinkingCollection = $this->productWarehouseLinkingFactory->create()->getCollection();
        $productWarehouseLinkingCollection->addFieldToFilter('area_id',array('eq'=> 1))
                                        ->addFieldToFilter('product_id',array('eq'=> $productId));
        return $productWarehouseLinkingCollection->getFirstItem();
    }
    public function getWarehouseProductLinking($productId)
    {
        $resource =  $this->resource;
        $connection = $resource->getConnection();
        $indexerTable = $resource->getTableName('commercers_product_warehouse_linking');
        $warehouseAreaTable = $resource->getTableName('commercers_warehouse_area');
        $warehouseRowTable = $resource->getTableName('commercers_warehouse_row');
        $sql = "SELECT * FROM {$indexerTable} as mainTable
                        LEFT JOIN {$warehouseAreaTable} as warehoueArea  ON mainTable.area_id = warehoueArea.area_id
                        LEFT JOIN {$warehouseRowTable} as warehoueRow  ON mainTable.id = warehoueRow.linking_id
                        WHERE mainTable.product_id = {$productId} AND mainTable.area_id != 1";
        $ProductLinking = $connection->fetchAll($sql);
        return $ProductLinking;
    }
    public function getWarehouse($id)
    {
        return $this->warehouseFactory->create()->load($id);
    }
    public function getJsLoadWarehouse()
    {
        $assetRepository = $this->_assetRepository;
        $asset = $assetRepository->createAsset('Commercers_WarehouseManagement::js/loadWarehouse.js');
        $url = $asset->getUrl();
        return $url;
    }
    
}
