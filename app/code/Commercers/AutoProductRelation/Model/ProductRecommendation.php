<?php
/**
 *  Commercers Vietnam
 *  Toan Dao
 */
namespace Commercers\AutoProductRelation\Model;

class ProductRecommendation extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Commercers\AutoProductRelation\Model\ResourceModel\ProductRecommendation'); /*định nghĩa lớp ResourceModel liên kết*/
    }
}
