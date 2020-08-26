<?php 
namespace Commercers\AutoContent\Model\ResourceModel\Attribute;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
    
    
    /**
     * @var string
     */
    public function _construct(){
        $this->_init("Commercers\AutoContent\Model\Attribute","Commercers\AutoContent\Model\ResourceModel\Attribute");
            
    }
    
}