<?php
class Commercers_Autocategory_Model_System_Config_Source_Attributes_Flat
{
    public function toOptionArray()
    {
        $noneSelectedAttributes = array();
        $collection = Mage::getResourceModel('catalog/product_attribute_collection')
            ->setEntityTypeFilter(Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId())
            ->addVisibleFilter()
            ->addFieldToFilter('used_in_product_listing',array('eq'=>1))
            ->setOrder('frontend_label','ASC')
        ;

        $attributes = array();
        if ($collection->getSize()) {
            foreach ($collection as $attribute) {
                $code = $attribute->getAttributeCode();
                if (!in_array($code, $noneSelectedAttributes)) {
                    $attributes[] = array(
                        'value' => $code,
                        'label' => $attribute->getFrontend()->getLabel(),
                    );
                }

            }
        }
        return $attributes;

    }

}