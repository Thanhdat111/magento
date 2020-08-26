<?php

namespace Commercers\AutoProductRelation\Controller\Adminhtml\Crosssell\Rule;

class Index extends \Commercers\AutoProductRelation\Controller\Adminhtml\Crosssell\Rule
{
    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        //echo $this->getRequest()->getFullActionName();exit;
        $this->_initAction()->_addBreadcrumb(__('AutoProductRelation'), __('AutoProductRelation'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('AutoProductRelation'));
        $this->_view->renderLayout('root');
    }
}