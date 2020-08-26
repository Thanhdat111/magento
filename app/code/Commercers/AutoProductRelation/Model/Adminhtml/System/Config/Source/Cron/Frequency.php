<?php
namespace Commercers\AutoProductRelation\Model\Adminhtml\System\Config\Source\Cron;

class Frequency {
	
	public function toOptionArray(){ 
		return [
			array(

				'label' => __('Manual'),

				'value' => 'MANUAL',

			),

			array(

				'label' => __('Hourly'),

				'value' => 'H',

			),

			array(

				'label' => __('Daily'),

				'value' => 'D',

			),

			array(

				'label' => __('Twice Daily'),

				'value' => 'TD',

			),

			array(

				'label' => __('Weekly'),

				'value' => 'W',

			),

			array(

				'label' => __('Monthly'),

				'value' => 'M',

			),
		];
    }
}