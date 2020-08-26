<?php

namespace Commercers\ComparedProduct\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    protected $_productFactory;
    protected $_linkCollection;
    protected $_productRepository;

    public function __contruct(
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Model\ResourceModel\Product\Link\Collection $linkCollection
    ){
        $this->_productFactory = $productFactory;
        $this->_linkCollection = $linkCollection;
        $this->_productRepository = $productRepository;
    }
    public function getComparedProductIds($productId){
        $collection = $this->_linkCollection;
        $collection->addFieldToFilter('product_id',['eq' => $productId]);
        $collection->addFieldToFilter('link_type_id',['eq' => \Commercers\ComparedProduct\Model\Product\Link::LINK_TYPE_COMPARED]);
        //$collection->limit(5);
        $comparedProductIds = array();
        foreach ($collection->getData() as $record){
            $productModel =  $this->_productFactory->create();
            $comparedProductIds[]= $this->_productRepository->getById($record['linked_product_id']);

        }
        return $comparedProductIds;
    }
}