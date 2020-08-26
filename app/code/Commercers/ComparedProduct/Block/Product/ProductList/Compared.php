<?php

namespace Commercers\ComparedProduct\Block\Product\ProductList;

class Compared extends \Magento\Framework\View\Element\Template
{
    protected $_registry;
    protected $_productFactory;
    protected $_linkCollection;
    protected $_productRepository;
    protected $_storeManager;
    protected $_compareHelper;
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Block\Product\ListProduct $listProduct,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Helper\Product\Compare $compareHelper,
        \Magento\Framework\Data\Helper\PostHelper $postHelper,
        \Magento\Catalog\Model\Product\Compare\ListCompare $listCompare,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $configurableProductType,
        \Magento\Catalog\Model\ResourceModel\Product\Link\Collection $linkCollection,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->_registry = $registry;
        $this->_listProduct = $listProduct;
        $this->_productFactory = $productFactory;
        $this->_linkCollection = $linkCollection;
        $this->_productRepository = $productRepository;
        $this->_storeManager = $storeManager;
        $this->configurableProductType = $configurableProductType;
        parent::__construct($context, $data);
    }
    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');

    }
    public function getComparedProductIds($productId){
        $collection = $this->_linkCollection;
        $collection->addFieldToFilter('product_id',['eq' => $productId]);
        $collection->addFieldToFilter('link_type_id',['eq' => \Commercers\ComparedProduct\Model\Product\Link::LINK_TYPE_COMPARED]);
        $collection->getSelect()->limit(5);
        $comparedProductIds = array();
        foreach ($collection->getData() as $record){
            $comparedProductIds[] = $record['linked_product_id'];

        }

        return $comparedProductIds;
    }
    public function getProduct($productId){
        $product =  $this->_productFactory->create()->setStoreId($this->getStoreId())->load($productId);
        if($product->getTypeId() == 'configurable'){
            return $product;
        }else{
            $configurableProduct = $this->configurableProductType->getParentIdsByChild($productId);
            if($configurableProduct) {
                $product =  $this->_productFactory->create()->setStoreId($this->getStoreId())->load($configurableProduct[0]);
                return $product;
            }
            else {
                $product =  $this->_productFactory->create()->setStoreId($this->getStoreId())->load($productId);
                if($product) return $product;
                else return false;
            }
        }

    }
    public function getProductPrice($product){
        return $this->_listProduct->getProductPrice($product);

    }
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }
    public function getMediaUrl(){
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
}