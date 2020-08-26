<?php
namespace Commercers\CustomMenus\Plugin;

class CustomTab {
    public function afterAddTab(\Magento\Backend\Block\Widget\Tabs $subject, $result)
	{
        echo "123";
        exit;

	}

}