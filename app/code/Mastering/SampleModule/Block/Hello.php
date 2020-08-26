<?php


namespace Mastering\SampleModule\Block;


use Magento\Framework\View\Element\Template;
use Mastering\SampleModule\Model\ResourceModel\Items\Collection;
use Mastering\SampleModule\Model\ResourceModel\Items\CollectionFactory;

class Hello extends Template
{
    private $collectionFactory;

    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        array $data = []
    )
    {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    public function getItems()
    {
        return $this->collectionFactory->create()->getItems();
    }
    public function getFrontName()
    {
        return "Hello Thanh Dat";
    }

}