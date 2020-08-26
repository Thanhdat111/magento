<?php
class Commercers_Autocategory_Model_System_Config_Backend_Cron
    extends Mage_Core_Model_Config_Data
{
    const CRON_STRING_PATH = 'crontab/jobs/commercers_autocategory_run/schedule/cron_expr';

    protected function _afterSave()

    {
        $frequency = $this->getData('groups/general/fields/cronfrequency/value');

        $customExpression = $this->getData('groups/general/fields/croncustom/value');



        if ($frequency != Commercers_Autocategory_Model_System_Config_Source_Cron_Frequency::CRON_MANUAL) {

            $frequencyMinute = Commercers_Autocategory_Model_System_Config_Source_Cron_Frequency::CRON_MINUTE;
            $frequencyTenMinutes = Commercers_Autocategory_Model_System_Config_Source_Cron_Frequency::CRON_TENMINUTES;
            $frequencyTwentyMinutes = Commercers_Autocategory_Model_System_Config_Source_Cron_Frequency::CRON_TWENTYMINUTES;
            $frequencyHalfHourly = Commercers_Autocategory_Model_System_Config_Source_Cron_Frequency::CRON_HALFHOURLY;
            $frequencyHourly = Commercers_Autocategory_Model_System_Config_Source_Cron_Frequency::CRON_HOURLY;
            $frequencyDaily = Commercers_Autocategory_Model_System_Config_Source_Cron_Frequency::CRON_DAILY;
            $frequencyTwiceDaily = Commercers_Autocategory_Model_System_Config_Source_Cron_Frequency::CRON_TWICEDAILY;
            $frequencyWeekly = Commercers_Autocategory_Model_System_Config_Source_Cron_Frequency::CRON_WEEKLY;

            $minuteExpr = '0';
            $hourExpr = '0';
            $dayMonthExpr = '*';
            $monthExpr = '*';
            $dayWeekExpr = '*';
            if ($frequency == $frequencyMinute) {
                $minuteExpr = '*';
                $hourExpr = '*';
            }

            if ($frequency == $frequencyTenMinutes) {
                $minuteExpr = '*/10';
                $hourExpr = '*';
            }

            if ($frequency == $frequencyTwentyMinutes) {
                $minuteExpr = '*/20';
                $hourExpr = '*';
            }

            if ($frequency == $frequencyHalfHourly) {
                $minuteExpr = '0,30';
                $hourExpr = '*';
            }

            if ($frequency == $frequencyHourly) {
                $hourExpr = '*';
            }

            if ($frequency == $frequencyDaily) {
                # Nothing to change
            }
            if ($frequency == $frequencyTwiceDaily) {
                $minuteExpr = '0';
                $hourExpr = '3,15';
            }
            if ($frequency == $frequencyWeekly) {
                $minuteExpr = '0';
                $dayWeekExpr = '1';
            }

            $cronExprArray = array(
                $minuteExpr,                                # Minute
                $hourExpr,                                  # Hour
                $dayMonthExpr,                              # Day of the Month
                $monthExpr,                                 # Month of the Year
                $dayWeekExpr,                               # Day of the Week
            );
            $cronExprString = join(' ', $cronExprArray);
        } else {
            $cronExprString = $customExpression;
        }
        try {
            Mage::getModel('core/config_data')
                ->load(self::CRON_STRING_PATH, 'path')
                ->setValue($cronExprString)
                ->setPath(self::CRON_STRING_PATH)
                ->save();
        } catch (Exception $e) {
            throw new Exception(Mage::helper('cron')->__('Unable to save Cron expression'));
        }
    }
}