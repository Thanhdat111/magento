<?php

namespace Commercers\AutoContent\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $table = $installer->getConnection()->newTable(
            $installer->getTable('commercers_autocontent')
        )->addColumn(
            'rule_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Rule Id'
        )->addColumn(
            'name',
            Table::TYPE_TEXT,
            255,
            [],
            'Name'
        )->addColumn(
            'is_active',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '0'],
            'Is Active'
        )->addColumn(
            'conditions_serialized',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Conditions Serialized'
        )->addColumn(
            'cron_code',
            Table::TYPE_TEXT,
            255,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'cron_code'
        )->addColumn(
            'cron_schedule',
            Table::TYPE_TEXT,
            255,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'cron_schedule'
        )->addColumn(
            'run_model_cronjob',
            Table::TYPE_TEXT,
            255,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'run_model_cronjob'
        )->addColumn(
            'sort_order',
            Table::TYPE_INTEGER,
            11,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'sort_order'
        )->setComment(
            'Own AutoContent'
        );
        $installer->getConnection()->createTable($table);
        $installer->endSetup();

        $installer->startSetup();
        $table = $setup->getConnection()
            ->newTable($setup->getTable('commercers_autocontent_attribute')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )->addColumn(
                'rule_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['default' => 0, 'nullable' => false],
                'rule_id'
            )->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['default' => 0, 'nullable' => false],
                'store_id'
            )->addColumn(
                'attribute_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                128,
                ['default' => '', 'nullable' => false],
                'Attribute Code'
            )->addColumn(
                'expression',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                128,
                ['nullable' => false, 'default' => ''],
                'Expression')
            ->addColumn(
                'use_default',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'use_default');
        $setup->getConnection()->createTable($table);
        $installer->endSetup();

        $table = $setup->getConnection()
            ->newTable($setup->getTable('autocontent_process_log'))
            ->addColumn(
                'process_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )
            ->addColumn(
                'id_profiler',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false, 'default' => 0],
                  'Id Profiler'
            )
            ->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => 0],
                  'Message'
            )
            ->addColumn(
                'message',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                  'Mapping'
            )
            ->addColumn(
                'executed_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Executed At'
            )->addColumn(
                'end_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'End At'
            )
            ->setComment("AutoContent Process table");
        $setup->getConnection()->createTable($table);
    }
}