<?php

namespace Commercers\ProductAdvance\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use \Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Controller\ResultFactory;

class SetSkuProduct extends Action {

    public function __construct(
        Context $context,
        ResultFactory $resultFactory,
        ProductRepository $productRepository

    ) {
        parent::__construct($context);
        $this->resultFactory = $resultFactory;
        $this->_productRepository = $productRepository;
    }

    public function execute()
    {
        $product_id = $this->getRequest()->getParam('product_id');
        $product_sku = $this->getRequest()->getParam('sku');
        $product = $this->_productRepository->getById($product_id);
        $product->setSku($product_sku);
        //print_r($product->getSku());exit();
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($product->getSku());
        return $resultJson;
    }
}
