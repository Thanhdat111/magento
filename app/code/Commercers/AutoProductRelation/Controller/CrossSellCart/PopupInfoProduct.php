<?php

namespace Commercers\AutoProductRelation\Controller\CrossSellCart;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
class PopupInfoProduct extends \Magento\Framework\App\Action\Action {

    protected $_resultPageFactory;
    protected $_resultJsonFactory;
    protected $productFactory;
    protected $_productConfigurable;
    public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $productConfigurable,
    PageFactory $resultPageFactory,
    JsonFactory $resultJsonFactory
    ) {
        $this->productFactory = $productFactory;
        $this->_productConfigurable = $productConfigurable;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    public function execute() {
        $productId = $this->getRequest()->getParam('productId');
        $productId = $this->getParentId($productId);
        $product = $this->productFactory->create()->load($productId);
        $response["result"] = $this->_view->getLayout()// response ch?y qua block PopupSuccess
                ->createBlock('Commercers\AutoProductRelation\Block\InfoProduct\Popup')
                ->setProduct($product)
                ->toHtml();
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);  //create Json type return object
        $resultJson->setData($response);  // array value set in Json Result Data set
        return $resultJson; 
    } 
    public function getParentId($productId){
        $parentIds = $this->_productConfigurable->getParentIdsByChild($productId);
        if ($parentIds != NULL) {
            $parentId = array_shift($parentIds);
            $parentId = $parentId;
        } else {
            $parentId = $productId;
        }
        return $parentId;

    }
}
