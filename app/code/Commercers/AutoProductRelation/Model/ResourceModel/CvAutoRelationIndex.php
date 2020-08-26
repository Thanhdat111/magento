<?php
/**
 *  Commercers Vietnam
 *  Duc Hieu
 */

namespace Commercers\AutoProductRelation\Model\ResourceModel;

class CvAutoRelationIndex extends \Magento\Rule\Model\ResourceModel\AbstractResource
{

    /**
     * Initialize main table and table id field
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('cv_autorelation_index', 'entity_id');
    }
}