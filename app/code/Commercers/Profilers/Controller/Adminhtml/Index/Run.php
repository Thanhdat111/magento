<?php

namespace Commercers\Profilers\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\App\Cache\Manager as CacheManager;
class Run  extends \Magento\Backend\App\Action {

    private $_profilersFactory;

    public function __construct(
    \Commercers\Profilers\Model\ProfilersFactory $profilersFactory,
            \Magento\Framework\App\Config\ValueFactory $configValueFactory,
            \Commercers\Profilers\Model\RuleFactory $ruleFactory,
            CacheManager $cacheManager,
            \Commercers\Profilers\Model\ProfilerSavingPool $savingPool,
             \Magento\Framework\Message\ManagerInterface $messageManager,
            Action\Context $context
    ) {
        $this->_profilersFactory = $profilersFactory;
        $this->_configValueFactory = $configValueFactory;
        $this->ruleFactory = $ruleFactory;
        $this->_cacheManager = $cacheManager;
        $this->savingPool = $savingPool;
        $this->messageManager = $messageManager;
        parent::__construct($context);
    }

    public function execute() {
        if ($id = $this->getRequest()->getParam('id', false)) {
            $profiler = $this->_profilersFactory->create();
            $profiler = $profiler->load($id);

            $type = $profiler->getData("type");
            try {
                if ($type == \Commercers\Profilers\Model\Constant::EXPORT_PROFILER) {
                    $process = \Magento\Framework\App\ObjectManager::getInstance()->get("\Commercers\Profilers\Service\Profiler\Process\Export");
                    $process->execute($profiler);
                }

                if ($type == \Commercers\Profilers\Model\Constant::IMPORT_PROFILER) {
                    $process = \Magento\Framework\App\ObjectManager::getInstance()->get("\Commercers\Profilers\Service\Profiler\Process\Import");
                    $process->execute($profiler);
                }
                $this->messageManager->addSuccess("A profiler is executed successfully");
            } catch (Exception $ex) {
                $this->messageManager->addError($ex->getMessage());
            }

            $this->_redirect('*/*/editaction', ['id' => $profiler->getId(), '_current' => true]);
            return;
        }
        $this->_redirect('*/*/listing');
    }

}
