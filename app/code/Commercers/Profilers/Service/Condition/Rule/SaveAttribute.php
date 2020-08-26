<?php

namespace Commercers\Profilers\Service\Condition\Rule;

class SaveAttribute {

    public function __construct(
    \Commercers\Profilers\Model\AttributeFactory $attributeFactory
    ) {
        $this->attributeFactory = $attributeFactory;
    }

    public function save($paramsJson, $ruleId = '', $storeId = '') {
        //echo "<pre>";print_r($paramsJson);exit;
        if ($ruleId) {
            if ($storeId) {
                $attributeOld = $this->attributeFactory->create()->getCollection()
                        ->addFieldToFilter('rule_id', array('eq' => $ruleId))
                        ->addFieldToFilter('store_id', array('eq' => $storeId));
            } else {
                $attributeOld = $this->attributeFactory->create()->getCollection()
                        ->addFieldToFilter('rule_id', array('eq' => $ruleId));
            }
            if ($attributeOld->getSize()) {
                $attributeOld->walk('delete');
            }
        }
        foreach ($paramsJson as $paramJson) {
            if ($paramJson) {
                $paramsDecode = json_decode($paramJson, true);
                foreach ($paramsDecode as $paramDecode) {
                    $attributeFactory = $this->attributeFactory->create();
                    $attributeFactory->addData(array(
                        'rule_id' => $ruleId,
                        'store_id' => $paramDecode['store_id'],
                        'attribute_code' => $paramDecode['attribute']['attribute_code'],
                        'expression' => $paramDecode['expression'],
                        'use_default' => $paramDecode['use_default']
                    ));
                    $attributeFactory->save();
                }
            }
        }
    }

}
