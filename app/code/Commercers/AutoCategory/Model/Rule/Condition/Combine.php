<?php


namespace Commercers\AutoCategory\Model\Rule\Condition;


use Commercers\AutoCategory\Model\Rule\Condition\Product;

class Combine extends \Magento\Rule\Model\Condition\Combine
{
    /**
     * @var \Magento\CatalogWidget\Model\Rule\Condition\ProductFactory
     */
    protected $productFactory;

    /**
     * {@inheritdoc}
     */
    protected $elementName = 'rule';

    /**
     * @var array
     */
    private $excludedAttributes;

    /**
     * @param \Magento\Rule\Model\Condition\Context $context
     * @param \Magento\CatalogWidget\Model\Rule\Condition\ProductFactory $conditionFactory
     * @param array $data
     * @param array $excludedAttributes
     */
    public function __construct(
        \Magento\Rule\Model\Condition\Context $context,
        \Commercers\AutoCategory\Model\Rule\Condition\ProductFactory $conditionFactory,
        array $data = [],
        array $excludedAttributes = []
    ) {
        $this->productFactory = $conditionFactory;
        parent::__construct($context, $data);
        $this->setType(\Commercers\AutoCategory\Model\Rule\Condition\Combine::class);
        $this->excludedAttributes = $excludedAttributes;
    }

    /**
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        $productAttributes = $this->productFactory->create()->loadAttributeOptions()->getAttributeOption();
        $attributes = [];
        foreach ($productAttributes as $code => $label) {
            if (!in_array($code, $this->excludedAttributes)) {
                $attributes[] = [
                    'value' => Product::class . '|' . $code,
                    'label' => $label,
                ];
            }
        }
        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive(
            $conditions,
            [
                [
                    'value' => \Commercers\AutoCategory\Model\Rule\Condition\Combine::class,
                    'label' => __('Conditions Combination'),
                ],
                ['label' => __('Product Attribute'), 'value' => $attributes]
            ]
        );
        return $conditions;
    }

    /**
     * Collect validated attributes for Product Collection
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection
     * @return \Commercers\AutoContent\Model\Condition\Combine
     */
    public function collectValidatedAttributes($productCollection)
    {
        foreach ($this->getConditions() as $condition) {
            $condition->collectValidatedAttributes($productCollection);
        }
        return $this;
    }
}
