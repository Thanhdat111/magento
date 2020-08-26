<?php
/**
 *  Commercers Vietnam
 *  Toan Dao 
 */
namespace Commercers\AutoProductRelation\Ui\Component\Form;
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider {

    /**
     * DataProvider constructor.
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param array $meta
     * @param array $data
     */
    protected $helperData;
    public function __construct(

        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
       
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->collection = $objectManager->create("Commercers\AutoProductRelation\Model\CrosssellFollow")->getCollection();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        
    }
    
    

    public function getData()
    { 
        $data = [];
        return $data;
    }
    
    
}