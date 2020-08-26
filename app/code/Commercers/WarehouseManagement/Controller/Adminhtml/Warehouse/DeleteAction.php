<?php

namespace Commercers\WarehouseManagement\Controller\Adminhtml\Warehouse;

class DeleteAction extends \Commercers\WarehouseManagement\Controller\Adminhtml\Warehouses {

    /**
     * @return void
     */
    public function execute() {
        $id = (int) $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $warehouse = $this->warehouseFactory->create()->load($id);
                if($warehouse->getId()){
                    $warehouse->delete();
                    $this->messageManager->addSuccess(__('The warehouse has been deleted.'));
                }else{
                    $this->messageManager->addError(__('This warehouse no longer exists.'));
                }

                $areaWarehouseCollection = $this->areaWarehouseFactory->create()->getCollection();
                $areaWarehouse = $areaWarehouseCollection->addFieldToFilter('warehouse_id', array('eq' => $id));
                if ($areaWarehouse->getSize()) {
                    $areaWarehouse->walk('delete');
                }

                // Redirect to grid page
                $this->_redirect('*/*/');
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_redirect('*/*/edit', ['id' => $id]);
            }
        }
    }

}
