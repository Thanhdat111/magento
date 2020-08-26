<?php

namespace Commercers\AutoContent\Controller\Adminhtml\Content\Rule;

class Delete extends \Commercers\AutoContent\Controller\Adminhtml\Content\Rule
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                /** @var \Commercers\AutoProductRelation\Model\Rule $model */
                $model = $this->ruleFactory->create();
                $model->load($id);
                $this->deleteAttribute($id);
                $this->deleteCronConfiguration($model);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the rule.'));
                $this->_redirect('backend/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('We can\'t delete the rule right now. Please review the log and try again.')
                );
                $this->logger->critical($e);
                $this->_redirect('backend/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a rule to delete.'));
        $this->_redirect('backend/*/');
    }
    protected function deleteAttribute($ruleId){
        $attributeOld = $this->attributeFactory->create()->getCollection()
                        ->addFieldToFilter('rule_id', array('eq' => $ruleId));
        if ($attributeOld->getSize()) {
                $attributeOld->walk('delete');
            }  
    }

    protected function deleteCronConfiguration($model){
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
}