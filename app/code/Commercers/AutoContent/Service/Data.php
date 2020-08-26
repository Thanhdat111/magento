<?php

namespace Commercers\AutoContent\Service;
use \Magento\CatalogInventory\Api\StockRegistryInterface;
class Data
{

    const XML_PATH_ENABLED = 1;
    const XML_PATH_SEPARATOR_MULTISELECTATTRIBUTE = 1;
    const XML_PATH_AVAILABLE_ATTRIBUTES = 1;
    const REGEXPRESSION_BACKTETS = "/{([A-Za-z\_\[\]0-9]+)}/";
    const REGEXPRESSION_CONDITION = "/\#(.*?)\#/";

    public function __construct(
    \Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
    \Magento\Store\Model\StoreRepository $StoreRepository,
    \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
    StockRegistryInterface $stockRegistry,
    \Magento\Catalog\Model\Product $product,
    \Magento\Catalog\Model\Category $category,
    \Magento\Catalog\Model\ProductFactory $productFactory,
    \Magento\Eav\Model\Entity $eavEntity,
    \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $eavAttributeSetCollectionFactory,
    \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrencyInterface,
    \Commercers\AutoContent\Model\RuleFactory $ruleFactory,
    \Commercers\AutoContent\Model\AttributeFactory $attributeFactory
    ) {
        $this->storeManagerInterface = $storeManagerInterface;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->product = $product;
        $this->category = $category;
        $this->productFactory = $productFactory;
        $this->stockRegistry = $stockRegistry;
        $this->eavEntity = $eavEntity;
        $this->eavAttributeSetCollectionFactory = $eavAttributeSetCollectionFactory;
        $this->priceCurrencyInterface = $priceCurrencyInterface;
        $this->ruleFactory = $ruleFactory;
        $this->attributeFactory = $attributeFactory;
        $this->StoreRepository = $StoreRepository;
    }
        public function Save($product, $template, $logFile){
        $attributes = $this->parseContent($product, $template, $logFile);
        $allStore =  $this->StoreRepository->getList();
        //echo "<pre>";print_r($attributes);exit;
        foreach($allStore as $store){
            foreach($attributes as $key => $attribute){
                if ($store->getId() == $key) {
                    foreach ($attribute as $keyAttribute => $value) {
                        if ($value == '[USE_DEFAULT]') {
                            $product->setData($keyAttribute, NULL)->save();
                        } else {
                            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                            $objectManager->get(\Magento\Catalog\Model\Product\Action::class)
                                    ->updateAttributes(array($product->getId()), $attribute, $store->getId());
                        }
                    }
                }
            }
        }
    }

    public function getSpecialAttributes(){
        return array(
            'image_label' =>__('Image Label')
        );
    }


    protected $_previousAttributeValues = array();

    public function getCategoryTree($asHash)

    {
        $rootId = $this->storeManagerInterface->getStore(0)->getRootCategoryId();
//        $tree = array(
//            '' => array(
//                'label' => __("None"),
//                'path' => array()
//            )
//        );
        $collection = $this->categoryCollectionFactory->create()->addNameToResult();
        $pos = array();

        foreach ($collection as $cat) {
            // echo "<pre>";print_r($cat->getData());exit;
            $path = explode('/', $cat->getPath());
            if ((!$rootId || in_array($rootId, $path)) && $cat->getLevel() ) {
                $tree[$cat->getId()] = array(
                    'label' => str_repeat('--', $cat->getLevel()) . $cat->getName(),
                    'value' => $cat->getId(),
                    'path' => $path,
                );
            }
            $pos[$cat->getId()] = $cat->getPosition();
        }

        foreach ($tree as $catId => $cat) {
            $order = array();
            foreach ($cat['path'] as $id) {
                if (isset($pos[$id])) {
                    $order[] = $pos[$id];
                }
            }
            $tree[$catId]['order'] = $order;
        }
        usort($tree, array($this, 'compareCategory'));

        if ($asHash) {
            $hash = array();
            foreach ($tree as $v) {
                $hash[$v['value']] = $v['label'];
            }
            $tree = $hash;
        }
        return $tree;

    }

