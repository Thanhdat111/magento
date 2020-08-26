<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_UiForm
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Commercers\WarehouseManagement\Ui\Component\Form;
 
use Commercers\WarehouseManagement\Model\ResourceModel\Warehouse\CollectionFactory;
use Commercers\WarehouseManagement\Model\ResourceModel\AreaWarehouse\CollectionFactory as AreaCollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     */
    protected $_loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $warehouseCollectionFactory,
        AreaCollectionFactory $areaWarehouseCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $warehouseCollectionFactory->create();
        $this->areaCollection = $areaWarehouseCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $item) {
            $this->_loadedData[$item->getId()] = $item->getData();
            $areaWarehouse = $this->areaCollection->addFieldToFilter('warehouse_id',array('eq'=>$item->getId()));
            $this->_loadedData[$item->getId()]['warehouse_dynamic_row'] = $areaWarehouse->getData();
        }
        return $this->_loadedData;
    }
}