<?php

namespace Commercers\AutoProductRelation\Cron;

class CronAutoLinkeds {

    protected $relationIndexer;
    protected $insertTable;
    protected $autoLinkeds;
    public function __construct(
    \Commercers\AutoProductRelation\Model\Indexer\RelationIndexer $relationIndexer,
    \Commercers\AutoProductRelation\Service\Indexer\InsertTable $insertTable,
    \Commercers\AutoProductRelation\Model\Services\AutoLinkeds $autoLinkeds
    ) {
        $this->relationIndexer = $relationIndexer;
        $this->insertTable = $insertTable;
        $this->autoLinkeds = $autoLinkeds;
    }

    public function execute() {
       $this->relationIndexer->executeFull();
       $this->insertTable->execute();
       $this->autoLinkeds->execute();
    }

}
