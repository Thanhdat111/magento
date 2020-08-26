<?php

namespace Commercers\AutoProductRelation\Model\Services\CartCoupon;

class GenerateCouponCodesService {

    const SHOPPING_CART_RULE = 'section_cross_sell/group_commercers_auto_product_relation_followup_email/enable_auto_product_relation_followup_email_shopping_cart_rule';
    const COUPON_EXPIRE_RULE = 'section_cross_sell/group_commercers_auto_product_relation_followup_email/enable_auto_product_relation_followup_email_coupon_expire';

    protected $couponFactory;
    protected $rule;
    protected $date;
    protected $scopeConfig;

    public function __construct(
    \Magento\SalesRule\Model\CouponFactory $couponFactory, \Magento\SalesRule\Model\RuleFactory $rule, \Magento\Framework\Stdlib\DateTime\DateTime $date, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->couponFactory = $couponFactory;
        $this->rule = $rule;
        $this->date = $date;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute() {
        $idRule = $this->scopeConfig->getValue(self::SHOPPING_CART_RULE, \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
        $couponExpire = $this->scopeConfig->getValue(self::COUPON_EXPIRE_RULE, \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
        $today = $this->date->gmtDate();
        $dateExpire = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($today)) . " + " . $couponExpire . " day"));
        $rule = $this->rule->create()->load($idRule);
        $couponCode = $rule->getCouponCodeGenerator()->generateCode();
        $coupon = $this->couponFactory->create();
        if ($idRule) {
            try {
                $coupon->setRule($rule)
                        ->setUsageLimit(
                                $rule->getUsesPerCoupon() ? $rule->getUsesPerCoupon() : null
                        )->setUsagePerCustomer(
                                $rule->getUsesPerCustomer() ? $rule->getUsesPerCustomer() : null
                        )->setTimesUsed(
                                0
                        )->setExpirationDate(
                                $dateExpire ? $dateExpire : $rule->getToDate()
                        )->setCreatedAt($today)
                        ->setType(true)
                        ->setCode($couponCode);
                $coupon->save();
                return $couponCode;
            } catch (Exception $ex) {
                return false;
            }
        }
    }

}
