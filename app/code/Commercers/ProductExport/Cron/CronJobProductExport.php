<?php

namespace Commercers\ProductExport\Cron;

use \Psr\Log\LoggerInterface;

class CronJobProductExport 
{ 
    
    protected $_export;

    public function __construct(
        \Commercers\ProductExport\Service\Export $export
    )
    {
        $this->_export = $export;
    }
    
    public function execute(){
        $this->_export->execute();
    }
}