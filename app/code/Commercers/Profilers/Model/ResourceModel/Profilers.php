<?php
namespace Commercers\Profilers\Model\ResourceModel;

class Profilers extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('profilers', 'id'); /* tên bảng , Id của bảng*/
    }

}