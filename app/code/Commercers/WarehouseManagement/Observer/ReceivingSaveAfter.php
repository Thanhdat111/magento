<?php
namespace Commercers\WarehouseManagement\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ObjectManager;

class ReceivingSaveAfter implements ObserverInterface
{
    const TYPE_RECEIVING = 2;
    public function __construct(
       \Magento\Sales\Api\Data\OrderInterface $order,
       \Magento\Framework\App\ResourceConnection $resource,
       \Magento\Catalog\Model\ProductFactory $productFactory,
       \Commercers\WarehouseManagement\Model\RowWarehouseFactory $rowWarehouseFactory,
       \Commercers\WarehouseManagement\Model\ProductWarehouseLinkingFactory $productWarehouseLinkingFactory,
       \Commercers\WarehouseManagement\Model\WarehouseLogFactory $warehouseLogFactory

    ) {
        $this->order = $order;
        $this->resource = $resource;
        $this->productFactory = $productFactory;
        $this->productWarehouseLinkingFactory = $productWarehouseLinkingFactory;
        $this->rowWarehouseFactory = $rowWarehouseFactory;
        $this->warehouseLogFactory = $warehouseLogFactory;
    }

    /**
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer) {
        $attr = $observer->getAttr();
        $attrSelect = $observer->getAttrselect();
        $product = $this->productFactory->create()->loadByAttribute($attrSelect, $attr);
        $productId = $product->getId();
        $productWarehouseLinkingFactory = $this->productWarehouseLinkingFactory->create();
        $productWarehouseLinkingCollection = $productWarehouseLinkingFactory->getCollection();
        $WarehouseLinking = $productWarehouseLinkingCollection
                                        ->addFieldToFilter('product_id',array('eq'=>$productId))
                                        ->addFieldToFilter('area_id',array('eq'=> 1))
                                        ->getFirstItem();
        if($WarehouseLinking->getId()){
            $qtyWarehouse = $WarehouseLinking->getQuantity();
            $qtyUpdate = $qtyWarehouse + $observer->getQty();
            $WarehouseLinking->setQuantity($qtyUpdate)->save();
            $this->WriteLog($productId,$qtyWarehouse,$qtyUpdate);
        }else{
            $qtyWarehouse = 0;
            $qtyUpdate = $observer->getQty();
            $productWarehouseLinkingFactory->addData([
                            'product_id' => $productId,
                            'area_id' => 1,
                            'quantity' => $qtyUpdate,
                        ])->save();
            $rowWarehouseFactory = $this->rowWarehouseFactory->create();
            $rowWarehouseFactory->addData([
                            'linking_id' => $productWarehouseLinkingFactory->getId(),
                            'rack_row' => 1,
                            'rack_level'=> 1
                        ])->save();
            $this->WriteLog($productId,$qtyWarehouse,$qtyUpdate);
        }
    }
    protected function WriteLog($productId,$qtyWarehouse,$qtyUpdate){
        $difference = $qtyUpdate - $qtyWarehouse;
        $warehouseLogFactory = $this->warehouseLogFactory->create();
        $warehouseLogFactory->addData([
                    'product_id'=> $productId,
                    'type'=> self::TYPE_RECEIVING,
                    'warehouse_id'=> 1,
                    'qty_before' => $qtyWarehouse,
                    'qty_afterwards'=>  $qtyUpdate,
                    'difference' => $difference,
                    'area_id'=> 1,
                    'rack_row' => 1,
                    'rack_level'=> 1
                ])->save();
    }

}
