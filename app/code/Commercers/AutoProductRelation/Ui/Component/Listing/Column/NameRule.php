<?php

namespace Commercers\AutoProductRelation\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class NameRule extends \Magento\Ui\Component\Listing\Columns\Column {

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    protected $ruleFactory;
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
    ContextInterface $context,
    UiComponentFactory $uiComponentFactory,
    UrlInterface $urlBuilder,
    \Commercers\AutoProductRelation\Model\RuleFactory $ruleFactory,
    array $components = [],
    array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->ruleFactory = $ruleFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {
            $storeId = $this->context->getFilterParam('store_id');

            foreach ($dataSource['data']['items'] as &$item) {
                $rule = $this->ruleFactory->create()->load($item['rule_id']);
                $nameRule = $rule->getName();
                $item[$this->getData('name')][$nameRule] = [
                'href' => $this->urlBuilder->getUrl(
                'backend/crosssell_rule/edit/',
                ['id' => $item['rule_id']]
                ),
                'target' => '_blank',
                'label' => __($nameRule),
                'hidden' => false,
                ];
            }
        }

        return $dataSource;
    }

}
