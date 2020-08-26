<?php
namespace Commercers\WarehouseManagement\Controller\Adminhtml\Warehouse\ReBooking;
use Magento\Backend\App\Action;

class LoadWarehouse extends \Magento\Framework\App\Action\Action {
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory

    ){
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->productFactory = $productFactory;
        parent::__construct($context);
    }
    public function execute() {
        $result = $this->resultJsonFactory->create();
        $params = $this->getRequest()->getParams();
        if ($params['isAjax']) {
            $resultPage = $this->resultPageFactory->create();
            $product = $this->productFactory->create()->loadByAttribute('sku', $params['sku']);
            if ($product != false) {
                $template = $resultPage->getLayout()
                        ->createBlock('Commercers\WarehouseManagement\Block\Adminhtml\ReBooking\LoadWarehouse')
                        ->setTemplate('Commercers_WarehouseManagement::rebooking/loadwarehouse.phtml')
                        ->setProduct($product)
                        ->toHtml();
                return $result->setData(array('template' => $template));
            }
            return $result->setData(array('error' => 'SKU is not empty'));
        }
        return false;
    }

}
