<?php


namespace Commercers\AutoCategory\Block\Adminhtml\Category\Rule\Edit\Tab;

class Conditions extends \Magento\Backend\Block\Widget\Form\Generic
{

    protected $_rendererFieldset;

    protected $_conditions;

    protected $_nameInLayout = 'conditions';

    private $ruleFactory;



    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Rule\Block\Conditions $conditions,
        \Magento\Backend\Block\Widget\Form\Renderer\Fieldset $rendererFieldset,
        array $data = []
    )
    {
        $this->_rendererFieldset = $rendererFieldset;
        $this->_conditions = $conditions;
        parent::__construct($context, $registry, $formFactory, $data);
    }


    private function getRuleFactory()
    {
        if ($this->ruleFactory === null) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $this->ruleFactory = $objectManager->create('Commercers\AutoCategory\Model\RuleFactory');
        }
        return $this->ruleFactory;
    }

    public function getTabClass()
    {
        return null;
    }


    public function getTabUrl()
    {
        return null;
    }

    public function isAjaxLoaded()
    {
        return false;
    }


    public function getTabLabel()
    {
        return __('Conditions');
    }


    public function getTabTitle()
    {
        return __('Conditions');
    }

    public function canShowTab()
    {
        return true;
    }


    public function isHidden()
    {
        return false;
    }


    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_rule');
        $form = $this->addTabToForm($model);
        $this->setForm($form);

        return parent::_prepareForm();
    }


    protected function addTabToForm($model, $fieldsetId = 'conditions_serialized', $formName = 'category_form')
    {
        if (!$model) {
           // $id = $this->getRequest()->getParam('id');
            $model = $this->getRuleFactory()->create();
           // $model->load($id);
        }
        $conditionsFieldSetId = $model->getConditionsFieldSetId($formName);
        $newChildUrl = $this->getUrl(
            'catalog_rule/promo_catalog/newConditionHtml/form/' . $conditionsFieldSetId,
            ['form_namespace' => $formName]
        );
     //   $newChildUrl = $this->getUrl('auto_category/category_rule/newConditionHtml/form/rule_conditions_fieldset');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('rule_');
        $renderer = $this->_rendererFieldset->setTemplate(
            'Magento_CatalogRule::promo/fieldset.phtml'
        )->setNewChildUrl(
            $newChildUrl
        );


        $fieldset = $form->addFieldset(
            $fieldsetId,
            [
                'legend' => __(
                    'Apply the rule only if the following conditions are met (leave blank for all products).'
                )
            ]
        )->setRenderer(
            $renderer
        );
        $fieldset->addField(
            'conditions',
            'text',
            [
                'name' => 'conditions',
                'label' => __('Conditions'),
                'title' => __('Conditions'),
                'required' => true,
                'data-form-part' => $formName
            ]
        )->setRule(
            $model
        )->setRenderer(
            $this->_conditions
        );

        $form->setValues($model->getData());

        $this->setConditionFormName($model->getConditions(), $formName);
        return $form;
    }

    private function setConditionFormName(\Magento\Rule\Model\Condition\AbstractCondition $conditions, $formName)
    {
        $conditions->setFormName($formName);
        if ($conditions->getConditions() && is_array($conditions->getConditions())) {
            foreach ($conditions->getConditions() as $condition) {
                $this->setConditionFormName($condition, $formName);
            }
        }
    }
}