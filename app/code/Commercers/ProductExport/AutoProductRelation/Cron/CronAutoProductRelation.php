<?php

namespace Commercers\AutoProductRelation\Cron;

class CronAutoProductRelation {

    protected $sendEmailWithCrossSellProduct;
    protected $sendEmailController;

    public function __construct(
    \Commercers\AutoProductRelation\Model\Services\CrossSell\SendEmailWithCrossSellProduct $sendEmailWithCrossSellProduct,
     \Commercers\AutoProductRelation\Controller\Adminhtml\Index\TestService $sendEmailController
    ) {
        $this->sendEmailWithCrossSellProduct = $sendEmailWithCrossSellProduct;
        $this->sendEmailController = $sendEmailController;
    }

    public function execute() {

        $this->sendEmailWithCrossSellProduct->execute();
       // $this->sendEmailController->execute();
    }

}
