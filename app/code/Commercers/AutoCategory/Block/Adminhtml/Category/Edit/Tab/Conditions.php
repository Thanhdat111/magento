<?php


namespace Commercers\AutoCategory\Block\Adminhtml\Category\Edit\Tab;



use Magento\Framework\Registry;
use Magento\Framework\Data\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Form\Renderer\Fieldset;
use Magento\Rule\Block\Conditions as RuleConditions;
use Magento\Rule\Model\Condition\AbstractCondition;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Commercers\AutoCategory\Model\RuleFactory;
use Commercers\AutoCategory\Model\Rule;

/**
 * Conditions tab
 */
class Conditions extends Generic implements TabInterface
{
    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * Fieldset renderer
     *
     * @var Fieldset
     */
    protected $rendererFieldset;

    /**
     * Conditions
     *
     * @var RuleConditions
     */
    protected $conditions;

    /**
     * Rule factory
     *
     * @var RuleFactory
     */
    protected $ruleFactory;

    /**
     * Intialize conditions
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param RuleConditions $conditions
     * @param Fieldset $rendererFieldset
     * @param RuleFactory $ruleFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        RuleConditions $conditions,
        Fieldset $rendererFieldset,
        RuleFactory $ruleFactory,
        array $data = []
    ) {
        $this->rendererFieldset = $rendererFieldset;
        $this->conditions = $conditions;
        $this->coreRegistry = $registry;
        $this->ruleFactory = $ruleFactory;
        $this->formFactory  = $formFactory;

        parent::__construct(
            $context,
            $registry,
            $formFactory,
            $data
        );
    }

    /**
     * Prepare content for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Conditions');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Conditions');
    }

    /**
     * Retrieve status flag about this tab can be showen or not
     *
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Retrieve status flag about this tab hidden or not
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Retrieve tab class
     *
     * @return string
     */
    public function getTabClass()
    {
        return '';
    }

    /**
     * Retrieve URL link to tab content
     *
     * @return string
     */
    public function getTabUrl()
    {
        return '';
    }

    /**
     * Tab should be loaded trough Ajax call
     *
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return false;
    }
    private function getRuleFactory()
    {
        if ($this->ruleFactory === null) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $this->ruleFactory = $objectManager->create('Commercers\AutoCategory\Model\RuleFactory');
        }
        return $this->ruleFactory;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return \Faonni\SmartCategory\Block\Adminhtml\Catalog\Edit\Tab\Conditions
     */
    protected function _prepareForm()
    {
        $category = $this->getCurrentCategory();
        $rule = $this->ruleFactory->create();

        if ($category->getId()) {
            $rule = $rule->load($category->getId());
        }
        /** @var Form $form */
        $form = $this->addTabToForm($rule);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Handles addition of conditions tab to supplied form
     *
     * @param Rule $model
     * @param string $fieldsetId
     * @param string $formName
     * @return Form
     */
    protected function addTabToForm($model, $fieldsetId = 'conditions_fieldset', $formName = 'category_form')
    {
        /** @var Form $form */
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
        $form = $this->formFactory->create();
        $form->setHtmlIdPrefix('rule_');
        $renderer = $this->rendererFieldset->setTemplate(
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
            $this->conditions
        );

        $form->setValues($model->getData());

        $this->setConditionFormName($model->getConditions(), $formName);
        return $form;
    }

    /**
     * Handles addition of form name to condition and its conditions
     *
     * @param AbstractCondition $conditions
     * @param string $formName
     * @return void
     */
    protected function setConditionFormName(AbstractCondition $conditions, $formName)
    {
        $conditions->setFormName($formName);
        $conditions->setJsFormObject($formName);
        if ($conditions->getConditions() && is_array($conditions->getConditions())) {
            foreach ($conditions->getConditions() as $condition) {
                $this->setConditionFormName($condition, $formName);
            }
        }
    }

    /**
     * Retrieve current category model object
     *
     * @return \Magento\Catalog\Model\Category
     */
    public function getCurrentCategory()
    {
        return $this->coreRegistry->registry('current_category');
    }
}