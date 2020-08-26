<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Commercers\Workshop\Ui\Component\Workshop;

use Magento\Ui\DataProvider\AbstractDataProvider;


class DataProviderReference extends AbstractDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Commercers\Workshop\Model\ResourceModel\WorkshopReferences\CollectionFactory $collectionFactory,
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
        $this->collection->getSelect()->join(
            array('workshop_task' => ('commercers_workshop_task')),
            "workshop_task.pk_entity_id = main_table.pk_entity_id",
            array('main_table.pk_entity_id','workshop_task.type','workshop_task.offer_price','workshop_task.status','workshop_task.order_increment_id')
        );

        $this->_makeCollectionDifferent();
    }

    protected function _makeCollectionDifferent(){
        return;
    }

}
