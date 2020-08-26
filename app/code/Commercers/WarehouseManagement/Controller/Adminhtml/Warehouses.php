<?php

namespace Commercers\WarehouseManagement\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Commercers\WarehouseManagement\Model\WarehouseFactory;
use Commercers\WarehouseManagement\Model\AreaWarehouseFactory;
use Magento\Framework\Session\SessionManagerInterface;

abstract class Warehouses extends \Magento\Backend\App\Action
{
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        WarehouseFactory $warehouseFactory,
        AreaWarehouseFactory $areaWarehouseFactory,
        SessionManagerInterface $coreSession
    ) {
       parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->warehouseFactory = $warehouseFactory;
        $this->areaWarehouseFactory = $areaWarehouseFactory;
        $this->coreSession = $coreSession;
    }

    /**
     * News access rights checking
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Commercers_WarehouseManagement::manage_warehouse');
    }
}