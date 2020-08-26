<?php 

namespace Commercers\AutoProductRelation\Controller\SendEmail;

class SendEmail extends \Magento\Framework\App\Action\Action{
    protected $pageFactory;
    protected $sendEmailWithCrossSellProduct;

    public function __construct(
    \Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $pageFactory,
	\Commercers\AutoProductRelation\Model\Services\CrossSell\SendEmailWithCrossSellProduct $sendEmailWithCrossSellProduct
    ) {
        $this->pageFactory = $pageFactory;
        $this->sendEmailWithCrossSellProduct = $sendEmailWithCrossSellProduct;
        return parent::__construct($context);
    }

    public function execute() {
        $this->sendEmailWithCrossSellProduct->execute();
        
    }
}