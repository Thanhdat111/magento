<?php

namespace Commercers\Profilers\Controller\Adminhtml\Index\Rule;
class Save extends \Commercers\Profilers\Controller\Adminhtml\Index\Rule
{
    /**
     * Rule save action
     *
     * @return void
     */
    public function execute()
    {
        if (!$this->getRequest()->getPostValue()) {
            $this->_redirect('profilers/*/');
        }
        
        try {
           // echo "<pre>";print_r($this->getRequest()->getPostValue());echo "<pre>";exit;
            /** @var $model \Commercers\AutoProductRelation\Model\Rule */
           //echo "<pre>";print_r($this->getRequest()->getPostValue());echo "<pre>";exit;
            $params = $this->getRequest()->getPostValue();
            $model = $this->ruleFactory->create();
            $this->_eventManager->dispatch(
                'adminhtml_controller_index_rules_prepare_save',
                ['request' => $this->getRequest()]
            );
            $data = $this->getRequest()->getPostValue();
            $inputFilter = new \Zend_Filter_Input(
                ['from_date' => $this->dateFilter, 'to_date' => $this->dateFilter],
                [],
                $data
            );
            $data = $inputFilter->getUnescaped();
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            }

            $validateResult = $model->validateData(new \Magento\Framework\DataObject($data));
            if ($validateResult !== true) {
                foreach ($validateResult as $errorMessage) {
                    $this->messageManager->addErrorMessage($errorMessage);
                }
                $this->_session->setPageData($data);
                $this->_redirect('profilers/*/edit', ['id' => $model->getId()]);
                return;
            }

            $data = $this->prepareData($data);
            $model->loadPost($data);

            $this->_session->setPageData($model->getData());
            if(isset($params['rule_id'])){
                $this->deleteCronConfiguration($model);
            }
            $model->save();
            $attributeJson = $params['attribute'];
            $this->saveAttribute->Save($attributeJson,$model->getRuleId());
            $this->addCronConfiguration($model);
            $this->messageManager->addSuccessMessage(__('You saved the rule.'));
            $this->_session->setPageData(false);
            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('profilers/*/edit', ['id' => $model->getId()]);
                return;
            }
            $this->_redirect('profilers/*/');
            return;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $id = (int)$this->getRequest()->getParam('id');
            if (!empty($id)) {
                $this->_redirect('profilers/*/edit', ['id' => $id]);
            } else {
                $this->_redirect('profilers/*/new');
            }
            return;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('Something went wrong while saving the rule data. Please review the error log.')
            );
            $this->logger->critical($e);
            $data = !empty($data) ? $data : [];
            $this->_session->setPageData($data);
            $this->_redirect('profilers/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            return;
        }
    }
    /**
     * Prepares specific data
     *
     * @param array $data
     * @return array
     */
    protected function prepareData($data)
    {
        if (isset($data['rule']['conditions'])) {
            $data['conditions'] = $data['rule']['conditions'];
        }
        
        unset($data['rule']);

        return $data;
    }
}