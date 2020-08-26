<?php


namespace Commercers\ProductAdvance\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Controller\Result\JsonFactory;

class AjaxGetSize extends Action
{
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
        $sizeId = $product->getBegadiSize();
        $attr = $product->getResource()->getAttribute('begadi_size');
        if ($attr->usesSource()) {
            $optionText = $attr->getSource()->getOptionText($sizeId);
        }
        if ($optionText) {
            $response['success'] = true;
            $response['size'] = $optionText;
        }


        return $this->_resultJsonFactory->create()->setData($response);
    }
}
