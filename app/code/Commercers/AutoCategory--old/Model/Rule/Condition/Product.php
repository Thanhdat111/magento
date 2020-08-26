<?php


namespace Commercers\AutoCategory\Model\Rule\Condition;


use Magento\Catalog\Model\ProductCategoryList;

class Product extends \Magento\Rule\Model\Condition\Product\AbstractProduct
{
    /**
     * Add special attributes
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(
        \Commercers\AutoCategory\Helper\Data $helperData,
        \Commercers\AutoCategory\Model\Rule\Condition\ProductFactory $condProdCombineF,
        \Magento\Rule\Model\Condition\Context $context,
        \Magento\Backend\Helper\Data $backendData,
        \Magento\Eav\Model\Config $config,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\ResourceModel\Product $productResource,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection $attrSetCollection,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        array $data = [],
        ProductCategoryList $categoryList = null)
    {
        $this->condProdCombineF  = $condProdCombineF;
        $this->helperData = $helperData;
        parent::__construct($context, $backendData, $config, $productFactory, $productRepository, $productResource, $attrSetCollection, $localeFormat, $data, $categoryList);
    }

    protected function _addSpecialAttributes(array &$attributes)
    {
        parent::_addSpecialAttributes($attributes);
    }

    /**
     * Validate Product Rule Condition
     *
     * @param \Magento\Framework\Model\AbstractModel $model
     * @return bool
     */
    public function validate(\Magento\Framework\Model\AbstractModel $model)
    {
        //@todo reimplement this method when is fixed MAGETWO-5713
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $model->getProduct();
        if (!$product instanceof \Magento\Catalog\Model\Product) {
            $product = $this->productRepository->getById($model->getProductId());
        }

        $product->setQuoteItemQty(
            $model->getQty()
        )->setQuoteItemPrice(
            $model->getPrice() // possible bug: need to use $model->getBasePrice()
        )->setQuoteItemRowTotal(
            $model->getBaseRowTotal()
        );

        $attrCode = $this->getAttribute();

        if ($attrCode === 'category_ids') {
            return $this->validateAttribute($this->_getAvailableInCategories($product->getId()));
        }

        if ($attrCode === 'quote_item_price') {
            $numericOperations = $this->getDefaultOperatorInputByType()['numeric'];
            if (in_array($this->getOperator(), $numericOperations)) {
                $this->setData('value', $this->getFormattedPrice($this->getValue()));
            }
        }

        return parent::validate($product);
    }
    public function loadAttributeOptions()
    {
        $productAttributes = $this->_productResource->loadAllAttributes()->getAttributesByCode();
      //  print_r($productAttributes); exit;
        $allowedAttributes = $this->helperData->getAllowedAttributes();

        $attributes = array();
        foreach ($productAttributes as $attribute) {
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

    /**
     * Retrieve value element chooser URL
     *
     * @return string
     */
    public function getValueElementChooserUrl()
    {
        $url = false;
        switch ($this->getAttribute()) {
            case 'sku':
            case 'category_ids':
                $url = 'sales_rule/promo_widget/chooser/attribute/' . $this->getAttribute();
                if ($this->getJsFormObject()) {
                    $url .= '/form/' . $this->getJsFormObject();
                }
                break;
            default:
                break;
        }
        return $url !== false ? $this->_backendData->getUrl($url) : '';
    }

    /**
     * @param string $value
     * @return float|null
     */
    private function getFormattedPrice($value)
    {
        $value = preg_replace('/[^0-9^\^.,-]/m', '', $value);

        /**
         * If the comma is the third symbol in the number, we consider it to be a decimal separator
         */
        $separatorComa = strpos($value, ',');
        $separatorDot = strpos($value, '.');
        if ($separatorComa !== false && $separatorDot === false && preg_match('/,\d{3}$/m', $value) === 1) {
            $value .= '.00';
        }
        return $this->_localeFormat->getNumber($value);
    }
}