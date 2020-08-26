<?php

namespace Commercers\AutoProductRelation\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class LinkedProduct extends \Magento\Ui\Component\Listing\Columns\Column {

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    protected $productRepository;
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
    ContextInterface $context,  \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, UiComponentFactory $uiComponentFactory, UrlInterface $urlBuilder, array $components = [], array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->productRepository = $productRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource) {
        // echo "<pre>";print_r($dataSource);exit;
        if (isset($dataSource['data']['items'])) {
            $storeId = $this->context->getFilterParam('store_id');
            foreach ($dataSource['data']['items'] as &$item) {
                $jsonLinked = json_decode($item['linkedIds']);
                foreach ($jsonLinked as $valueLinked) {
                    $skuLinks = $valueLinked;
                }
                foreach ($skuLinks as $sku) {
                    $product = $this->productRepository->get($sku);
                    $productId = $product->getId();
                    $item[$this->getData('name')][$sku] = [
                        'href' => $this->urlBuilder->getUrl(
                        'catalog/product/edit/',
                        ['id' => $productId]
                        ),
                        'target' => '_blank',
                        'label' => __($sku),
                        'hidden' => false,
                    ];
                }
            }
        }
        return $dataSource;
    }

}
