<?php


namespace Commercers\AutoCategory\Controller\Adminhtml\Category\Rule;


class NewAction extends \Commercers\AutoCategory\Controller\Adminhtml\Category\Rule
{
    /**
     * New action
     *
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}