<?php

namespace Commercers\WarehouseManagement\Controller\Adminhtml\Warehouse;
class Save extends \Commercers\WarehouseManagement\Controller\Adminhtml\Warehouses
{
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $params = $this->getRequest()->getPostValue();
        if($params)
        {
            try{
                if($params['warehouse_id']){
                    $model = $this->warehouseFactory->create()->load($params['warehouse_id']);
                }else{
                     $model = $this->warehouseFactory->create();
                }
                $model->addData([
                        'name'=> $params['name'],
                        'address'=>$params['address'],
                        'description'=>$params['description'],
                        ])->save();
                if(isset($params['warehouse_dynamic_row'])){
                    $areaWarehouseCollection = $this->areaWarehouseFactory->create()->getCollection();
                    $areaWarehouse = $areaWarehouseCollection->addFieldToFilter('warehouse_id',array('eq'=>$model->getId()));
                    if($areaWarehouse->getSize()){
                            $areaWarehouse->walk('delete');
                    }
                    foreach($params['warehouse_dynamic_row'] as $areaData){
                        $areaWarehouse = $this->areaWarehouseFactory->create();
                        $areaWarehouse->addData([
                            'warehouse_id' =>$model->getId(),
                            'area' => $areaData['area'],
                            'record_id' => $areaData['record_id'],
                        ])->save();
                    }
                }
                $this->messageManager->addSuccess(__('Successfully saved the item.'));
            }
            catch(\Exception $e)
            {
                $this->messageManager->addError($e->getMessage());
            }
        }
         return $resultRedirect->setPath('*/*/index');
    }
}