<?php

namespace Commercers\AutoProductRelation\Model\Services\CartCoupon;

class GenerateCouponCodesService {

    protected $couponFactory;
    protected $rule;
    protected $random;
    protected $codegeneratorInterface;
    protected $date;

    public function __construct(
    \Magento\SalesRule\Model\CouponFactory $couponFactory, \Magento\SalesRule\Model\Rule $rule, \Magento\SalesRule\Model\Coupon\CodegeneratorInterface $codegeneratorInterface, \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        $this->couponFactory = $couponFactory;
        $this->rule = $rule;
        $this->codegeneratorInterface = $codegeneratorInterface;
        $this->date = $date;
    }

    public function execute() {
        $ruleCollections = $this->rule->getCollection();

        foreach ($ruleCollections as $ruleCollection) {
            $rule = $ruleCollection->load(23);
        }
        $couponCode = $rule->getCouponCodeGenerator()->generateCode();
        $coupon = $this->couponFactory->create();
        $coupon->setRule($rule)
                ->setUsageLimit(
                        $rule->getUsesPerCoupon() ? $rule->getUsesPerCoupon() : null
                )->setUsagePerCustomer(
                        $rule->getUsesPerCustomer() ? $rule->getUsesPerCustomer() : null
                )->setTimesUsed(
                        $rule->getTimesUsed() ? $rule->getTimesUsed() : 0
                )->setExpirationDate(
                        $rule->getToDate()
                )->setCreatedAt($this->date->gmtDate())
                ->setType(true)
                ->setCode($couponCode);
        $coupon->save();
    }

}
