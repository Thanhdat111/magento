<?php

namespace Commercers\Profilers\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\App\Cache\Manager as CacheManager;
class Save extends \Magento\Backend\App\Action {

    private $_profilersFactory;

    public function __construct(
    \Commercers\Profilers\Model\ProfilersFactory $profilersFactory, \Magento\Framework\App\Config\ValueFactory $configValueFactory,
            \Commercers\Profilers\Model\RuleFactory $ruleFactory,
            CacheManager $cacheManager,
            \Commercers\Profilers\Model\ProfilerSavingPool $savingPool,
            Action\Context $context
    ) {
        $this->_profilersFactory = $profilersFactory;
        $this->_configValueFactory = $configValueFactory;
        $this->ruleFactory = $ruleFactory;
        $this->_cacheManager = $cacheManager;
        $this->savingPool = $savingPool;
        parent::__construct($context);
    }

    public function execute() {

        $dataGeneral = $this->getRequest()->getParam('general');
        $dataFtp = $this->getRequest()->getParam('ftp');
        $dataLocal = $this->getRequest()->getParam('local');
        $dataCron = $this->getRequest()->getParam('cron');
        $dataMapping = $this->getRequest()->getParam('mapping');
        $dataOutputFormat = $this->getRequest()->getParam('outputformat');
        
        $profiler = $this->_profilersFactory->create();
        
        if (isset($dataGeneral['id'])) {
            $profiler = $profiler->load($dataGeneral['id']);
            $this->deleteCronConfiguration($profiler);
        }





        try {
            $profiler->addData([
                'name' => $dataGeneral['name'],
                'id_profiler' => $dataGeneral['id_profiler'],
                'data_source' => $dataGeneral['data_source'],
                'status' => $dataGeneral['status'],
                'type' => $dataGeneral['type'],
                'ftp_enable' => $dataFtp['ftp_enable'],
                'ftp_hostname' => $dataFtp['ftp_hostname'],
                'ftp_username' => $dataFtp['ftp_username'],
                'ftp_password' => $dataFtp['ftp_password'],
                'ftp_type' => $dataFtp['ftp_type'],
                'ftp_key_file' => $dataFtp['ftp_key_file'],
                'ftp_port' => $dataFtp['ftp_port'],
                'ftp_folder' => $dataFtp['ftp_folder'],
                'ftp_done_folder' => $dataFtp['ftp_done_folder'],
                'ftp_local_tmp' => $dataFtp['ftp_local_tmp'],
                'local_enable' => $dataLocal['local_enable'],
                'local_folder' => $dataLocal['local_folder'],
                'local_done_folder' => $dataLocal['local_done_folder'],
                'local_nfiles_per_process' => $dataLocal['local_nfiles_per_process'],
                
                'enable_disable_cron' => $dataCron['enable_disable_cron'],
                'code' => $dataCron['code'],
                'run_model_cronjob' => $dataCron['run_model_cronjob'],
                'schedule' => $dataCron['schedule'],
                'file_prefix' => $dataMapping['file_prefix'],
                'delimiter' => $dataMapping['delimiter'],
                'format' => $dataMapping['format'],
                'import_input_template' => $dataMapping['import_input_template'],
                'export_output_template' => $dataOutputFormat['export_output_template']
            ]);

            $profiler->save();
            $this->savingPool->save($profiler, $this->getRequest()->getParams());

            if($dataCron['enable_disable_cron'])
            $this->addCronConfiguration($profiler);




        } catch (Exception $e) {
            //echo $e->getMessage();exit;
        }
        //clean cache
        $this->_cacheManager->clean(array('config'));
        
        if ($this->getRequest()->getParam('back')) {
            if (isset($dataGeneral['id'])) {
                $id = $dataGeneral['id'];
            } else {
                $profilersFactory = $this->_profilersFactory->create();
                $profilersFactory->getCollection()->addFieldToFilter('id_profiler', array('eq' => $dataGeneral['id_profiler']));
                $data = $profilersFactory->getData();
                $id = $data['id'];
            }
            $this->_redirect('*/*/editaction', ['id' => $id, '_current' => true]);
        } else {
            $this->_redirect('*/*/listing');
        }

    }

    public function addCronConfiguration($profiler) {
        $cronExprStringPath = "crontab/default/jobs/{$profiler->getCode()}/schedule/cron_expr";
        $cronModelPath = "crontab/default/jobs/{$profiler->getCode()}/run/model";
        $cronNamePath = "crontab/default/jobs/{$profiler->getCode()}/name";

        /**
         * save cron job
         *
         */
        $this->_configValueFactory->create()->load(
                $cronNamePath, 'path'
        )->setValue(
                $profiler->getCode()
        )->setPath(
                $cronNamePath
        )->save();

        $this->_configValueFactory->create()->load(
                $cronExprStringPath, 'path'
        )->setValue(
                $profiler->getSchedule()
        )->setPath(
                $cronExprStringPath
        )->save();
        $cronModel = $profiler->getRunModelCronjob();
        if (!$cronModel) {
            $cronModel = 'Commercers\Profilers\Cron\Task::execute';
        }
        $this->_configValueFactory->create()->load(
                $cronModelPath, 'path'
        )->setValue(
                $cronModel
        )->setPath(
                $cronModelPath
        )->save();
    }

    public function deleteCronConfiguration($profiler) {
        $cronExprStringPath = "crontab/default/jobs/{$profiler->getCode()}/schedule/cron_expr";
        $cronModelPath = "crontab/default/jobs/{$profiler->getCode()}/run/model";
        $cronNamePath = "crontab/default/jobs/{$profiler->getCode()}/name";

        /**
         * save cron job
         *
         */
        $cronName = $this->_configValueFactory->create()->load(
                $cronNamePath, 'path'
        );
        if ($cronName) {
            $cronName->delete();
        }

        $cronName = $this->_configValueFactory->create()->load(
                $cronExprStringPath, 'path'
        );
        if ($cronName) {
            $cronName->delete();
        }

        $cronName = $this->_configValueFactory->create()->load(
                $cronModelPath, 'path'
        );
        if ($cronName) {
            $cronName->delete();
        }
    }

}
