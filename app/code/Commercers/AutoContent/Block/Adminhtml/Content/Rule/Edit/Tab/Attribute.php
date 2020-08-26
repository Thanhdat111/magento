<?php

namespace Commercers\AutoContent\Block\Adminhtml\Content\Rule\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
class Attribute extends Generic implements TabInterface
{
    /**
     * Core registry
     *
     * @var \Magento\Backend\Block\Widget\Form\Renderer\Fieldset
     */
    protected $rendererFieldset;

    /**
     * @var \Magento\Rule\Block\Conditions
     */
    protected $conditions;
    
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Rule\Block\Conditions $conditions
     * @param \Magento\Backend\Block\Widget\Form\Renderer\Fieldset $rendererFieldset
     * @param array $data
     */
    protected $resource;
    
    const AVAILABLE_ATTRIBUTE = 'auto_content/group_auto_content/meta_attributes';

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Rule\Block\Conditions $conditions,
        \Magento\Backend\Block\Widget\Form\Renderer\Fieldset $rendererFieldset,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Store\Model\StoreRepository $StoreRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->resource = $resource;
        $this->rendererFieldset = $rendererFieldset;
        $this->conditions = $conditions;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->StoreRepository = $StoreRepository;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Template');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Template');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return Generic
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getProductAttributes();
        $this->getAttributeExpression();
        $this->setTemplate('Commercers_AutoContent::attribute.phtml');
        return $this;
    }

    public function getAttributeJsonFormConfig(){
        
        $configs = array(
            
            'get_product_attribute' => $this->getUrl('backend/content_rule/saveAttribute'),
            'get_url_tester' => $this->getUrl('backend/content/tester')
        );
        
        
        return json_encode($configs);
        
    }
    public function getAllProductAttributes()
    {
        $resource =  $this->resource;
        $connection = $resource->getConnection();
        $indexerTable = $resource->getTableName('eav_attribute');
        $sql = "SELECT attribute_code,frontend_label FROM {$indexerTable} WHERE entity_type_id = '4'";
        $productAttribute = $connection->fetchAll($sql);
        return $productAttribute;
    }
    public function getProductAttributes()
    {
        $availableAttributes = $this->scopeConfig->getValue(self::AVAILABLE_ATTRIBUTE, \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
        $availableAttribute = str_replace(",","','",$availableAttributes);
        $resource =  $this->resource;
        $connection = $resource->getConnection();
        $indexerTable = $resource->getTableName('eav_attribute');
        $sql = "SELECT attribute_code,frontend_label FROM {$indexerTable} WHERE entity_type_id = '4' AND attribute_code IN ('{$availableAttribute}')";
        $productAttribute = $connection->fetchAll($sql);
        return $productAttribute;
    }
    public function getAttributeExpression(){
        $resource =  $this->resource;
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('commercers_autocontent_attribute');
        $sql = "SELECT * FROM ". $tableName;
        $result = $connection->fetchAll($sql);
        $arr = array();
        if($result){
           foreach($result as $value){
                $arr[$value['store_id']][] =  $value;
            }  
        }
        return $arr;
    }
    public function getStore(){
        $stores = $this->StoreRepository->getList();
        ksort($stores);
        $storeView = array();
        foreach ($stores as $store) {
            if($store->getName() === 'Admin')
            $store->setName('Default');
            $storeView[] = array('store_id'=>$store->getId(),'store_label'=> $store->getName());
        }
        
        return $storeView;
    }
}