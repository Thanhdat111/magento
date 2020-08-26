<?php

namespace Commercers\WarehouseManagement\Block\Adminhtml\Product\Edit\Tab;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
class SelectArea extends \Magento\Framework\View\Element\Template
{

    protected $_coreRegistry = null;

    public function __construct(
        Context $context,
        UrlInterface $urlBuilder,
        Registry $registry,
        \Magento\Framework\App\ResourceConnection $resource,
        array $data = []
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->_coreRegistry = $registry;
        $this->resource = $resource;
        parent::__construct($context, $data);
    }
   
    public function getAddAjax()
    {
        return $this->urlBuilder->getUrl('backend/warehouse/addproductlinking');
    }
}