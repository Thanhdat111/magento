<?php

namespace Commercers\AutoContent\Controller\Adminhtml\Content\Rule;

class Index extends \Commercers\AutoContent\Controller\Adminhtml\Content\Rule
{
    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        $this->_initAction()->_addBreadcrumb(__('AutoContent'), __('AutoContent'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('AutoContent'));
        $this->_view->renderLayout('root');
    }
}