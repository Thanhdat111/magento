<?php
namespace Commercers\WarehouseManagement\Controller\Adminhtml\Warehouse\ReBooking;
use Magento\Backend\App\Action;
class Save extends \Magento\Framework\App\Action\Action {
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Commercers\WarehouseManagement\Model\ProductWarehouseLinkingFactory $productWarehouseLinkingFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory

    ){
        $this->productWarehouseLinkingFactory = $productWarehouseLinkingFactory;
        $this->messageManager = $messageManager;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    public function execute() {
        $result = $this->resultJsonFactory->create();
        $resultRedirect = $this->resultRedirectFactory->create();
        $productId = $this->getRequest()->getParam('productId');
        $params = $this->getArrayValueForm();
        foreach($params as $param){
            //Save Area Warehouse
            $productWarehouseLinkingCollection = $this->productWarehouseLinkingFactory->create()->getCollection();
            $productWarehouseLinkingCollection->addFieldToFilter('product_id',array('eq'=>$productId))
                                    ->addFieldToFilter('area_id',array('eq'=>$param['areaId']));
             $productWarehouseLinking = $productWarehouseLinkingCollection->getFirstItem();
            if($productWarehouseLinking){
                $qtyOld = $productWarehouseLinking->getQuantity();
                $qtyNew = $qtyOld + $param['qty'];
                $productWarehouseLinking->setQuantity($qtyNew)->save();

                //Save Receiving Location
                $productWarehouseLinkingCollection = $this->productWarehouseLinkingFactory->create()->getCollection();
                $productWarehouseLinkingCollection->addFieldToFilter('product_id',array('eq'=>$productId))
                                        ->addFieldToFilter('area_id',array('eq'=>1));
                $receivingLocation = $productWarehouseLinkingCollection->getFirstItem();
                if($receivingLocation){
                    $qtyOld = $receivingLocation->getQuantity();
                    $qtyNew = $qtyOld - $param['qty'];
                    $receivingLocation->setQuantity($qtyNew)->save();                    
                }
            }
        }
        $this->messageManager->addSuccess(__('Successfully transferred'));
        return $resultRedirect->setPath('*/*/rebooking');
    }
    protected function getArrayValueForm(){
        $params = $this->getRequest()->getParams();
        foreach ($params['areaId'] as $key => $value){
            $arrayValue[$key]['areaId'] = $value;
        }
        foreach ($params['qty'] as $key => $value){
            $arrayValue[$key]['qty'] = $value;
        }
        return $arrayValue;
    }

}
