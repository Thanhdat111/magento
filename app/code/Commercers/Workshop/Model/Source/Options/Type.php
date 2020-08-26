<?php
namespace Commercers\Workshop\Model\Source\Options;

class Type implements \Magento\Framework\Option\ArrayInterface
{
     const TYPE_NEW = 1;
     const TYPE_REPAIR_AND_TUNING = 2;
     const TYPE_SPARE_PART_ORDER = 3;
     const TYPE_SPARE_PART_IMPORT = 4;

     protected $_options;
     /**
      * Get options
      *
      * @return array
      */
     public function toOptionArray($isMultiselect = false)
     {
          $status[static::TYPE_NEW] = 'type_new';
          $status[static::TYPE_REPAIR_AND_TUNING] = 'type_repair_and_tuning';
          $status[static::TYPE_SPARE_PART_ORDER] = 'type_spare_part_order';
          $status[static::TYPE_SPARE_PART_IMPORT] = 'type_spare_part_import';
          
          if (!$this->_options) {
               /* @var $helper Fairnet_Workshop_Helper_Status */
               $optionsStatus = array();
               foreach ($status as $key => $value) {
                   array_push($optionsStatus,
                           array('value' => $key, 'label' => $value)
                   );
               }
               $this->_options = $optionsStatus;
           }
   
           $options = $this->_options;
           //add leading 'Please Select' indicator
           if (!$isMultiselect) {
               array_unshift($options,
                       array('value' => '', 'label' => __('--Please Select--')));
           }
           return $options;
     }
}