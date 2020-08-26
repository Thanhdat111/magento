<?php
namespace Commercers\Profilers\Model\ResourceModel\Profilers;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    public function _construct(){
    $this->_init("Commercers\Profilers\Model\Profilers","Commercers\Profilers\Model\ResourceModel\Profilers");
        /* Lop Model + Lop Resource Model*/
    }
}