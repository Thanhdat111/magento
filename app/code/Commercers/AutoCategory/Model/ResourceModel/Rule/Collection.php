<?php


namespace Commercers\AutoCategory\Model\ResourceModel\Rule;


use Magento\Rule\Model\ResourceModel\Rule\Collection\AbstractCollection;
use Commercers\AutoCategory\Model\ResourceModel\Rule as RuleResource;
use Commercers\AutoCategory\Model\Rule;

/**
 * Rule collection
 */
class Collection extends AbstractCollection
{
    /**
     * Set resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Rule::class, RuleResource::class);
    }
}
