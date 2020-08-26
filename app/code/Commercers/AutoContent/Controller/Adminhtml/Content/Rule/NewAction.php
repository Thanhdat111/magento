<?php

namespace Commercers\AutoContent\Controller\Adminhtml\Content\Rule;

class NewAction extends \Commercers\AutoContent\Controller\Adminhtml\Content\Rule
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