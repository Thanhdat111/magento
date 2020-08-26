<?php

namespace Commercers\AutoContent\Controller\Adminhtml\Content;
use Magento\Backend\App\Action;

class Tester extends \Magento\Framework\App\Action\Action {
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Commercers\AutoContent\Model\RuleFactory $ruleFactory,
        \Commercers\AutoContent\Model\AttributeFactory $attributeFactory,
        \Commercers\AutoContent\Model\Condition\Sql\Builder $sqlBuilder,
        \Commercers\AutoContent\Service\Data $dataService,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        \Magento\Framework\App\Config\ValueFactory $configValueFactory

    ){
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->ruleFactory = $ruleFactory;
        $this->sqlBuilder = $sqlBuilder;
        $this->dataService = $dataService;
        $this->attributeFactory = $attributeFactory;
        $this->productFactory = $productFactory;
        $this->stockItemRepository = $stockItemRepository;
        $this->configValueFactory = $configValueFactory;
        parent::__construct($context);
    }
    public function execute() {
        $result = $this->resultJsonFactory->create();
        $resultPage = $this->resultPageFactory->create();
        $params = $this->getRequest()->getParams();
        $sku = $params['sku'];
        if ($sku) {
            $product = $this->productFactory->create()->loadByAttribute('sku', $sku);
            $message = null;
            if ($product == false) {
                $message = 'Cannot find product. Please re-enter the SKU';
                return $result->setData(array('message'=>$message));
            }
            $dataAttribute = $params['data'];
            $attributeDecodes = json_decode($dataAttribute, true);
            foreach ($attributeDecodes as $key => $attributeDecode) {
                $attribute[$key]['attribute_code'] = $attributeDecode['attribute']['attribute_code'];
                $attribute[$key]['expression'] = $attributeDecode['expression'];
                $attribute[$key]['store_id'] = $attributeDecode['store_id'];
                $attribute[$key]['use_default'] = $attributeDecode['use_default'] ? $attributeDecode['use_default'] : 0;
            }

            $logFile = 1;
            $resultAttribute = $this->dataService->parseContent($product, $attribute, $logFile, 1);
            $block = $resultPage->getLayout()
                    ->createBlock('Commercers\AutoContent\Block\Adminhtml\View\Tester')
                    ->setTemplate('Commercers_AutoContent::view/tester.phtml')
                    ->setAttribute(array('attribute' => $resultAttribute))
                    ->toHtml();
            return $result->setData(array('block' => $block,'message'=>$message));
        }
        return $result->setData(array('block' => null,'message'=>'SKU is not empty'));
    }

}
