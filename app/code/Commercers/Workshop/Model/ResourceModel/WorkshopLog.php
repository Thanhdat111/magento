<?php
namespace Commercers\Workshop\Model\ResourceModel;

class WorkshopLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }
    public function _construct()
    {
        $this->_init("commercers_workshop_log","pk_entity_id");
    }

}