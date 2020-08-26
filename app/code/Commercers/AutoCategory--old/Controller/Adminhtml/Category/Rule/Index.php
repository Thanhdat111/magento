<?php


namespace Commercers\AutoCategory\Controller\Adminhtml\Category\Rule;


class Index extends \Commercers\AutoCategory\Controller\Adminhtml\Category\Rule
{
    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        $this->_initAction()->_addBreadcrumb(__('Auto Category Rules'), __('Auto Category Rules'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Auto Category Rules'));
        $this->_view->renderLayout('root');
    }
}