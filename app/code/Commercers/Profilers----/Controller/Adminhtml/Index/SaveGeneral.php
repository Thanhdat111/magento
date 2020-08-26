<?php

namespace Commercers\Profilers\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class SaveGeneral extends \Magento\Backend\App\Action
{
    private $_profilersFactory;

    public function __construct(
        \Commercers\Profilers\Model\ProfilersFactory $profilersFactory,
        \Magento\Framework\App\Config\ValueFactory $configValueFactory,
        Action\Context $context
    ) {
        $this->_profilersFactory = $profilersFactory;
        $this->_configValueFactory = $configValueFactory;
        parent::__construct($context);
    }

    public function execute() {
        $dataGeneral = $this->getRequest()->getPostValue();

        $profiler = $this->_profilersFactory->create();

        if (isset($dataGeneral['id'])) {
            $profiler = $profiler->load($dataGeneral['id']);
        }

        try {
            $profiler->addData([
                'name' => $dataGeneral['name'],
                'id_profiler' => $dataGeneral['id_profiler'],
                'data_source' => $dataGeneral['data_source'],
                'enable_disable' => $dataGeneral['enable_disable'],
                'type' => $dataGeneral['type']
            ])->save();
            $this->_redirect('*/*/editaction', ['id' => $profiler->getId(), '_current' => true]);
        } catch (Exception $e) {
            //echo $e->getMessage();exit;
            $this->_redirect('*/*/listing');
        }
    }

}
