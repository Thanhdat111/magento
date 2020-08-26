<?php


namespace Commercers\ProductAdvance\Controller\Adminhtml\Product;


use Magento\Backend\App\Action;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\ResponseInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class AjaxGetLogo extends Action
{
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory

    ) {
        parent::__construct($context);
        $this->_resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        print_r("12312312thanhdat"); exit;
        // TODO: Implement execute() method.
        $response = [];
        $response['success'] = false;
        $image = $this->getRequest()->getParam('image');
        print_r("12312312");
        print_r($image); exit;

//        $product = $this->_productRepository->getById($product_id);
//        $price = $product->getPrice();
//        $priceFormat = round($price,3);
//        if($priceFormat == 0){
//            $priceFormat = 0.0;
//        }
//        if ($price) {
//            $response['success'] = true;
//            $response['price'] = $priceFormat;
//        }


        return $this->_resultJsonFactory->create()->setData($response);
    }
}