<?php 

namespace Commercers\AutoProductRelation\Controller\Adminhtml\SendEmail;

class SendEmail extends \Magento\Framework\App\Action\Action{
    protected $pageFactory;
    protected $sendEmailWithCrossSellProduct;
    protected $messageManager;
    public function __construct(
    \Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $pageFactory,
    \Magento\Framework\Message\ManagerInterface $messageManager,
    \Commercers\AutoProductRelation\Model\Services\CrossSell\SendEmailWithCrossSellProduct $sendEmailWithCrossSellProduct
    ) {
        $this->pageFactory = $pageFactory;
        $this->sendEmailWithCrossSellProduct = $sendEmailWithCrossSellProduct;
        $this->messageManager = $messageManager;
        return parent::__construct($context);
    }

    public function execute() {
        $params = $this->getRequest()->getParams();
        $idSendMail = $params['id'];
        $orderId = $params['orderId'];
        $customerEmail = $params['customerEmail'];
        $this->sendEmailWithCrossSellProduct->execute($idSendMail,$orderId,$customerEmail);
        $this->messageManager->addSuccess(__('Email sent successfully'));

        $this->_redirect('*/crosssell/followupemail');
    }
}