    public function compareCategory($a, $b)
    {
        foreach ($a['path'] as $i => $id) {
            if (!isset($b['path'][$i])) {
                return 1;
            }
            if ($id != $b['path'][$i]) {
                $p = isset($a['order'][$i]) ? $a['order'][$i] : 0;
                $p2 = isset($b['order'][$i]) ? $b['order'][$i] : 0;
                return ($p < $p2) ? -1 : 1;
            }
        }
        return ($a['value'] == $b['value']) ? 0 : -1;
    }


    public function getContentAttributes($ruleId = null)
    {
        if ($ruleId) {
            $availableAttributes = self::XML_PATH_AVAILABLE_ATTRIBUTES;
            if ($availableAttributes)
                $availableAttributes = explode(",", $availableAttributes);
                $attributes = $this->attributeFactory->create()
                ->getCollection()
                ->addFieldToFilter('rule_id', $ruleId);
//                ->addFieldToFilter('attribute_code', array('in' => $availableAttributes));
            if ($attributes->getSize()) {
                $collectionArray = $attributes->load()->toArray();
                return $collectionArray['items'];
            }
        }
        return null;
    }

    public function parseSimpleContent($product, $templateContent, $storeId, $logFile)
    {
        $regExpressForParseBasket = self::REGEXPRESSION_BACKTETS;
        //$regExpressForParseBasket = "/\{([A-Z a-z\_\|\s\&\$\(\)\[\]<\!\=>\'0-9]+)\}/";
        //preg_match_all($regExpressForParseBasket, $templateContent, $vars);
        $vars = array();
        preg_match_all($regExpressForParseBasket, $templateContent, $vars);
        if (!$vars[1]) {
            return $templateContent;
        }
        $vars = $vars[1];
        //print_r($vars);exit;
        $clearContent = $templateContent;
        foreach ($vars as $var) {
            if (strpos($var, "[") !== false) {
                $mTokens = array();
                $mTok = strtok($var, '[]');
                while ($mTok) {
                    $mTokens[] = $mTok;
                    $mTok = strtok('[]');
                }
                $offsetValue = !empty($mTokens[1]) ? $mTokens[1] : null;
                $mAttributeCode = !empty($mTokens[0]) ? $mTokens[0] : null;
                $value = $this->getAttributeValue($product, $mAttributeCode, $storeId, null, $offsetValue);
            } else {
                $value = $this->getAttributeValue($product, $var, $storeId);
            }
            //$value = $this->getAttributeValue($product, $var, $storeId);
            if (empty($value)) {
                $storeContents['none_value_attribute'][$var][] = $var;
            }
            if ($value)
                $clearContent = str_replace('{' . $var . '}', $value, $clearContent);
        }
        $clearContent = preg_replace_callback(
            '/\[.*?\]/',
            create_function('$m', 'if(strpos($m[0], "}")) return ""; return substr($m[0],1,-1);'),
            $clearContent);

        // remove non-processed variables
        $clearContent = preg_replace($regExpressForParseBasket, '', $clearContent);
        /*

        $limitLength = !empty($attribute['length_limit']) ? $attribute['length_limit'] : 2000;

        if (mb_strlen($clearContent) > $limitLength) {

            Mage::log("Parse content for  {$var} : string length over limit , full string : {$clearContent}", null, $logFile, true);

            $clearContent = mb_substr($clearContent, 0, (int)$limitLength);


        }

        */
        return $clearContent;

    }


