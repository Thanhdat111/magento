<?php


namespace Commercers\AutoCategory\Controller\Adminhtml;


use Magento\Backend\App\Action;

/**
 * AutoCategory rule controller
 */
abstract class Rule extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Catalog::categories';
}
