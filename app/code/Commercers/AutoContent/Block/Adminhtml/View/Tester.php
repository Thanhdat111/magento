<?php
namespace Commercers\AutoContent\Block\Adminhtml\View;
 
use Magento\Framework\View\Element\Template;
 
class Tester extends Template
{
  public function __construct(
        Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
        array $data = [])
    {
        $this->storeManagerInterface = $storeManagerInterface;
        parent::__construct($context, $data);
    }
 
  protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
 
  public function getLabelStore($storeId)
  {
      $store =  $this->storeManagerInterface->getStore($storeId);
      $storeName = $store->getName();
      if($storeName == 'Admin')
      $storeName = 'Default';
      return $storeName;
  }
}