    public function parseContent($product, $template, $logFile,$tester = null)
    {
        $helper = $this->getConfig();
        $contents = array();
        $storeIds = array();
        if($tester == 1)
            $attributes = $template;
        else
            $attributes = $this->getContentAttributes($template->getId());
        foreach($attributes as $attribute){
            $storeIds[] =  $attribute['store_id'];
        }
        $stores = array_unique($storeIds);
        if (!$stores) {
            $allStore =  $this->StoreRepository->getList();
            $stores = array();
            foreach ($allStore as $store) {
                $stores[] = $store->getId();
            }
        }
        foreach ($stores as $idx => $storeId) {
            if (!$helper) {
                unset($stores[$storeId]);
            }
        }
        foreach ($stores as $storeId) {
            $storeContents = array();
            $product->setStoreId($storeId);
//            $stockItem = $this->stockRegistry->getStockItem($product->getId(), $storeId);
//                if ($stockItem->getIsInStock() != 1) {
//                    $contents[$storeId] = false;
//                    continue;
//                }
            if ($attributes) {
                $this->_previousAttributeValues = array();
                foreach ($attributes as $index => $attribute) {
                    if($attribute['store_id'] == $storeId){
                    $attributeCode = $attribute['attribute_code'];
                    $templateContent = $attribute['expression'];
                    if ($attribute['use_default'] == 1) {
                        //$storeContents[$attributeCode] = $product->getData($attributeCode);
                        $storeContents[$attributeCode] = '[USE_DEFAULT]';
                        continue;
                    }
                    $productOfStore = $this->productFactory->create()
                        ->setStoreId($storeId)
                        ->load($product->getId());
                    if (isset($storeContents[$attributeCode])) {
                        if (!isset($this->_previousAttributeValues[$storeId])) {
                            $this->_previousAttributeValues[$storeId] = array();
                        }
                        $this->_previousAttributeValues[$storeId][$attributeCode] = $storeContents[$attributeCode];
                    }

                    $clearContent = $this->parseMixContent($productOfStore, $templateContent, $storeId, $logFile, $attributeCode);
//                    $limitLength = !empty($attribute['length_limit']) ? $attribute['length_limit'] : 2000;
//
//                    if (mb_strlen($clearContent) > $limitLength) {
//                        //Mage::log("Parse content for  {$attributeCode} : string length over limit , full string : {$clearContent}", null, 'limit_content.log', true);
//                        $clearContent = mb_substr($clearContent, 0, (int)$limitLength);
//                    }
                    $storeContents[$attributeCode] = $clearContent;
                    }
                }
                $contents[$storeId] = $storeContents;
            }
        }
        return $contents;
    }


    public function parseConditionTemplate($product, $templateContent, $storeId, $logFile, $attributeCode = null)
    {
        $conditionData = preg_split('#\|#', $templateContent);
        if (count($conditionData) >= 2) {
            $condition = $conditionData[0];
            try {
                $tokens = array();
                $tok = strtok($condition, '()');
                while ($tok) {
                    $tokens[] = $tok;
                    $tok = strtok('()');
                }
                $conditionValues = array();
                $conditionOperators = array();
                $attributesInConditions = array();

                foreach ($tokens as $token) {
                    $token = trim($token);
                    if (!in_array(mb_strtoupper($token), array('OR', 'AND'))) {
                        if ($token)
                            $conditionValues[$token] = $this->evalCondition($token, $product, $storeId, $attributesInConditions, $logFile);
                    } else {
                        $conditionOperators[] = $token;
                    }
                }
                $conditionCode = '';
                $cnt = 0;

                foreach ($conditionValues as $cV) {

                    if ($cV) {
                        $conditionCode .= ' true ';
                    }
                    if($cV === false){
                        $conditionCode .= ' false ';
                    }

                    if (!empty($conditionOperators[$cnt]) && strtoupper($conditionOperators[$cnt]) == 'AND')
                        $conditionCode .= ' && ';
                    if (!empty($conditionOperators[$cnt]) && strtoupper($conditionOperators[$cnt]) == 'OR')
                        $conditionCode .= ' || ';
                    $cnt++;
                }
                $valueOfCondition = false;
                $conditionCode = '$valueOfCondition=' . $conditionCode . ";";
                if (!isset($conditionData[2])) {
                    $conditionData[2] = '';
                    if (count($attributesInConditions) == 1) {
                        $conditionData[2] = "{" . $attributeCode . "}";
                        //$conditionData[2] =
                        //$this->getAttributeRawValueByStore($product, $attributesInConditions[0], $storeId);
                    }
                }

                @eval($conditionCode);
                $gotValue = '';
                if ($valueOfCondition) {
                    $gotValue = trim($conditionData[1]);
                } else {
                    $gotValue = trim($conditionData[2]);
                }
                //$gotValue = $attributesInConditions[0];
                //echo $gotValue;
                $value = $this->parseSimpleContent($product, $gotValue, $storeId, $logFile);
                //echo $value;
                //exit;
                /*

                if (strpos($gotValue, "'") === false) {
                    $value = $this->getAttributeValue($product, $gotValue, $storeId);
                } else {
                    $value = str_replace("'", "", $gotValue);
                }
                */
                return $value;

            } catch (Exception $ex) {
                Mage::log("Can not parse {$var} for {$attributeCode} : " . $ex->getMessage(), null, $logFile, true);

            }
        }

    }


