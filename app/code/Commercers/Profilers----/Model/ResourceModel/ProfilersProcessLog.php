<?php
namespace Commercers\Profilers\Model\ResourceModel;

class ProfilersProcessLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('profiler_process_log', 'process_id'); /* tên bảng , Id của bảng*/
    }

}