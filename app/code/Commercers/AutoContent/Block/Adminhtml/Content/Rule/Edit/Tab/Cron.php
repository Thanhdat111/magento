<?php

namespace Commercers\AutoContent\Block\Adminhtml\Content\Rule\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

class Cron extends Generic implements TabInterface
{

    /**
     * Constructor
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Cron');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Cron');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return Generic
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_rule');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('rule_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Cron')]);

        if ($model->getId()) {
            $fieldset->addField('rule_id', 'hidden', ['name' => 'rule_id']);
        }

        $fieldset->addField(
            'cron_code',
            'text',
            ['name' => 'cron_code', 'label' => __(' Cron job code'), 'title' => __(' Cron job code'), 'required' => true]
        );
        $fieldset->addField(
            'cron_schedule',
            'text',
            ['name' => 'cron_schedule', 'label' => __('Cron job schedule'), 'title' => __('Cron job schedule'), 'required' => true]
        );
        $fieldset->addField(
            'run_model_cronjob',
            'text',
            ['name' => 'run_model_cronjob', 'label' => __('Run Model CronJob'), 'title' => __('Run Model CronJob'),'note' => 'Default: Commercers\AutoContent\Cron\Task::execute', 'required' => true]
        );
    
        if (!$model->getId()) {
            $model->setData('is_active', '1');
        }
        
        $form->setValues($model->getData());

        if ($model->isReadonly()) {
            foreach ($fieldset->getElements() as $element) {
                $element->setReadonly(true, true);
            }
        }

        $this->setForm($form);

        $this->_eventManager->dispatch('adminhtml_content_rule_edit_tab_main_prepare_form', ['form' => $form]);

        return parent::_prepareForm();
    }
}