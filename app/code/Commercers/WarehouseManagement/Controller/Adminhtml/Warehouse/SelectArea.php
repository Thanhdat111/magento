<?php
namespace Commercers\WarehouseManagement\Controller\Adminhtml\Warehouse;
use Magento\Backend\App\Action;
use Commercers\WarehouseManagement\Model\AreaWarehouseFactory;

class SelectArea extends \Magento\Framework\App\Action\Action {
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        AreaWarehouseFactory $areaWarehouseFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory

    ){
        $this->areaWarehouseFactory = $areaWarehouseFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    public function execute() {
        $result = $this->resultJsonFactory->create();
        $valueOption = $this->getRequest()->getParam('valueOption');
        $areaWarehouseFactory =  $this->areaWarehouseFactory->create()->getCollection();
        $areaWarehouseCollection = $areaWarehouseFactory->addFieldToFilter('warehouse_id',array('eq'=>$valueOption));
        $resultPage = $this->resultPageFactory->create();
        $block = $resultPage->getLayout()
                    ->createBlock('Commercers\WarehouseManagement\Block\Adminhtml\Product\Edit\Tab\SelectArea')
                    ->setTemplate('Commercers_WarehouseManagement::catalog/product/edit/tab/selectarea.phtml')
                    ->setAreaWarehouse($areaWarehouseCollection)
                    ->toHtml();
            return $result->setData(array('block' => $block));
        }
    }
