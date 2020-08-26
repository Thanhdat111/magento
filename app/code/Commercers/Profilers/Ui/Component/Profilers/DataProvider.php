<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Commercers\Profilers\Ui\Component\Profilers;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;

class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    public function load($printQuery = false, $logQuery = false)
    {
        if (!$this->isLoaded()) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $products = $objectManager->create("\Commercers\Profilers\Model\Profilers");
            $products->addAttributeToSelect("*");

            //get name, price, sku, image
            foreach($products as $product){
                $this->addItem(($product));
            }

            $this->_setIsLoaded(true);
        }
        return $this;
    }
}
