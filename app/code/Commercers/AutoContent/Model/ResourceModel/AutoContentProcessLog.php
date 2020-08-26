<?php
namespace Commercers\AutoContent\Model\ResourceModel;

class AutoContentProcessLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('autocontent_process_log', 'process_id'); /* tên bảng , Id của bảng*/
    }

}