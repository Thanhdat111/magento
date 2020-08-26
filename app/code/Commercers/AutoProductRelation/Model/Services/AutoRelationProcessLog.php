<?php

namespace Commercers\AutoProductRelation\Model\Services;

class AutoRelationProcessLog {

    protected $autoRelationProcessLogFactory;
    protected $date;
    protected $productRepositoryInterface;
    public function __construct(
    \Commercers\AutoProductRelation\Model\AutoRelationProcessLogFactory $autoRelationProcessLogFactory,
    \Magento\Framework\Stdlib\DateTime\DateTime $date,
    \Magento\Catalog\Api\ProductRepositoryInterface $productRepositoryInterface

    ) {
        $this->autoRelationProcessLogFactory = $autoRelationProcessLogFactory;
        $this->date = $date;
        $this->productRepositoryInterface = $productRepositoryInterface;
    }

    public function execute($dataLogs) {
        //print_r($dataLogs);exit;
        foreach($dataLogs as $dataLog){
            foreach($dataLog as $key=> $valueProductId){
              $productId = $key;
              $sku = $this->getSkuProduct($productId);
              foreach($valueProductId as $key=> $valueRuleId){
                  $ruleId = $key;
                  foreach($valueRuleId as $valueLinkedId){
                    if($productId != $valueLinkedId['entity_id'])
                        $skuLinked[] = $this->getSkuProduct($valueLinkedId['entity_id']);
                  }
              }
              $countLinked = count($skuLinked);
              $this->Save($productId,$sku,$ruleId,$skuLinked,$countLinked);
              $skuLinked = array();
            }
        }
    }

    public function Save($productId,$sku,$ruleId,$skuLinked,$countLinked) {
        $jsonLinkedProductIds = json_encode(['SkuLinked' => $skuLinked]);
        $autoRelationProcessLog = $this->autoRelationProcessLogFactory->create();
        $autoRelationProcessLog->addData([
            'product_id' => $productId,
            'sku' =>$sku,
            'rule_id' => $ruleId,
            'created_at' => $this->date->gmtDate(),
            'linkedIds' => $jsonLinkedProductIds,
            'count' => $countLinked,
        ])->save();
    }
    public function getSkuProduct($productId){
        $product = $this->productRepositoryInterface->getById($productId);
        $productSku = $product->getSku();
        return $productSku;
    }
}
