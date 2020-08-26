<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Commercers\Workshop\Ui\Component\Workshop;

use Magento\Ui\DataProvider\AbstractDataProvider;


class DataProviderChat extends AbstractDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Commercers\Workshop\Model\ResourceModel\WorkshopChat\CollectionFactory $collectionFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Request\Http $request,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collectionFactory = $collectionFactory;
        $this->addFieldStrategies  = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;
        $this->coreRegistry = $coreRegistry;
        $this->request = $request;
        $this->collection = $this->collectionFactory->create();
        // $this->collection->addFieldToFilter('pk_entity_id', array(
        //     'eq' => $this->_getId())
        //     );

        $this->_makeCollectionDifferent();
    }

    protected function _makeCollectionDifferent(){
        return;
    }

}

