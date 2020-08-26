<?php
namespace Commercers\Workshop\Block;

class Workshop extends \Magento\Framework\View\Element\Template
{
     protected $product; 

	public function __construct(
          \Magento\Framework\View\Element\Template\Context $context,
          \Magento\Catalog\Model\Product $product
     ){
          $this->product = $product;
		parent::__construct($context);
     }
     
     public function countryWithJouleRestriction(){
          return true;
     }

     public function getDeliveryTimeBlock(){
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $cmsBlock = $objectManager->create('\Magento\Cms\Model\Block');
          $store = $objectManager->create('\Magento\Store\Model\StoreManagerInterface');
          $block = $cmsBlock->setStoreId($store->getStore()->getStoreId())->load('fn_workshop_delivery_time');
          
          $tasksCol = $objectManager->create('\Commercers\Workshop\Model\WorkshopTask')->getCollection();
          $tasksCol->addFieldToFilter('additional_status_weapon_received', array('eq' => 1));
          $tasksCol->addFieldToFilter('additional_status_task_processed', array('neq' => 1));
          $tasksCol->addFieldToFilter('status',
               array(
                    \Commercers\Workshop\Model\Source\Options\Status::STATUS_OFFER_ACCEPTED,
                    \Commercers\Workshop\Model\Source\Options\Status::STATUS_OFFER_CREATED,
                    \Commercers\Workshop\Model\Source\Options\Status::STATUS_OFFER_CREATED_FREE,
                    \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_EXECUTE,
                    \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_NEW,
                    \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_REOPENED,
                    \Commercers\Workshop\Model\Source\Options\Status::STATUS_WAITING_FOR_PAYMENT,
                    \Commercers\Workshop\Model\Source\Options\Status::STATUS_WAITING_FOR_WEAPON,
               )
          );
          $count = $tasksCol -> count();
          $time = $count/2;

          $array = array();
          $array['workshop_delivery_time'] = round($time, 0, PHP_ROUND_HALF_UP);
          $filter = $objectManager->create('\Magento\Email\Model\Template\Filter');
          $filter->setVariables($array);
          $html = $filter->filter($block->getContent());
          return $html;
     }

     public function getManufacturerHtmlSelect(){
         $attributeId = 81;
         $obManager = \Magento\Framework\App\ObjectManager::getInstance();
         $eavModel = $obManager->create('Magento\Catalog\Model\ResourceModel\Eav\Attribute');
         $eavModel->load($attributeId);
         $attributeCode=$eavModel->getAttributeCode();
         $productAttributeRepository = $obManager->create('Magento\Catalog\Model\Product\Attribute\Repository');
         $options = $productAttributeRepository->get($attributeCode)->getOptions();
         $htmlSelect = '<select id="manufacturer-select" class="manufacturer-select" style="width: 100%;">';
         $htmlSelect .= '<option value="default">Select a manufacturer</option>';
         foreach ($options as $option)
         {
             if(empty($option->getValue()))
                 continue;
             $htmlSelect .= '<option value="'.$option->getValue().'">'.$option->getLabel().'</option>';
         }
         $htmlSelect .= '</select>';
         return $htmlSelect;
     }

    public function getProductArray($manufacturerId){
        $htmlLi = '';
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productModel  = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')->addAttributeToSelect('*');
        foreach ($productModel as $product){
            if($product->getManufacturer()== $manufacturerId )
            {
                $productId = $product->getId();
                $image = $this->getBaseUrl()."pub/media/catalog/product".$product->getSmallImage();
                $htmlLi .= "<li data-id='{$product->getId()}' data-img='$image' data-name='{$product->getName()}'>";
                //$htmlLi .= '<li class="init">'.$product->getName().'</li>';
                $htmlLi .= "<img src='$image' width='5%'>" ;
                $htmlLi .= "<span>{$product->getName()} </span>";
                $htmlLi .= "</li>";
            }
        }
        return $htmlLi;

    }
}