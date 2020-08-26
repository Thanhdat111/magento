<?php

namespace Commercers\WarehouseManagement\Block\Adminhtml\Product\Edit\Tab;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Commercers\WarehouseManagement\Model\WarehouseFactory;
use Commercers\WarehouseManagement\Model\AreaWarehouseFactory;

class Warehouse extends \Magento\Framework\View\Element\Template
{
   protected $_template = 'catalog/product/edit/tab/warehouse.phtml';

    protected $_coreRegistry = null;

    public function __construct(
        Context $context,
        UrlInterface $urlBuilder,
        Registry $registry,
        WarehouseFactory $warehouseFactory,
        AreaWarehouseFactory $areaWarehouseFactory,
        \Magento\Framework\App\ResourceConnection $resource,
        array $data = []
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->_coreRegistry = $registry;
        $this->warehouseFactory = $warehouseFactory;
        $this->areaWarehouseFactory = $areaWarehouseFactory;
        $this->resource = $resource;
        parent::__construct($context, $data);
        $this->_assetRepository = $context->getAssetRepository();
    }
        public function getWarehouseUrl(){
        
        $url = array(
            'get_add_product_linking' => $this->getUrl('backend/warehouse/addproductlinking'),
            'get_select_area' => $this->getUrl('backend/warehouse/selectarea'),
            'get_save_product_linking' => $this->getUrl('backend/warehouse/savelinkingproduct')
            );
        
        
        return json_encode($url);
        
    }
    public function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }
    public function getAddAjax()
    {
        return $this->urlBuilder->getUrl('backend/warehouse/addproductlinking');
    }
    public function getProductLinking($productId)
    {
        $resource =  $this->resource;
        $connection = $resource->getConnection();
        $indexerTable = $resource->getTableName('commercers_product_warehouse_linking');
        $warehouseAreaTable = $resource->getTableName('commercers_warehouse_area');
        $warehouseRowTable = $resource->getTableName('commercers_warehouse_row');
        $sql = "SELECT * FROM {$indexerTable} as mainTable
                        LEFT JOIN {$warehouseAreaTable} as warehoueArea  ON mainTable.area_id = warehoueArea.area_id
                        LEFT JOIN {$warehouseRowTable} as warehoueRow  ON mainTable.id = warehoueRow.linking_id
                        WHERE mainTable.product_id = {$productId} ";
        $ProductLinking = $connection->fetchAll($sql);
        return $ProductLinking;
    }
    public function getWarehouse($id)
    {
        return $this->warehouseFactory->create()->load($id);
    }
    public function getSelelctWarehouse()
    {
        $allWarehouse =  $this->warehouseFactory->create()->getCollection();
        $returnValue = array();
        foreach ($allWarehouse as $warehouse){
            if($warehouse->getId() != 1)
            $returnValue[] = [
                'warehouse_id' => $warehouse->getWarehouseId(),
                'warehouse_name' => $warehouse->getName()
            ];
        }
        return json_encode($returnValue);
    }
    public function getAllWarehouse()
    {
        $allWarehouse =  $this->warehouseFactory->create()->getCollection();
        return $allWarehouse;
    }

    public function getAreaWarehouse($warehouseId)
    {
        $areaWarehouseCollection = $this->areaWarehouseFactory->create()->getCollection();
        $areaWarehouse = $areaWarehouseCollection->addFieldToFilter('warehouse_id',array('eq'=>$warehouseId));
        return $areaWarehouse;
    }
    public function getJsWarehouseProductTab()
    {
        $assetRepository = $this->_assetRepository;
        $asset = $assetRepository->createAsset('Commercers_WarehouseManagement::js/warehouseProductTab.js');
        $url = $asset->getUrl();
        return $url;
    }
}