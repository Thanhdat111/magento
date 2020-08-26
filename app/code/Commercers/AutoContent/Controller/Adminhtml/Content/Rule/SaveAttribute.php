<?php
namespace Commercers\AutoContent\Controller\Adminhtml\Content\Rule;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class SaveAttribute extends \Magento\Backend\App\Action { 
    
    public function __construct(
        Context $context,
        ResultFactory $resultFactory,     
       \Commercers\AutoContent\Service\SaveAttribute $saveAttribute       
    ) {
        $this->resultFactory = $resultFactory;
        $this->saveAttribute = $saveAttribute;
        parent::__construct($context);
    }
    
    public function execute(){
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $params = $this->getRequest()->getParams();
        $this->saveAttribute->Save(array($params['data']),$params['ruleId'],$params['storeId']);
        $resultJson->setData(true);
        return $resultJson;
    }
}