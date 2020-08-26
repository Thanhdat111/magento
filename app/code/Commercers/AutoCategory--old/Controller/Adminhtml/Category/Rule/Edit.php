<?php


namespace Commercers\AutoCategory\Controller\Adminhtml\Category\Rule;


class Edit extends \Commercers\AutoCategory\Controller\Adminhtml\Category\Rule
{
    /**
     * Rule edit action
     *
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var \Vendor\Rules\Model\Rule $model */
        $model = $this->ruleFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getRuleId()) {
                $this->messageManager->addErrorMessage(__('This rule no longer exists.'));
                $this->_redirect('auto_category/*');
                return;
            }
        }

        // set entered data if was error when we do save
        $data = $this->_session->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $model->getConditions()->setJsFormObject('rule_conditions_fieldset');

        $this->coreRegistry->register('current_rule', $model);

        $this->_initAction();
        $this->_view->getLayout()
            ->getBlock('category_rule_edit')
            ->setData('action', $this->getUrl('auto_category/*/save'));

        $this->_addBreadcrumb($id ? __('Edit Rule') : __('New Rule'), $id ? __('Edit Rule') : __('New Rule'));

        $this->_view->getPage()->getConfig()->getTitle()->prepend(
            $model->getRuleId() ? $model->getName() : __('New Rule')
        );
        $this->_view->renderLayout();
    }
}