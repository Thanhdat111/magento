<?php
namespace Commercers\AutoProductRelation\Controller\Product;

class View extends \Magento\Framework\App\Action\Action {
    public function execute()
    {
        echo  $this->getRequest()->getFullActionName();
        echo 1; exit;
    }
}