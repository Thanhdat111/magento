<?php

namespace Commercers\AutoContent\Block\Adminhtml\Content\Rule;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $data);
    }

    /**
     * Initialize form
     * Add standard buttons
     * Add "Save and Continue" button
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_content_rule';
        $this->_blockGroup = 'Commercers_AutoContent';

        parent::_construct();

        $this->buttonList->add(
            'save_and_continue_edit',
            [
                'class' => 'save',
                'label' => __('Save and Continue Edit'),
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form']],
                ]
            ],
            10
        );
        $id = $this->request->getParam('id');

        if($id){
            $url = $this->urlBuilder->getUrl('backend/content_rule/runnowaction', array('id'=>$id));
            $message ='Are you sure you want to do this?';
            $this->buttonList->add(
                'run_now_profile',
                [
                    'class' => 'save',
                    'label' => __('Run Now'),
                    'onclick' => "confirmSetLocation('{$message}', '{$url}')"
                ],
                10
            );
        }


    }

    /**
     * Getter for form header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        $rule = $this->coreRegistry->registry('current_rule');
        if ($rule->getRuleId()) {
            return __("Edit Rule '%1'", $this->escapeHtml($rule->getName()));
        } else {
            return __('New Rule');
        }
    }

}