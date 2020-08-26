<?php

namespace Commercers\AutoProductRelation\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class Actions extends \Magento\Ui\Component\Listing\Columns\Column {

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

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
    array $components = [],
    array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
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
//                $item[$this->getData('name')]['edit'] = [
//                    'href' => "#",
//                    'label' => __('Edit'),
//                    'hidden' => false,
//                ];
                
                $item[$this->getData('name')]['sendemail'] = [
                'href' => $this->urlBuilder->getUrl(
                'backend/sendemail/sendemail',
                ['id' => $item['id'],'orderId' => $item['order_id'], 'customerEmail' => $item['customer_email']]
                ),
                'label' => __('Send Now'),
                'hidden' => false,
                ];
            }
        }

        return $dataSource;
    }

}
