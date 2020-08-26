<?php
namespace Commercers\WarehouseManagement\Controller\Adminhtml\Warehouse;
use Magento\Backend\App\Action;
class SaveLinkingProduct extends \Magento\Framework\App\Action\Action {
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Commercers\WarehouseManagement\Model\RowWarehouseFactory $rowWarehouseFactory,
        \Commercers\WarehouseManagement\Model\ProductWarehouseLinkingFactory $productWarehouseLinkingFactory,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistryInterface,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory

    ){
        $this->rowWarehouseFactory = $rowWarehouseFactory;
        $this->productWarehouseLinkingFactory = $productWarehouseLinkingFactory;
        $this->stockRegistryInterface = $stockRegistryInterface;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    public function execute() {
        $result = $this->resultJsonFactory->create();
        $productId = $this->getRequest()->getParam('productId');
        $arrayValues = $this->getArrayValueForm();
        if(is_string($arrayValues)){
           return $result->setData(array('message' => $arrayValues));
        }
        $productWarehouseLinkingFactory = $this->productWarehouseLinkingFactory->create();
        $productWarehouseLinkingCollection = $productWarehouseLinkingFactory->getCollection();
        $productWarehouseLinkings = $productWarehouseLinkingCollection->addFieldToFilter('product_id', array('eq' => $productId));
        if ($productWarehouseLinkings->getSize()) {
            foreach ($productWarehouseLinkings as $productWarehouseLinking) {
                $rowWahouseFactory = $this->rowWarehouseFactory->create();
                $rowWahouse = $rowWahouseFactory->load($productWarehouseLinking->getId());
                if ($rowWahouse)
                    $rowWahouse->delete();
            }
            $productWarehouseLinkings->walk('delete');
        }
        $quantityUpdate = null; 
        foreach ($arrayValues as $arrayValue) {
            $quantityUpdate += $arrayValue['quantity'];
            $productWarehouseLinkingFactory = $this->productWarehouseLinkingFactory->create();
            $rowWahouseFactory = $this->rowWarehouseFactory->create();
            $productWarehouseLinkingFactory->addData([
                'product_id' => $productId,
                'area_id' => $arrayValue['area_id'],
                'quantity' => $arrayValue['quantity'],
                'priority' => $arrayValue['priority'],
            ])->save();
            $productLinkingId = $productWarehouseLinkingFactory->getId();
            $rowWahouseFactory->addData([
                'linking_id' => $productLinkingId,
                'rack_row' => $arrayValue['rack_row'],
                'rack_level' => $arrayValue['rack_level'],
            ])->save();
        }
        try {
            $stockItem = $this->stockRegistryInterface->getStockItem($productId);
            if($quantityUpdate)
            $stockItem->setQty($quantityUpdate)->save();
        } catch (Exception $ex) {
            
        }

        return $result->setData(array('message' => 'Saved','qty'=>$quantityUpdate));
    }

    protected function getArrayValueForm() {
        $result = $this->resultJsonFactory->create();
        $params = $this->getRequest()->getParams();
        $arrayValue = array();
        foreach ($params['warehouse'] as $key => $valueWarehouse) {
            if($valueWarehouse == null){
                return $message = 'Please select warehouse';
            }
            $arrayValue[$key]['warehouse'] = $valueWarehouse;
        }
        foreach ($params['area'] as $key => $valueArea) {
            if($valueArea == null){
                return $message = 'Please select area';
            }
            $arrayValueArea = explode(',', $valueArea);
            $area = $arrayValueArea[0];
            $idArea = $arrayValueArea[1];
            $arrayValue[$key]['area_id'] = $idArea;
            $arrayValue[$key]['area'] = $area;
        }
        foreach ($params['rackRow'] as $key => $valueRackRow) {
            if($valueRackRow == null){
                return $message = 'Please enter rack row';
            }
            $arrayValue[$key]['rack_row'] = $valueRackRow;
        }
        foreach ($params['rackLevel'] as $key => $valueRackLevel) {
            if($valueRackLevel == null){
                return $message = 'Please enter rack level';
            }
            $arrayValue[$key]['rack_level'] = $valueRackLevel;
        }
        foreach ($params['quantity'] as $key => $valueQuantity) {
            if($valueQuantity == null){
                return $message = 'Please enter quantity';
            }
            $arrayValue[$key]['quantity'] = $valueQuantity;
        }
        foreach ($params['priority'] as $key => $valuePriority) {
            $arrayValue[$key]['priority'] = $valuePriority;
        }
        return $arrayValue;
    }

}
