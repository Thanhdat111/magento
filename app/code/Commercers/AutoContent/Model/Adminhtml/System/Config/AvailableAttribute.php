<?php

namespace Commercers\AutoContent\Model\Adminhtml\System\Config;

class AvailableAttribute {

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->resource = $resource;
    }

    public function toOptionArray() {
        $resource =  $this->resource;
        $connection = $resource->getConnection();
        $indexerTable = $resource->getTableName('eav_attribute');
        $sql = "SELECT attribute_code,frontend_label FROM {$indexerTable} WHERE entity_type_id = '4'";
        $productAttributes = $connection->fetchAll($sql);
        foreach($productAttributes as $productAttribute){
            $attributes[] = array('value'=>$productAttribute['attribute_code'],'label'=>$productAttribute['frontend_label']);
        }
        return $attributes;
    }

}
