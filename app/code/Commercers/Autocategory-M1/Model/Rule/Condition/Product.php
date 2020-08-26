<?php
class Commercers_Autocategory_Model_Rule_Condition_Product
    extends Mage_Rule_Model_Condition_Product_Abstract
{

    public function __construct()
    {
        parent::__construct();
        $this->setType('autocategory/rule_condition_product');
    }
    public function loadAttributeOptions()
    {
        $productAttributes = Mage::getResourceSingleton('catalog/product')
            ->loadAllAttributes()
            ->getAttributesByCode();
        $allowedAttributes = Mage::helper('commercers_autocategory')->getAllowedAttributes();

        $attributes = array();
        foreach ($productAttributes as $attribute) {
            /* @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
            if(!in_array($attribute->getAttributeCode(),$allowedAttributes))
            {
                continue;
            }
            $attributes[$attribute->getAttributeCode()] = $attribute->getFrontendLabel();
        }

        $this->_addSpecialAttributes($attributes);

        asort($attributes);
        $this->setAttributeOption($attributes);

        return $this;
    }
    protected function _addSpecialAttributes(array &$attributes)
    {
        $attributes['attribute_set_id'] = Mage::helper('catalogrule')->__('Attribute Set');
        $attributes['type_id'] = Mage::helper('catalogrule')->__('Product Type');
    }

    public function getInputType()
    {
        if ($this->getAttribute() === 'attribute_set_id') {
            return 'select';
        }
        if ($this->getAttribute() === 'type_id') {
            return 'select';
        }
        if (!is_object($this->getAttributeObject())) {
            return 'string';
        }
        switch ($this->getAttributeObject()->getFrontendInput()) {
            case 'select':
                return 'select';

            case 'multiselect':
                return 'multiselect';

            case 'date':
                return 'date';

            case 'boolean':
                return 'boolean';

            default:
                return 'string';
        }
    }

    public function getValueElementType()
    {
        if ($this->getAttribute() === 'attribute_set_id') {
            return 'select';
        }
        if ($this->getAttribute() === 'type_id') {
            return 'select';
        }
        if (!is_object($this->getAttributeObject())) {
            return 'text';
        }
        switch ($this->getAttributeObject()->getFrontendInput()) {
            case 'select':
            case 'boolean':
                return 'select';

            case 'multiselect':
                return 'multiselect';

            case 'date':
                return 'date';

            default:
                return 'text';
        }
    }
    protected function _prepareValueOptions()
    {
        // Check that both keys exist. Maybe somehow only one was set not in this routine, but externally.
        $selectReady = $this->getData('value_select_options');
        $hashedReady = $this->getData('value_option');
        if ($selectReady && $hashedReady) {
            return $this;
        }

        // Get array of select options. It will be used as source for hashed options
        $selectOptions = null;
        if ($this->getAttribute() === 'attribute_set_id') {
            $entityTypeId = Mage::getSingleton('eav/config')
                ->getEntityType(Mage_Catalog_Model_Product::ENTITY)->getId();
            $selectOptions = Mage::getResourceModel('eav/entity_attribute_set_collection')
                ->setEntityTypeFilter($entityTypeId)
                ->load()
                ->toOptionArray();
        } elseif($this->getAttribute() === 'type_id'){
            $selectOptions = Mage::getModel('catalog/product_type')->getAllOptions();
            foreach($selectOptions as $idx => $_opt){
                if($_opt['value'] == '')
                    unset($selectOptions[$idx]);
            }

        } elseif (is_object($this->getAttributeObject())) {
            $attributeObject = $this->getAttributeObject();
            if ($attributeObject->usesSource()) {
                if ($attributeObject->getFrontendInput() == 'multiselect') {
                    $addEmptyOption = false;
                } else {
                    $addEmptyOption = true;
                }
                $selectOptions = $attributeObject->getSource()->getAllOptions($addEmptyOption);
            }
        }

        // Set new values only if we really got them
        if ($selectOptions !== null) {
            // Overwrite only not already existing values
            if (!$selectReady) {
                $this->setData('value_select_options', $selectOptions);
            }
            if (!$hashedReady) {
                $hashedOptions = array();
                foreach ($selectOptions as $o) {
                    if (is_array($o['value'])) {
                        continue; // We cannot use array as index
                    }
                    $hashedOptions[$o['value']] = $o['label'];
                }
                $this->setData('value_option', $hashedOptions);
            }
        }

        return $this;
    }
    public function prepareConditionSql()
    {
        $alias     = 'cpf';
        $attribute = $this->getAttribute();
        $value     = $this->getValueParsed();
        $operator  = $this->correctOperator($this->getOperator(), $this->getInputType());
        if ($attribute == 'category_ids') {
            $alias     = 'ccp';
            $attribute = 'category_id';
            $value     = $this->bindArrayOfIds($value);
        }

        /** @var $ruleResource Mage_Rule_Model_Resource_Rule_Condition_SqlBuilder */
        $ruleResource = $this->getRuleResourceHelper();

        return $ruleResource->getOperatorCondition($alias . '.' . $attribute, $operator, $value);
    }
    public function correctOperator($operator, $inputType)
    {
        if ($inputType == 'category') {
            if ($operator == '==') {
                $operator = '{}';
            } elseif ($operator == '!=') {
                $operator = '!{}';
            }
        }

        return $operator;
    }
    public function getRuleResourceHelper()
    {
        if (!$this->_ruleResourceHelper) {
            $this->_ruleResourceHelper = Mage::getModel('rule/resource_rule_condition_sqlBuilder');
        }
        return $this->_ruleResourceHelper;
    }
    
}