<?php

namespace Commercers\ProductAdvance\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Catalog\Model\ProductRepository;

class AjaxGetPrice extends Action {

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        ProductRepository $productRepository

    ) {
        parent::__construct($context);
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_productRepository = $productRepository;
    }

    public function execute()
    {
        $response = [];
        $response['success'] = false;
        $product_id = $this->getRequest()->getParam('product_id');
        $product = $this->_productRepository->getById($product_id);
       $price = $product->getPrice();
       $priceFormat = round($price,3);
       if($priceFormat == 0){
           $priceFormat = 0.0;
       }
//        print_r($product->getBegadiMpdPrice());
//        exit;
        if ($price) {
            $response['success'] = true;
            $response['price'] = $priceFormat;
        }


        return $this->_resultJsonFactory->create()->setData($response);
    }
}