    public function parseMixContent($product, $templateContent, $storeId, $logFile, $attributeCode = null)
    {
        $storeContents = array();
        $storeContents['none_value_attribute'] = array();
        $regExpressForParseBasket = self::REGEXPRESSION_BACKTETS;
        $regExpressForParseCondBasket = self::REGEXPRESSION_CONDITION;
        $vars = array();
        preg_match_all($regExpressForParseBasket, $templateContent, $vars);
        $varConditions = array();
        preg_match_all($regExpressForParseCondBasket, $templateContent, $varConditions);
        //print_r($vars);

        if (!$vars[1] && !$varConditions[1]) {
            return $templateContent;
        }

        $varConditions = !empty($varConditions[1]) ? $varConditions[1] : array();
        foreach ($varConditions as $varCondition) {
            $cValue = $this->parseConditionTemplate($product, $varCondition, $storeId, $logFile, $attributeCode);
            //if ($cValue) {
            $templateContent = str_replace('#' . $varCondition . '#', $cValue, $templateContent);
            //}
        }

        $vars = !empty($vars[1]) ? $vars[1] : array();
        $clearContent = $templateContent;
        $product->setStoreId($storeId);
        $storeContents['none_value_attribute'][$attributeCode] = array();
        foreach ($vars as $var) {
            $tokens = array();
            //for multiple attribute
            if (strpos($var, "[") !== false) {
                $mTokens = array();
                $mTok = strtok($var, '[]');
                while ($mTok) {
                    $mTokens[] = $mTok;
                    $mTok = strtok('[]');
                }
                $offsetValue = !empty($mTokens[1]) ? $mTokens[1] : null;
                $mAttributeCode = !empty($mTokens[0]) ? $mTokens[0] : null;
                $value = $this->getAttributeValue($product, $mAttributeCode, $storeId, null, $offsetValue);
            } else {
                $value = $this->getAttributeValue($product, $var, $storeId);
            }
            if (empty($value)) {
                $storeContents['none_value_attribute'][$attributeCode][] = $var;
            }
            if ($value)
                $clearContent = str_replace('{' . $var . '}', $value, $clearContent);
        }
//        $clearContent = preg_replace_callback(
//            '/\[.*?\]/',
//            create_function('$m', 'if(strpos($m[0], "}")) return ""; return substr($m[0],1,-1);'),
//            $clearContent);
        // remove non-processed variables
        $clearContent = preg_replace($regExpressForParseBasket, '', $clearContent);
        return $clearContent;
    }

    protected function isStaticValue($value = null)
    {
        if (($value) == null || $value == '') return false;
        if (strpos($value, "'") !== false) {
            return true;
        }
        if (is_int($value)) {
            return true;
        }
        if (is_numeric($value)) {
            return true;
        }
        return false;
    }


