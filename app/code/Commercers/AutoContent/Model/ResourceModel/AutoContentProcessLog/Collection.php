<?php
namespace Commercers\AutoContent\Model\ResourceModel\AutoContentProcessLog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    public function _construct(){
    $this->_init("Commercers\AutoContent\Model\AutoContentProcessLog","Commercers\AutoContent\Model\ResourceModel\AutoContentProcessLog");
        /* Lop Model + Lop Resource Model*/
    }
}