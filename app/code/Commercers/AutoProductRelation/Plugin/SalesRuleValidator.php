<?php

namespace Commercers\AutoProductRelation\Plugin;

class SalesRuleValidator
{
    protected $productRecommendation;
    protected $productFactory;
    public function __construct(
        \Commercers\AutoProductRelation\Model\ProductRecommendationFactory $productRecommendation,
        \Magento\Catalog\Model\ProductFactory $productFactory

    ) {
        $this->productRecommendation = $productRecommendation;
        $this->productFactory = $productFactory;
    }

    public function aroundCanApplyDiscount(\Magento\SalesRule\Model\Validator $subject, \Closure $proceed, $item) {
        $couponCode = $subject->getCouponCode();
        if ($couponCode) {
            $productId = (int) $item->getProduct()->getId();
            $productLinked = $this->productRecommendation->create()->load($couponCode, 'coupon_code');
            $jsonArray = json_decode($productLinked['product_linked']);
            $jsonDecode = get_object_vars($jsonArray);
            foreach ($jsonDecode['product_id'] as $value) {
                $productIds[] = $value;
            }
            if ($item->getProduct()->getTypeId() === 'configurable') {
                $options = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
                $productSimple = $this->productFactory->create()->loadByAttribute('sku', $options['simple_sku']);
                $productId = $productSimple->getId();
                foreach ($jsonDecode['product_simple_id'] as $productSimpleId) {
                    $productIds[] = (int)$productSimpleId;
                }
            }
            if (in_array($productId, $productIds)) {
                return true;
            } else {
                return false;
            }
        }
    }

}