    protected function evalCondition($condition, $product, $storeId, &$attributeCodes = array(), $logFile)
    {
        $operators = array('!=', '>=', '<=', '>', '<', '=');
        foreach ($operators as $operator) {
            $conditionAttributes = preg_split("#{$operator}#", $condition);
            if (count($conditionAttributes) == 2) {
                if (count($conditionAttributes) == 2) {
                    $attributeA = trim($conditionAttributes[0]);
                    $attributeB = trim($conditionAttributes[1]);
                    if ($this->isStaticValue($attributeA)) {
                        $valueA = $value = str_replace("'", "", $attributeA);
                    } else {
                        //$valueA = $this->getAttributeRawValueByStore($product, $attributeA, $storeId);
                        $attributeA = "{" . $attributeA . "}";
                        $valueA = $this->parseSimpleContent($product, $attributeA, $storeId, $logFile);
                        //public function parseSimpleContent($product, $templateContent, $storeId, $logFile)
                        $attributeCodes[] = $attributeA;
                        //$valueA = $this->getAttributeValue($product, $attributeA, $storeId);
                        //echo $valueA; exit;
                    }
                    if ($this->isStaticValue($attributeB)) {
                        $valueB = str_replace("'", "", $attributeB);
                    } else {
                        //getAttributeValue($p, $code, $storeId, $logFile = null , $offsetValue = null)
                        //$valueB = $this->getAttributeRawValueByStore($attributeB, $product, $storeId);
                        //$valueB = $this->getAttributeRawValueByStore($product, $attributeB, $storeId);
                        $attributeA = "{" . $attributeB . "}";
                        $valueB = $this->parseSimpleContent($product, $attributeB, $storeId, $logFile);
                        $attributeCodes[] = $attributeB;
                        //$valueB = $this->getAttributeValue($product, $attributeB, $storeId);
                    }
                    return $this->compareValue($valueA, $valueB, $operator);
                }
                break;
            }
        }
        return false;
    }
    /*protected function getAttributeRawValueByStore($attributeCode, $product, $storeId)
    {
        $rawValue = Mage::getResourceModel('catalog/product')->getAttributeRawValue($product->getId(), $attributeCode, $storeId);
        return $rawValue;
    }*/
    protected function getAttributeValue($p, $code, $storeId, $logFile = null, $offsetValue = null)
    {
        $value = $this->getAttributeRawValueByStore($p, $code, $storeId, $logFile = null, $offsetValue);
        $store =  $this->storeManagerInterface->getStore($storeId);
        $currency = $store->getCurrentCurrency()->getCode();
        switch ($code) {
            case 'price':
                // $value = $store->convertPrice($rawValue, true, false);
                //$rawValue = $p->getPrice();
                
                $value = $this->priceCurrencyInterface->convertAndFormat($value,true,true,null,$currency);
                break;
            case 'special_price':
                //$rawValue = $p->getSpecialPrice();
                $value = $this->priceCurrencyInterface->convertAndFormat($value, true,true,null,$currency);
                //var_dump($value);exit;
                break;
            case 'final_price':
                //$rawValue = $p->getFinalPrice();
                $value =  $this->priceCurrencyInterface->convertAndFormat($p->getFinalPrice(),true,true,null,$currency);
                break;
            case 'final_price_incl_tax':
                //$rawValue = $p->getFinalPrice();
                $value =  $this->priceCurrencyInterface->convertAndFormat($p->getFinalPrice(), true,true,null,$currency);
                break;
            default:
                break;
        }
        return $value;
    }

