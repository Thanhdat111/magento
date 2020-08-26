<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Commercers\AutoProductRelation\Model\Services;

use Magento\Catalog\Model\ProductFactory;
use Magento\Cms\Model\Wysiwyg as WysiwygModel;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreFactory;
use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\Registry;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type as ProductTypes;

class ProductRepositoryService {

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepositoryInterface;
    protected $productLinkInterfaceFactory;

    public function __construct(
    \Magento\Catalog\Api\ProductRepositoryInterface $productRepositoryInterface, 
            \Magento\Catalog\Api\Data\ProductLinkInterfaceFactory $productLinkInterfaceFactory
    ) {

        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->productLinkInterfaceFactory = $productLinkInterfaceFactory;
    }

    /**
     * Build product based on user request
     *
     * @param RequestInterface $request
     * @return \Magento\Catalog\Model\Product
     * @throws \RuntimeException
     */
    public function execute($data) {
        $productId = $data['productId'];
        $linkedProductIds = $data['linkedProductIds'];
        $linkTypeId = $data['linkTypeId'];

        $product = $this->productRepositoryInterface->getById($productId);
        $productSku = $product->getSku();
        //add
        $type = $this->getLinkType($linkTypeId);
		$linkData = $product->getProductLinks();
        foreach ($linkedProductIds as $linkedProductId) {
            $linkedProduct = $this->productRepositoryInterface->getById($linkedProductId);
            $productLink = $this->productLinkInterfaceFactory->create();
           
            $productLink->setSku($product->getSku())
                    ->setLinkedProductSku($linkedProduct->getSku())
                    ->setLinkType($type);
            $linkData[] = $productLink;
        }
		
        $product->setProductLinks($linkData)->save();
    }

    public function getLinkType($linkTypeId) {
        if ($linkTypeId == 1) {
            return 'related';
        }
        if ($linkTypeId == 4) {
            return 'upsell';
        }
        if ($linkTypeId == 5) {
            return 'crosssell';
        }
    }

}
