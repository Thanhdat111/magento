<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Commercers\AutoProductRelation\Model\Rule\Condition;

use Magento\Catalog\Model\ResourceModel\Product\Collection;

/**
 * @api
 * @since 100.0.2
 */
class Combine extends \Magento\Rule\Model\Condition\Combine
{
    /**
     * @var \Magento\SalesRule\Model\Rule\Condition\Product
     */
    const AVAILABLE_ATTRIBUTES = 'section_cross_sell/group_commercers_auto_product_relation_general/enable_auto_product_relation_general_attributes';
    protected $_ruleConditionProd;
    protected $scopeConfig;
    /**
     * @param \Magento\Rule\Model\Condition\Context $context
     * @param \Magento\SalesRule\Model\Rule\Condition\Product $ruleConditionProduct
     * @param array $data
     */
    public function __construct(
        \Magento\Rule\Model\Condition\Context $context,
        \Commercers\AutoProductRelation\Model\Rule\Condition\Product $ruleConditionProduct,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_ruleConditionProd = $ruleConditionProduct;
        $this->scopeConfig = $scopeConfig;
        $this->setType(\Commercers\AutoProductRelation\Model\Rule\Condition\Combine::class);
    }

    /**
     * Get new child select options
     *
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        $availableAttributes = $this->scopeConfig->getValue(self::AVAILABLE_ATTRIBUTES, \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
        if ($availableAttributes){
            $availableAttributes = explode(',', $availableAttributes);
        }
        $productAttributes = $this->_ruleConditionProd->loadAttributeOptions()->getAttributeOption();
        //print_r($productAttributes);
        $pAttributes = [];
        $iAttributes = [];
        foreach ($productAttributes as $code => $label) {
           
                if(in_array($code,$availableAttributes)){
                    $iAttributes[] = [
                        'value' => \Commercers\AutoProductRelation\Model\Rule\Condition\Product::class . '|' . $code,
                        'label' => $label,
                    ];   
                
            } else {
                $pAttributes[] = [
                    'value' => \Commercers\AutoProductRelation\Model\Rule\Condition\Product::class . '|' . $code,
                    'label' => $label,
                ];
            }
        } 
        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive(
            $conditions,
            [
                [
                    'value' => \Commercers\AutoProductRelation\Model\Rule\Condition\Combine::class,
                    'label' => __('Conditions Combination'),
                ],
                ['label' => __('Product Attribute'), 'value' => $iAttributes]
            ]
        );
        return $conditions;
    }

    /**
     * Collect validated attributes
     *
     * @param Collection $productCollection
     * @return $this
     */
    public function collectValidatedAttributes($productCollection)
    {
        foreach ($this->getConditions() as $condition) {
            $condition->collectValidatedAttributes($productCollection);
        }
        return $this;
    }
}
