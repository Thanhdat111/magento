<?php

namespace Commercers\AutoContent\Controller\Adminhtml\Content\Rule;

class RunNowAction extends \Commercers\AutoContent\Controller\Adminhtml\Content\Rule
{
    /**
     * Rule edit action
     *
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $rule = $this->ruleFactory->create()->load($id);
        $jobCode = $rule->getCronCode();
        $this->process->run($jobCode);
    }
}