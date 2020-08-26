<?php

namespace Commercers\ComparedProduct\Model;

class Product extends \Magento\Catalog\Model\Product
{
    const LINK_TYPE_COMPARED = 99;

    public function getComparedProducts()
    {
        if (!$this->hasComparedProducts()) {
            $products = [];
            $collection = $this->getComparedProductCollection();
            foreach ($collection as $product) {
                $products[] = $product;
            }
            $this->setComparedProducts($products);
        }
        return $this->getData('compared_products');
    }

    public function getComparedProductIds()
    {
        if (!$this->hasComparedProductIds()) {
            $ids = [];
            foreach ($this->getComparedProducts() as $product) {
                $ids[] = $product->getId();
            }
            $this->setComparedProductIds($ids);
        }
        return [$this->getData('compared_product_ids')];
    }

    public function getComparedProductCollection()
    {
        $collection = $this->getLinkInstance()->setLinkTypeId(static::LINK_TYPE_COMPARED)->getProductCollection()->setIsStrongMode();
        $collection->setProduct($this);
        return $collection;
    }
}
