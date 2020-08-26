<?php
/**
 *  Commercers Vietnam
 *  HieuND 
 */
namespace Commercers\Workshop\Block;

class Sidebar extends \Magento\Framework\View\Element\Template
{
     protected $resource;
     protected $scopeConfig;
     protected $_productCollectionFactory;
     protected $_productRepositoryFactory;

	public function __construct(
          \Magento\Framework\View\Element\Template\Context $context,
          \Magento\Framework\App\ResourceConnection $resource,
          \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
          \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
          \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory
     ){
          $this->resource = $resource;
          $this->_productCollectionFactory = $productCollectionFactory; 
          $this->_productRepositoryFactory = $productRepositoryFactory;
          $this->scopeConfig = $scopeConfig;
          parent::__construct($context);
          $this->setTemplate('Commercers_Workshop::sidebar/custom_sidebar.phtml');
     }

     public function getTitleConfig(){
          return 'Workshop';
     }
}