<?php

namespace Commercers\Profilers\Block\Adminhtml\Profilers\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class RunNow extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     * @codeCoverageIgnore
     */
    public function getButtonData()
    {
        $data = [
                'label' => __('Run Now'),
                'class' => 'run_now',
                'on_click' => sprintf("location.href = '%s';", $this->getUrl('profilers/index/run', array('_current' => true ))),
                'sort_order' => 90,
            ];
        return $data;
    }
}