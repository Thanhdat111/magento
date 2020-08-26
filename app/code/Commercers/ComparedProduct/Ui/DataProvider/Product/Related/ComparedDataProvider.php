<?php
namespace Commercers\ComparedProduct\Ui\DataProvider\Product\Related;

use Magento\Catalog\Api\Data\ProductLinkInterface;
use Magento\Catalog\Api\ProductLinkRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Api\StoreRepositoryInterface;

class ComparedDataProvider extends \Magento\Catalog\Ui\DataProvider\Product\Related\AbstractDataProvider
{
    public function __construct($name, $primaryFieldName, $requestFieldName, CollectionFactory $collectionFactory, RequestInterface $request, ProductRepositoryInterface $productRepository, StoreRepositoryInterface $storeRepository, ProductLinkRepositoryInterface $productLinkRepository, $addFieldStrategies, $addFilterStrategies,\Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $configurableProductType, array $meta = [], array $data = [])
    {
        $this->configurableProductType = $configurableProductType;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $collectionFactory, $request, $productRepository, $storeRepository, $productLinkRepository, $addFieldStrategies, $addFilterStrategies, $meta, $data);
    }

    protected function getLinkType() {
        return 'compared';
    }
    public function getCollection(){
        /** @var Collection $collection */
        $collection = parent::getCollection();
        $collection->addAttributeToSelect('status');
        $collection->addAttributeToSelect('visibility');

        if ($this->getStore()) {
            $collection->setStore($this->getStore());
        }
        $collection->addAttributeToFilter('status',['eq' =>\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED]);
        $collection->addAttributeToFilter('visibility', ['eq'=>\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH]);
        if (!$this->getProduct()) {
            return $collection;
        }

        $collection->addAttributeToFilter(
            $collection->getIdFieldName(),
            ['nin' => [$this->getProduct()->getId()]]
        );

        return $this->addCollectionFilters($collection);
    }

}
