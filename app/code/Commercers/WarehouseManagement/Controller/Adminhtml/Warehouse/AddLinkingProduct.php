<?php
namespace Commercers\WarehouseManagement\Controller\Adminhtml\Warehouse;
use Magento\Backend\App\Action;

class AddLinkingProduct extends \Magento\Framework\App\Action\Action {
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory

    ){
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    public function execute() {
        $result = $this->resultJsonFactory->create();
        $resultPage = $this->resultPageFactory->create();
        $block = $resultPage->getLayout()
                    ->createBlock('Commercers\WarehouseManagement\Block\Adminhtml\Product\Edit\Tab\Warehouse')
                    ->setTemplate('Commercers_WarehouseManagement::catalog/product/edit/tab/warehouse.phtml')
                    ->toHtml();
            return $result->setData(array('block' => $block,'message'=>'false'));
        }
    }
