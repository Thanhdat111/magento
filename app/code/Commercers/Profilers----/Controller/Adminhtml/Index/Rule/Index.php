<?php

namespace Commercers\Profilers\Controller\Adminhtml\Index\Rule;

class Index extends \Commercers\Profilers\Controller\Adminhtml\Index\Rule
{
    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        $this->_initAction()->_addBreadcrumb(__('Profilers'), __('Profilers'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Profilers'));
        $this->_view->renderLayout('root');
    }
}