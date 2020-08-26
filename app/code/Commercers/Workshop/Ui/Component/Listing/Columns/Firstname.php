<?php

namespace Commercers\Workshop\Ui\Component\Listing\Columns;

class Firstname extends \Magento\Ui\Component\Listing\Columns\Column 
{
     protected $_customerRepositoryInterface;

     public function __construct(
          \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
          \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
          \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
          array $components = [],
          array $data = []
     ){
          $this->_customerRepositoryInterface = $customerRepositoryInterface;
          parent::__construct($context, $uiComponentFactory, $components, $data);
     }

     public function prepareDataSource(array $dataSource) {
          if (isset($dataSource['data']['items'])) {
               foreach ($dataSource['data']['items'] as & $item) {
                    if($item['fk_customer_id']){
                        $customer = $this->_customerRepositoryInterface->getById($item['fk_customer_id']);
                        $item['firstname'] = $customer->getFirstname();
                    }
                     //Here you can do anything with actual data

               }
          }

          return $dataSource;
     }
}