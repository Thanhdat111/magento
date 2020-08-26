<?php

namespace Commercers\AutoContent\Controller\Adminhtml\Content\Rule;
class Save extends \Commercers\AutoContent\Controller\Adminhtml\Content\Rule
{
    /**
     * Rule save action
     *
     * @return void
     */
    public function execute()
    {
        if (!$this->getRequest()->getPostValue()) {
            $this->_redirect('backend/*/');
        }
        
        try {
            //echo "<pre>";print_r($this->getRequest()->getPostValue());echo "<pre>";exit;
            /** @var $model \Commercers\AutoProductRelation\Model\Rule */
           //echo "<pre>";print_r($this->getRequest()->getPostValue());echo "<pre>";exit;
            $params = $this->getRequest()->getPostValue();
            $model = $this->ruleFactory->create();
            $this->_eventManager->dispatch(
                'adminhtml_controller_vendor_rules_prepare_save',
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
                $this->_redirect('backend/*/edit', ['id' => $model->getId()]);
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
                $this->_redirect('backend/*/edit', ['id' => $model->getId()]);
                return;
            }
            $this->_redirect('backend/*/');
            return;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $id = (int)$this->getRequest()->getParam('id');
            if (!empty($id)) {
                $this->_redirect('backend/*/edit', ['id' => $id]);
            } else {
                $this->_redirect('backend/*/new');
            }
            return;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('Something went wrong while saving the rule data. Please review the error log.')
            );
            $this->logger->critical($e);
            $data = !empty($data) ? $data : [];
            $this->_session->setPageData($data);
            $this->_redirect('backend/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            return;
        }
    }
    public function addCronConfiguration($model){
        $cronExprStringPath = "crontab/default/jobs/{$model->getCronCode()}/schedule/cron_expr";
        $cronModelPath = "crontab/default/jobs/{$model->getCronCode()}/run/model";
        $cronNamePath = "crontab/default/jobs/{$model->getCronCode()}/name";
            
        /**
         * save cron job
         * 
         */
        
        $this->configValueFactory->create()->load(
            $cronNamePath,
            'path'
        )->setValue(
            $model->getCronCode()
        )->setPath(
            $cronNamePath
        )->save();
        
        $this->configValueFactory->create()->load(
            $cronExprStringPath,
            'path'
        )->setValue(
            $model->getCronSchedule()
        )->setPath(
            $cronExprStringPath
        )->save();
        $cronModel = $model->getRunModelCronjob();
        if(!$cronModel){
            $cronModel = 'Commercers\AutoContent\Cron\Task::execute';
        }
        $this->configValueFactory->create()->load(
            $cronModelPath,
            'path'
        )->setValue(
            $cronModel
        )->setPath(
            $cronModelPath
        )->save();
    }

    public function deleteCronConfiguration($model){
        $cronExprStringPath = "crontab/default/jobs/{$model->getCronCode()}/schedule/cron_expr";
        $cronModelPath = "crontab/default/jobs/{$model->getCronCode()}/run/model";
        $cronNamePath = "crontab/default/jobs/{$model->getCronCode()}/name";
            
        /**
         * save cron job
         * 
         */
        
        $cronName = $this->configValueFactory->create()->load(
            $cronNamePath,
            'path'
        );
        if($cronName){
            $cronName->delete();
        }

        $cronName = $this->configValueFactory->create()->load(
            $cronExprStringPath,
            'path'
        );
        if($cronName){
            $cronName->delete();
        }
            
        $cronName = $this->configValueFactory->create()->load(
            $cronModelPath,
            'path'
        );
        if($cronName){
            $cronName->delete();
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