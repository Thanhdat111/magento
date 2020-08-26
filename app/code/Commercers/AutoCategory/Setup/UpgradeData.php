<?php
/**
 * Copyright © Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * See COPYING.txt for license details.
 */
namespace Commercers\AutoCategory\Setup;

use Magento\Framework\DB\AggregatedFieldDataConverter;
use Magento\Framework\DB\DataConverter\SerializedToJson;
use Magento\Framework\DB\FieldToConvert;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

/**
 * Upgrade data
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * Field data converter
     *
     * @var \Magento\Framework\DB\AggregatedFieldDataConverter
     */
    protected $aggregatedFieldConverter;

    /**
     * Initialize setup
     *
     * @param AggregatedFieldDataConverter $aggregatedFieldConverter
     */
    public function __construct(
        AggregatedFieldDataConverter $aggregatedFieldConverter
    ) {
        $this->aggregatedFieldConverter = $aggregatedFieldConverter;
    }

    /**
     * Upgrades DB data
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '2.2.0', '<')) {
            $this->convertSerializedDataToJson($setup);
        }

        $setup->endSetup();
    }

    /**
     * Convert metadata from serialized to JSON format
     *
     * @param ModuleDataSetupInterface $setup
     * @return void
     */
    protected function convertSerializedDataToJson($setup)
    {
        $this->aggregatedFieldConverter->convert(
            [
                new FieldToConvert(
                    SerializedToJson::class,
                    $setup->getTable('commercers_autocategory_rule'),
                    'rule_id',
                    'conditions_serialized'
                ),
            ],
            $setup->getConnection()
        );
    }
}
