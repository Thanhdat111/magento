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
    protected $ruleFactory;
    protected $autoRelationProcessLog;
    public function __construct(
    \Magento\Catalog\Api\ProductRepositoryInterface $productRepositoryInterface, 
    \Magento\Catalog\Api\Data\ProductLinkInterfaceFactory $productLinkInterfaceFactory,
    \Commercers\AutoProductRelation\Model\ResourceModel\Rule\CollectionFactory $ruleFactory,
    \Commercers\AutoProductRelation\Model\Services\AutoRelationProcessLog $autoRelationProcessLog
    ) {

        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->productLinkInterfaceFactory = $productLinkInterfaceFactory;
        $this->ruleFactory = $ruleFactory;
        $this->autoRelationProcessLog = $autoRelationProcessLog;
    }

    /**
     * Build product based on user request
     *
     * @param RequestInterface $request
     * @return \Magento\Catalog\Model\Product
     * @throws \RuntimeException
     */
    public function execute($ruleProducts) {
        //print_r($ruleProducts);exit;
        $linkTypeId = 5;
        $arrayActions = array();
        $checkProductIds = array();
        $productIdLog = array();
        $linkedIdLog = array();
        foreach ($ruleProducts as $ruleProduct) {
            if (is_array($ruleProduct['conditions']) && is_array($ruleProduct['actions'])) {
                foreach ($ruleProduct['conditions'] as $valueProductId) {
                    $productId = $valueProductId['entity_id'];
                    $product = $this->productRepositoryInterface->getById($productId);
                    $productSku = $product->getSku();
                    //add
                    $type = $this->getLinkType($linkTypeId);
                    $linkData = $product->getProductLinks();

                    $valueLinkedIds = $this->checkLinkedIds($ruleProduct, $checkProductIds, $arrayActions, $productId);
                    $productIdLog[] = $productId;
                    if (isset($linkedIdLog[$productId]))
                        $linkedIdLog[$productId] = array();
                    $linkedIdLog[$productId][$ruleProduct['rule_id']] = $valueLinkedIds;

                    $checkProductIds[] = $productId;

                    foreach ($ruleProduct['actions'] as $valueLinkedId) {
                        $linkedProductId = $valueLinkedId['entity_id'];
                        $linkedProduct = $this->productRepositoryInterface->getById($linkedProductId);
                        $productLink = $this->productLinkInterfaceFactory->create();

                        $productLink->setSku($product->getSku())
                                ->setLinkedProductSku($linkedProduct->getSku())
                                ->setLinkType($type);
                        $linkData[] = $productLink;
                    }
                    $product->setProductLinks($linkData)->save();
                }
            }
        }
        if ($linkedIdLog) {
            //Write Log          
            $dataLog = array('linkeds_id' => $linkedIdLog);
            $this->autoRelationProcessLog->execute($dataLog);
        }
    }

    public function checkLinkedIds($ruleProduct, $checkProductIds, $arrayActions, $productId) {
        $valueLinkedIds = array();
        $actions = array();
        if ($checkProductIds) {
            foreach ($checkProductIds as $checkProductId) {
                if ($checkProductId == $productId) {
                    foreach ($arrayActions as $action) {
                        if ($action['product_id'] == $productId) {
                            foreach ($action['actions'] as $valueAction) {
                                $actions[] = $valueAction;
                            }
                        }
                    }
                    $actions = array_map("unserialize", array_unique(array_map("serialize", $actions)));
                    $valueLinkedIds = array_slice($actions, 0, 6);
                } else {
                    $valueLinkedIds = $ruleProduct['actions'];
                }
            }
        } else {
            $valueLinkedIds = $ruleProduct['actions'];
            //print_r($valueLinkedIds);
        }

        return $valueLinkedIds;
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