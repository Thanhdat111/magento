<?php 
namespace Commercers\Profilers\Model\ResourceModel\Attribute;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
    
    
    /**
     * @var string
     */
    public function _construct(){
        $this->_init("Commercers\Profilers\Model\Attribute","Commercers\Profilers\Model\ResourceModel\Attribute");
            
    }
    
}