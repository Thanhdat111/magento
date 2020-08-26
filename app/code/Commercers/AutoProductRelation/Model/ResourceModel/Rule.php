<?php

namespace Commercers\AutoProductRelation\Model\ResourceModel;

class Rule extends \Magento\Rule\Model\ResourceModel\AbstractResource
{

    /**
     * Initialize main table and table id field
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('cv_auto_product_relattion_rule', 'rule_id');
    }
}