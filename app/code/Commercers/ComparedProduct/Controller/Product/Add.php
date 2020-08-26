<?php
namespace  Commercers\ComparedProduct\Controller\Product;


class Add extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute()
    { echo 1; exit;
        return $this->_pageFactory->create();
    }
}
