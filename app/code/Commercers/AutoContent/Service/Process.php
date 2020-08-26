<?php

namespace Commercers\AutoContent\Service;
class Process {
    public function __construct(
        \Commercers\AutoContent\Model\RuleFactory $ruleFactory,
        \Commercers\AutoContent\Model\AttributeFactory $attributeFactory,
        \Commercers\AutoContent\Model\Condition\Sql\Builder $sqlBuilder,
        \Commercers\AutoContent\Service\Data $dataService,
        \Commercers\AutoContent\Model\AutoContentProcessLogFactory $autoContentProcessLogFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory

    ){
        $this->ruleFactory = $ruleFactory;
        $this->sqlBuilder = $sqlBuilder;
        $this->dataService = $dataService;
        $this->autoContentProcessLogFactory = $autoContentProcessLogFactory;
        $this->date = $date;
        $this->attributeFactory = $attributeFactory;
        $this->productCollectionFactory = $productCollectionFactory;
    }
    public function run($jobCode) {
        $autoContentProcessLog = $this->autoContentProcessLogFactory->create();
        $ruleCollection = $this->ruleFactory->create()->getCollection();
        $rules = $ruleCollection->addFieldToFilter('cron_code', array('eq' => $jobCode));
        if ($rules->getSize()) {
            $ruleId = $rules->getFirstItem()->getId();
            $rule = $this->ruleFactory->create()->load($ruleId);
            if ($rule->getIsActive() == 1) {
                $products = $this->productCollectionFactory->create();
                $products->addAttributeToSelect('*');
                $conditions = $rule->getConditions();
                $this->sqlBuilder->attachConditionToCollection($products, $conditions);
                $timeStartCron = $this->date->gmtDate();
                if($products->getSize()){
                    $limit  = (int)$rule->getlimitItemsPerExecute();

                    if($limit > 0){
                        $products->getSelect()->order('auto_content_updated_at ASC');
                        $products->getSelect()->limit($limit);
                    }
                    foreach ($products as $product) {
                        $logFile = 'RuleId: ' . $ruleId . '-successful';
                        $this->dataService->Save($product, $rule, $logFile);
                    }
                    $timeEndCron = $this->date->gmtDate();
                    $autoContentProcessLog->addData([
                        'id_profiler' => $rule->getId(),
                        'status' => 1,
                        'message' => 'Updated the attribute',
                        'executed_at' => $timeStartCron,
                        'end_at' => $timeEndCron
                    ])->save();
                }else{
                    $autoContentProcessLog->addData([
                        'id_profiler' => $rule->getId(),
                        'status' => 0,
                        'message' => 'Attribute update failed'
                    ])->save();
                }

            }
        } else {
            $autoContentProcessLog->addData([
                'id_profiler' => $rule->getId(),
                'status' => 0,
                'message' => 'Attribute update failed'
            ])->save();
        }
    }

}
