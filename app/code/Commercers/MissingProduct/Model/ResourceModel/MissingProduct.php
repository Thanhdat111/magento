<?php


namespace Commercers\MissingProduct\Model\ResourceModel;


use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class MissingProduct extends AbstractDb
{

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        // TODO: Implement _construct() method.
        $this->_init('commercers_missing_product','id');
    }
}