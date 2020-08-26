<?php

namespace Commercers\AutoProductRelation\Model\Adminhtml\System\Config\Source\Cron;

class Expression extends \Magento\Framework\App\Config\Value
{
    const XML_PATH_FREQUENCY = 'section_cross_sell/group_commercers_auto_product_relation_general/enable_auto_product_relation_general_cron_frequency';
    const XML_PATH_CUSTOM_EXPRESSION = 'section_cross_sell/group_commercers_auto_product_relation_general/enable_auto_product_relation_general_custom_cron_expression';
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    protected $_configValueFactory;
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
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Config\ValueFactory $configValueFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->_configValueFactory = $configValueFactory;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    public function afterSave()
    {
        $frequencyManual = 'MANUAL';
        $frequencyHourly = 'H';
        $frequencyDaily = 'D';
        $frequencyTwiceDaily = 'TD';
        $frequencyWeekly = 'W';
        $frequencyMonthly = 'M';

        $frequency = $this->scopeConfig->getValue(self::XML_PATH_FREQUENCY,\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $customExpression = $this->scopeConfig->getValue(self::XML_PATH_CUSTOM_EXPRESSION,\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->deleteCronConfiguration();
        // echo $frequency;exit;
        if($frequency != $frequencyManual){
            $minuteExpr = '0';
            $hourExpr = '0';
            $dayMonthExpr = '*';
            $monthExpr = '*';
            $dayWeekExpr = '*';
            // if ($frequency == $frequencyMinute) {
            //     $minuteExpr = '*';
            //     $hourExpr = '*';
            // }

            // if ($frequency == $frequencyTenMinutes) {
            //     $minuteExpr = '*/10';
            //     $hourExpr = '*';
            // }

            // if ($frequency == $frequencyTwentyMinutes) {
            //     $minuteExpr = '*/20';
            //     $hourExpr = '*';
            // }

            // if ($frequency == $frequencyHalfHourly) {
            //     $minuteExpr = '0,30';
            //     $hourExpr = '*';
            // }

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

        }else{
            $cronExprString = $customExpression;
        }
        // echo $cronExprString;exit;
        $this->addCronConfiguration($cronExprString);

        return parent::afterSave();
    }

    public function addCronConfiguration($cronExprString){
        $code = 'commercers_auto_linkeds_product';
        $cronExprStringPath = "crontab/default/jobs/{$code}/schedule/cron_expr";
        $cronModelPath = "crontab/default/jobs/{$code}/run/model";
        $cronNamePath = "crontab/default/jobs/{$code}/name";
            
        /**
         * save cron job
         * 
         */
        
        $this->_configValueFactory->create()->load(
            $cronNamePath,
            'path'
        )->setValue(
            $code
        )->setPath(
            $cronNamePath
        )->save();
        
        $this->_configValueFactory->create()->load(
            $cronExprStringPath,
            'path'
        )->setValue(
            $cronExprString
        )->setPath(
            $cronExprStringPath
        )->save();
        $this->_configValueFactory->create()->load(
            $cronModelPath,
            'path'
        )->setValue(
           'Commercers\AutoProductRelation\Cron\CronAutoLinkeds::execute'
        )->setPath(
            $cronModelPath
        )->save();
    }

    public function deleteCronConfiguration(){
        $code = 'commercers_auto_linkeds_product';
        $cronExprStringPath = "crontab/default/jobs/{$code}/schedule/cron_expr";
        $cronModelPath = "crontab/default/jobs/{$code}/run/model";
        $cronNamePath = "crontab/default/jobs/{$code}/name";
            
        
        $cronName = $this->_configValueFactory->create()->load(
            $cronNamePath,
            'path'
        );
        if($cronName){
            $cronName->delete();
        }

        $cronName = $this->_configValueFactory->create()->load(
            $cronExprStringPath,
            'path'
        );
        if($cronName){
            $cronName->delete();
        }
            
        $cronName = $this->_configValueFactory->create()->load(
            $cronModelPath,
            'path'
        );
        if($cronName){
            $cronName->delete();
        }
    }
}