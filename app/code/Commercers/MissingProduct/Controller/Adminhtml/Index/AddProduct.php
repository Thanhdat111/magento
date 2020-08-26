<?php


namespace Commercers\MissingProduct\Controller\Adminhtml\Index;

use Magento\Framework\App\ResponseInterface;
use Magento\Backend\App\Action;
use Magento\Setup\Exception;

class AddProduct extends Action
{
    protected $resultPageFactory = false;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Commercers\MissingProduct\Model\MissingProductFactory $missingProductFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->missingProductFactory = $missingProductFactory;
        $this->_messageManager = $messageManager;
        $this->productFactory = $productFactory;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        // TODO: Implement execute() method.
        $missingProductId = $this->getRequest()->getParam('id');
        $missingProduct = $this->missingProductFactory->create()->load($missingProductId);
        try {
            $product = $this->productFactory->create();
            $product->setStatus(1); // Status on product enabled/ disabled 1/0
            $product->setVisibility(4); // visibilty of product (catalog / search / catalog, search / Not visible individually)
            $product->setTypeId('simple'); // type of product (simple/virtual/downloadable/configurable)
            $product->setName($missingProduct->getName());
            $product->setSku($missingProduct->getSku());
            $product->setPrice($missingProduct->getPrice());
            $product->setEan($missingProduct->getEan());
            $product->setAttributeSetId(4);
            $product->save();
            $missingProduct->delete();
            $this->_redirect('catalog/product/index');
            $this->_messageManager->addSuccessMessage(__('Add Product Successfully'));
        } catch (\Exception $e) {
            $this->_messageManager->addError(__('Product exists already!'));
            $missingProduct->delete();
            $this->_redirect('missing_product/index/listing');
        }

    }
}