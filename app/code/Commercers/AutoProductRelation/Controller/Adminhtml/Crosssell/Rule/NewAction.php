<?php

namespace Commercers\AutoProductRelation\Controller\Adminhtml\Crosssell\Rule;

class NewAction extends \Commercers\AutoProductRelation\Controller\Adminhtml\Crosssell\Rule
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