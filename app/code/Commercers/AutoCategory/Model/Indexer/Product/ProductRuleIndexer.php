<?php
/**
 * Copyright © Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * See COPYING.txt for license details.
 */
namespace Commercers\AutoCategory\Model\Indexer\Product;

use Magento\Framework\Exception\LocalizedException;
use Magento\Catalog\Model\Product;
use Commercers\AutoCategory\Model\Indexer\AbstractIndexer;

/**
 * Product rule indexer
 */
class ProductRuleIndexer extends AbstractIndexer
{
    /**
     * Execute partial indexation by ID list
     *
     * @param int[] $ids
     * @return void
     */
    protected function doExecuteList($ids)
    {
        $this->indexBuilder->reindexByIds(array_unique($ids));
        $this->getCacheContext()->registerEntities(Product::CACHE_TAG, $ids);
    }

    /**
     * Execute partial indexation by ID
     *
     * @param int $id
     * @throws LocalizedException
     * @return void
     */
    protected function doExecuteRow($id)
    {
        $this->indexBuilder->reindexById($id);
    }
}
