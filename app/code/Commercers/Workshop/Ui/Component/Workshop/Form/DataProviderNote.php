<?php
namespace Commercers\Workshop\Ui\Component\Workshop\Form;
 
class DataProviderNote extends \Magento\Ui\DataProvider\AbstractDataProvider
{
     /**
          * @param string $name
          * @param string $primaryFieldName
          * @param string $requestFieldName
          * @param array $meta
          * @param array $data
          */
     public function __construct(
          $name,
          $primaryFieldName,
          $requestFieldName,
          array $meta = [],
          array $data = []
     ) {
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $this->collection = $objectManager->create('\Commercers\Workshop\Model\WorkshopNote')->getCollection();
          parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
     }

     /**
          * Get data
          *
          * @return array
          */
     public function getData()
     {
          //return du lieu ra form edit
          $data = [];
          foreach($this->getCollection()->getItems() as $item){
               $data[$item->getId()] = array(
                    'addnote' => $item->getData()
                    );
               
          }
          return $data;
     }
}