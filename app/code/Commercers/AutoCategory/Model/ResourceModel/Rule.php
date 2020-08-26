<?php


namespace Commercers\AutoCategory\Model\ResourceModel;


use Magento\Rule\Model\ResourceModel\AbstractResource;

class Rule extends AbstractResource
{
    /**
     * Primary key auto increment flag
     *
     * @var bool
     */
    protected $_isPkAutoIncrement = false;

    /**
     * Initialize main table and table id field
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('commercers_autocategory_rule', 'rule_id');
    }
}
