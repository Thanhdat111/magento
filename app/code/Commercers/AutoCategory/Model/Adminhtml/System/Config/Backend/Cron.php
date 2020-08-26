<?php


namespace Commercers\AutoCategory\Model\Adminhtml\System\Config\Backend;

use Commercers\AutoCategory\Model\Adminhtml\System\Config\Source\Cron\Frequency as Frequency;

class Cron extends \Magento\Framework\App\Config\Value
{
    const CRON_STRING_PATH = 'crontab/default/jobs/commercers_autocategory_run/schedule/cron_expr';

    /**
     * Cron model path
     */
    const CRON_MODEL_PATH = 'crontab/default/jobs/commercers_autocategory_run/run/model';

    protected $_configValueFactory;

    /**
     * @var string
     */
    protected $_runModelPath = '';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Config\ValueFactory $configValueFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param string $runModelPath
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Config\ValueFactory $configValueFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        $runModelPath = '',
        array $data = []
    )
    {
        $this->_runModelPath = $runModelPath;
        $this->_configValueFactory = $configValueFactory;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    public function afterSave()

    {
        $frequency = $this->getData('groups/group_auto_category/fields/cronjob_frequency/value');
        if ($frequency != Frequency::CRON_MANUAL) {

            $frequencyMinute = Frequency::CRON_MINUTE;
            $frequencyTenMinutes = Frequency::CRON_TENMINUTES;
            $frequencyTwentyMinutes = Frequency::CRON_TWENTYMINUTES;
            $frequencyHalfHourly = Frequency::CRON_HALFHOURLY;
            $frequencyHourly = Frequency::CRON_HOURLY;
            $frequencyDaily = Frequency::CRON_DAILY;
            $frequencyTwiceDaily = Frequency::CRON_TWICEDAILY;
            $frequencyWeekly = Frequency::CRON_WEEKLY;

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
        }
        try {
            $this->_configValueFactory->create()->load(
                self::CRON_STRING_PATH,
                'path'
            )->setValue(
                $cronExprString
            )->setPath(
                self::CRON_STRING_PATH
            )->save();
            $this->_configValueFactory->create()->load(
                self::CRON_MODEL_PATH,
                'path'
            )->setValue(
                $this->_runModelPath
            )->setPath(
                self::CRON_MODEL_PATH
            )->save();
        } catch (\Exception $e) {
            throw new \Exception(__('We can\'t save the cron expression.'));
        }
        return parent::afterSave();

    }
}