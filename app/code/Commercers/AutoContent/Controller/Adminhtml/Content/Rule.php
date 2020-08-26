<?php

namespace Commercers\AutoContent\Controller\Adminhtml\Content;

abstract class Rule extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\Filter\Date
     */
    protected $dateFilter;

    protected $configValueFactory;
    /**
     * @var \Commercers\AutoProductRelation\Model\RuleFactory
     */
    protected $ruleFactory;
    protected $attributeFactory;
    protected $saveAttribute;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    protected $_coreSession;
    
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter
     * @param \Commercers\AutoProductRelation\Model\RuleFactory $ruleFactory
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \Magento\Framework\App\Config\ValueFactory $configValueFactory,
        \Commercers\AutoContent\Model\RuleFactory $ruleFactory,
        \Commercers\AutoContent\Model\AttributeFactory $attributeFactory,
        \Commercers\AutoContent\Service\SaveAttribute $saveAttribute,
        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        \Psr\Log\LoggerInterface $logger,
        \Commercers\AutoContent\Service\Process $process
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->fileFactory = $fileFactory;
        $this->configValueFactory = $configValueFactory;
        $this->dateFilter = $dateFilter;
        $this->ruleFactory = $ruleFactory;
        $this->attributeFactory = $attributeFactory;
        $this->saveAttribute = $saveAttribute;
        $this->logger = $logger;
        $this->_coreSession = $coreSession;
        $this->process = $process;
    }

    /**
     * Initiate rule
     *
     * @return void
     */
    protected function _initRule()
    {
        $rule = $this->ruleFactory->create();
        $this->coreRegistry->register(
            'current_rule',
            $rule
        );
        $id = (int)$this->getRequest()->getParam('id');

        if (!$id && $this->getRequest()->getParam('rule_id')) {
            $id = (int)$this->getRequest()->getParam('rule_id');
        }

        if ($id) {
            $this->coreRegistry->registry('current_rule')->load($id);
        }
    }

    /**
     * Initiate action
     *
     * @return Rule
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Commercers_AutoContent::backend')
            ->_addBreadcrumb(__('Auto Content'), __('Auto Content'));
        return $this;
    }

    /**
     * Returns result of current user permission check on resource and privilege
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Commercers_AutoContent::rules');
    }
}