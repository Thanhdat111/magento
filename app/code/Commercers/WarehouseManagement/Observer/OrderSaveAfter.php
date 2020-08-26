<?php
namespace Commercers\WarehouseManagement\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ObjectManager;

class OrderSaveAfter implements ObserverInterface
{
    public function __construct(
       \Magento\Sales\Api\Data\OrderInterface $order,
       \Magento\Framework\App\ResourceConnection $resource,
       \Commercers\WarehouseManagement\Model\ProductWarehouseLinkingFactory $productWarehouseLinkingFactory,
       \Commercers\WarehouseManagement\Model\WarehouseLogFactory $orderSaveAfterLogFactory

    ) {
        $this->order = $order;
        $this->resource = $resource;
        $this->productWarehouseLinkingFactory = $productWarehouseLinkingFactory;
        $this->orderSaveAfterLogFactory = $orderSaveAfterLogFactory;
    }

    /**
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    const TYPE_ORDER = 1;
    public function execute(\Magento\Framework\Event\Observer $observer) {
        $orderIds = $observer->getEvent()->getOrderIds();
        foreach ($orderIds as $orderId) {
            $order = $this->order->load($orderId);
            foreach ($order->getAllItems() as $item) {
                $productWarehouseLinkingFactory = $this->productWarehouseLinkingFactory->create();
                $productWarehouseLinkingCollection = $productWarehouseLinkingFactory->getCollection();
                $productWarehouseLinkingCollection->addFieldToFilter('product_id', array('eq' => $item->getProductId()));
                $productWarehouseLinkingCollection->getSelect()->WHERE('quantity > 0 AND area_id != 1');
                $productWarehouseLinkings = $productWarehouseLinkingCollection->setOrder('priority', 'DESC');
                foreach ($productWarehouseLinkings as $productWarehouseLinking) {
                    $warehouseProductLinkingId = $productWarehouseLinking->getId();
                    $qtyBefore = $productWarehouseLinking->getQuantity();
                    $orderQty = $item->getQtyOrdered();
                    $productWarehouseLinkingFactory = $this->productWarehouseLinkingFactory->create();
                    $warehouseProductLinking = $productWarehouseLinkingFactory->load($warehouseProductLinkingId);
                    if (isset($surplus)) {
                        if ($surplus > $qtyBefore) {
                            $surplus = $surplus - $qtyBefore;
                            $this->WriteLog($warehouseProductLinkingId,0,-$qtyBefore);
                            $warehouseProductLinking->setQuantity(0)->save();
                            $orderQty = $surplus;
                            continue;
                        } else {
                            $orderQty = $surplus;
                        }
                    }
                    if ($orderQty > $qtyBefore) {
                        $surplus = $orderQty - $qtyBefore;
                        $this->WriteLog($warehouseProductLinkingId,0,-$qtyBefore);
                        $warehouseProductLinking->setQuantity(0)->save();
                        continue;
                    }
                    $qtyAfter = $qtyBefore - $orderQty;
                    $this->WriteLog($warehouseProductLinkingId,$qtyAfter,-$orderQty);
                    $warehouseProductLinking->setQuantity($qtyAfter)->save();
                    return;
                }
            }
        }
    }
    protected function WriteLog($warehouseProductLinkingId,$qtyAfter,$difference){
           $connection = $this->resource;
           $areaWahouse = $connection->getTableName('commercers_warehouse_area');
           $warehouseRowTable = $connection->getTableName('commercers_warehouse_row');
           $orderSaveAfterFactory =  $this->orderSaveAfterLogFactory->create();
           $productWarehouseLinkingFactory = $this->productWarehouseLinkingFactory->create();
           $productWarehouseLinkingCollection = $productWarehouseLinkingFactory->getCollection();
           $productWarehouseLinkingCollection->getSelect()
                                                ->joinLeft(array('row' => $warehouseRowTable),
                                               'main_table.id = row.linking_id ',array('rack_row','rack_level'))
                                                ->joinLeft(array('area' => $areaWahouse),
                                               'main_table.area_id = area.area_id',array('area_id','warehouse_id'));
            $productWarehouseLinkingCollection->getSelect()->WHERE('main_table.id = '.$warehouseProductLinkingId);
            $orderLog = $productWarehouseLinkingCollection->getFirstItem();
            $orderSaveAfterFactory->addData([
                'product_id' => $orderLog->getProductId(),
                'type' => self::TYPE_ORDER,
                'warehouse_id'  => $orderLog->getWarehouseId(),
                'area_id'       => $orderLog->getAreaId(),
                'rack_row'   => $orderLog->getRackRow(),
                'rack_level' => $orderLog->getRackLevel(),
                'qty_before' => $orderLog->getQuantity(),
                'qty_afterwards' => $qtyAfter,
                'difference' => $difference
            ])->save();
    }

}