    protected $_attributeRawValues = array();
    protected function getAttributeRawValueByStore($p, $code, $storeId, $logFile = null, $offsetValue = null)
    {
        if (!$storeId && $p)
            $storeId = $p->getStoreId();
        if (isset($this->_previousAttributeValues[$storeId][$code])) {
            return $this->_previousAttributeValues[$storeId][$code];
        }
        if ($p && $storeId) {
            if (!empty($this->_attributeRawValues[$p->getId()][$storeId][$code])) {
                return $this->_attributeRawValues[$p->getId()][$storeId][$code];
            }
        }
        $value = $code;
        if (!$storeId)
        $storeId = $p->getStoreId();
        $store =  $this->storeManagerInterface->getStore($storeId);
//        echo $code;exit;
        if($value != 'category_ids')
        $rawValueAttribute = $this->product->getResource()
                    ->getAttributeRawValue($p->getId(), $value, $storeId);
        if(isset($rawValueAttribute[$code])){
            $rawValue = $rawValueAttribute[$code];
        }else{
            $rawValue = null;
        }
        //echo $code .'|'; print_r($rawValue );
        if (!$rawValue) {
            $rawValue = $p->getData($code);

        }
        switch ($code) {
            case 'attribute_set':
                $value = '';
                $entityTypeId =  $this->eavEntity
                                ->setType('catalog_product')
                                ->getTypeId();
                if($entityTypeId)
                $attributeSetCollection = $this->eavAttributeSetCollectionFactory->create()
                    ->setEntityTypeFilter($entityTypeId)
                    ->addFieldToFilter('attribute_set_id', $p->getAttributeSetId())
                    ->addFieldToSelect('*');
                if ($attributeSetCollection && $attributeSetCollection->getSize()) {
                    $value = $attributeSetCollection->getFirstItem()->getAttributeSetName();
                }
                //$attributeSetCollection->toOptionArray()
                break;
            case 'category_ids':
                $value = '';
                $category = $p->getCategoryIds();
                //echo "<pre>";print_r($category);exit;
                if ($category) {
                    foreach($category as $value)
                    //$value = $p->getCategory()->setStoreId($storeId)->getName();
                    $valueCategory[] =   $this->category->getResource()->getAttributeRawValue($value, 'name', $storeId);
                } else {
                    $categoryItems = $p->getCategoryCollection()->load()->getIterator();
                    $category = current($categoryItems);
                    
                    if ($category) {
                        //$category = Mage::getModel('catalog/category')->load($category->getId());
                        $value =  $this->category->getResource()->getAttributeRawValue($category->getId(), 'name', $storeId);
                        //$value = $category->setStoreId($storeId)->getName();
                    }
                }
                if($valueCategory){
                   $value = implode(', ', $valueCategory);
                }
                break;
            case 'store_view':
                $value = $store->getName();
                break;
            case 'store':
                $value = $store->getGroup()->getName();
                break;
            case 'website':
                $value = $store->getWebsite()->getName();
                break;

            default:
                //$value = $p->getData($code);
                
                $value = $rawValue;
                if (is_numeric($value)) {
                    // flat enabled
                    if ($p->getData($code . '_value')) {
                        $value = $p->getData($code . '_value');
                    } else {
                        $attr = $p->getResource()->getAttribute($code);
                        if ($attr) { // type dropdown
                            $optionText = $attr->setStoreId($storeId)->getSource()->getOptionText($value);
                            $value = $optionText ? $optionText : $value;
                        }
                    }
                } // multiple select
                elseif (preg_match('/^[0-9,]+$/', $value)) {
                    $attr = $p->getResource()->getAttribute($code);
                    $separatorMS = $this->getMulipleSelectSeparator($p->getStoreId());
                    if ($attr) {
                        $ids = explode(',', $value);
                        $value = '';
                        if ($offsetValue) {
                            $idValue = !empty($ids[$offsetValue - 1]) ? $ids[$offsetValue - 1] : '';
                            $value = $attr->setStoreId($storeId)->getSource()->getOptionText($idValue);
                        } else {
                            foreach ($ids as $id) {
                                $value .= $attr->setStoreId($storeId)->getSource()->getOptionText($id) . "{$separatorMS} ";
                            }
                            $value = substr($value, 0, -2);
                        }
                    }
                }
        }
        // end switch
        // remove tags
        $value = strip_tags($value);
        // remove spases
        $value = preg_replace('/\r?\n/', ' ', $value);
        $value = preg_replace('/\s{2,}/', ' ', $value);
        // convert possible special codes like '<' to safe html codes
        $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
        $value = htmlspecialchars($value);
        // check if price = 0.00
        if ($value ===  $this->priceCurrencyInterface->convert(0, $store)) {
            $value = '';
        }
        if ($p && $storeId) {
            if (!empty($this->_attributeRawValues[$p->getId()])) {
                $this->_attributeRawValues[$p->getId()] = array();
            }
            if (!empty($this->_attributeRawValues[$p->getId()][$storeId])) {
                $this->_attributeRawValues[$p->getId()][$storeId] = array();
            }

            $this->_attributeRawValues[$p->getId()][$storeId][$code] = $value;
        }
        return $value;
    }

    public function isActive()
    {
        return 1;
    }


    public function getMulipleSelectSeparator($storeId = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_SEPARATOR_MULTISELECTATTRIBUTE, $storeId);

    }


    public function compareValue($valueA, $valueB, $operator)
    {
        switch ($operator) {
            case '>' :
                return $valueA > $valueB;
            case '<' :
                return $valueA < $valueB;
            case '>=' :
                return $valueA >= $valueB;
            case '<=' :
                return $valueA <= $valueB;
            case '=' :
                return $valueA == $valueB;
            case '!=' :
                return $valueA != $valueB;
        }
        return false;

    }


    public function getConfig()
    {
        return true;
    }


}