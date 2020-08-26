<?php

namespace Commercers\Profilers\Controller\Adminhtml\Index\Rule;

class NewAction extends \Commercers\Profilers\Controller\Adminhtml\Index\Rule
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