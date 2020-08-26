<?php
namespace Commercers\Workshop\Controller\Frontend;

use Magento\Framework\Controller\ResultFactory;

use Magento\Framework\Filesystem;

class WorkshopProduct  extends \Magento\Framework\App\Action\Action {

    public function __construct(
        \Commercers\Workshop\Block\Workshop $workshop,
        ResultFactory $resultFactory,
        \Magento\Framework\App\Action\Context $context
    ) {

        parent::__construct($context);
        $this->resultFactory = $resultFactory;
        $this->workshop = $workshop;
    }

    public function execute(){
        $manuf= $this->getRequest()->getParam('manuf');
        $result['product'] = $this->workshop->getProductArray($manuf);
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($result);
        return $resultJson;
    }
}
