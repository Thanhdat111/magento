<?php

namespace Commercers\Profilers\Controller\Adminhtml\Export\Action\Get;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Commercers\Profilers\Service\DataSource\Factory as DataSourceFactory;

class Attributes extends \Magento\Backend\App\Action {

    protected $_productFactory;
    protected $_orderFactory;
    protected $_resultPageFactory;
    protected $_stockItemRepository;
    protected $dataSourceFactory;
    protected $profilerFactory;
    public function __construct(
            Action\Context $context, \Magento\Catalog\Model\ProductFactory $productFactory, 
            \Magento\Sales\Model\OrderFactory $orderFactory,
            \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
            \Magento\Framework\View\Result\PageFactory $resultPageFactory,
            DataSourceFactory $dataSourceFactory,
            \Commercers\Profilers\Model\ProfilersFactory $profilerFactory
            
    ) {
        $this->_productFactory = $productFactory;
        $this->_orderFactory = $orderFactory;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_stockItemRepository = $stockItemRepository;
        $this->dataSourceFactory = $dataSourceFactory;
        $this->profilerFactory = $profilerFactory;
        parent::__construct($context);
    }

    public function execute() {
        $profilerId = $this->getRequest()->getParam('profiler_id');
        $id = $this->getRequest()->getParam('id');

        $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        if ($profilerId && $id)
            return $result->setContents($this->_renderDataBlockHtml($profilerId, $id));

        return $result->setContents('');
    }

    protected function _renderDataBlockHtml($profilerId, $objectId) {

        $profiler = $this->profilerFactory->create()->load($profilerId);

        $dataSource = $this->dataSourceFactory->get($profiler->getDataSource());

        $item = $dataSource->getItemById($objectId, $profiler);
        $resultPage = $this->_resultPageFactory->create();
        if ($item == false) {
            $html = $resultPage->getLayout()
                    ->createBlock('Commercers\Profilers\Block\Adminhtml\Review\Export\Error')
                    ->setTemplate('Commercers_Profilers::export/product/error.phtml')
                    ->toHtml();
        } else {

            $html = $resultPage->getLayout()
                    ->createBlock('Commercers\Profilers\Block\Adminhtml\Review\Export\Result')
                    ->setTemplate('Commercers_Profilers::export/product/attribute.phtml')
                    ->setData($item)
                    ->toHtml();
        }
        return $html;
    }

}
