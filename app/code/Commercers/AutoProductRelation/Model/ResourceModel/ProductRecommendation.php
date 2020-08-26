<?php

namespace Commercers\AutoProductRelation\Model\ResourceModel;

class ProductRecommendation extends \Magento\Rule\Model\ResourceModel\AbstractResource
{

    /**
     * Initialize main table and table id field
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('cv_product_recommendation_log', 'id');
    }
}