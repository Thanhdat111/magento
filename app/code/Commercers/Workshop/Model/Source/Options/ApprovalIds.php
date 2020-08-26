<?php
namespace Commercers\Workshop\Model\Source\Options;

use Magento\Framework\Data\OptionSourceInterface;

class ApprovalIds implements OptionSourceInterface
{
     /**
      * Get options
      *
      * @return array
      */
     public function toOptionArray()
     {
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $attribute = $objectManager->create('\Magento\Eav\Model\Config')->getAttribute('catalog_product', 'age_status');
          $options = $attribute->getSource()->getAllOptions();
          return $options;
     }
}