<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Commercers\Workshop\Ui\Component\Workshop;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;

class DataProviderClaim extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    public function load($printQuery = false, $logQuery = false)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $workshop = $objectManager->create("\Commercers\Workshop\Model\WorkshopClaims");
        $workshop->addAttributeToSelect("*");
        return $workshop;

    }
}
