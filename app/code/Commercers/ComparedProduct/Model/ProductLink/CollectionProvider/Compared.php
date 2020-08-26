<?php
namespace Commercers\ComparedProduct\Model\ProductLink\CollectionProvider;

class Compared implements \Magento\Catalog\Model\ProductLink\CollectionProviderInterface {

    public function getLinkedProducts(\Magento\Catalog\Model\Product $product)
    {
        return $product->getComparedProducts();
    }
}