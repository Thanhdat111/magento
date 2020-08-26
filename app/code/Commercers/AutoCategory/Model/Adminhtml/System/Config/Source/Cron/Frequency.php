<?php


namespace Commercers\AutoCategory\Model\Adminhtml\System\Config\Source\Cron;


class Frequency extends \Magento\Cron\Model\Config\Source\Frequency
{
    /**
     * @var array
     */
    protected static $_options;

    const CRON_MANUAL = 'MANUAL';

    const CRON_MINUTE = 'M';

    const CRON_TENMINUTES = '10M';

    const CRON_TWENTYMINUTES = '20M';

    const CRON_HALFHOURLY = 'HH';

    const CRON_HOURLY = 'H';

    const CRON_DAILY = 'D';

    const CRON_TWICEDAILY = 'TD';

    const CRON_WEEKLY = 'W';


    public function toOptionArray()

    {

        if (!self::$_options) {

            self::$_options = array(

                array(

                    'label' => __('Manual'),

                    'value' => self::CRON_MANUAL,

                ),

                array(

                    'label' => __('Hourly'),

                    'value' => self::CRON_HOURLY,

                ),

                array(

                    'label' => __('Daily'),

                    'value' => self::CRON_DAILY,

                ),

                array(

                    'label' =>__('Twice Daily'),

                    'value' => self::CRON_TWICEDAILY,

                ),

                array(

                    'label' => __('Weekly'),

                    'value' => self::CRON_WEEKLY,

                ),

                array(

                    'label' => __('Monthly'),

                    'value' => self::CRON_MONTHLY,

                ),

            );

        }

        return self::$_options;
    }
}