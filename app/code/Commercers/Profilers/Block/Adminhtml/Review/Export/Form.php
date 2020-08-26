<?php 

namespace Commercers\Profilers\Block\Adminhtml\Export\Review\Export;

class Form extends  \Magento\Backend\Block\Template {
    
    
    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
    public function getProfilerJsonFormConfig(){

        $configs = array(

          'action_get_product_attribute' => $this->getUrl('*/export/action_get_attributes'),
           'show_fields_available' => $this->getUrl('*/export/showfieldsavailable'),
            'action_get_profiler_data' => $this->getUrl('*/export/action_get_dataprofiler')
        );


        return json_encode($configs);

    }
}