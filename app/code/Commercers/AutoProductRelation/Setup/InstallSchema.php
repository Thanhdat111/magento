<?php

namespace Commercers\AutoProductRelation\Setup;

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
            $installer->getTable('cv_auto_product_relattion_rule')
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
            'priority_by',
            Table::TYPE_INTEGER,
            11,
            [],
            'priority_by'
        )->addColumn(
            'max_matched_items',
            Table::TYPE_TEXT,
            255,
            [],
            'max_matched_items'       
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
            'actions_serialized',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Actions Serialized'
        )->addColumn(
            'sort_order',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Sort Order (Priority)'
            )->addIndex(
                $setup->getIdxName('cv_auto_product_relattion_rule', ['rule_id']),
                ['rule_id']
        )->setComment(
            'Own AutoProductRelation'
        );
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
        
        $table = $installer->getConnection()->newTable(
        $installer->getTable('cv_crosssell_followup')
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
           ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            'order_id',
            Table::TYPE_INTEGER,
            11,
            ['nullable' => true],
            'order_id'
        )->addColumn(
            'customer_email',
            Table::TYPE_TEXT,
            128,
            ['nullable' => true],
            'customer_email'
        )->addColumn(
            'promotion_code',
            Table::TYPE_TEXT,
            128,
            ['nullable' => true],
            'promotion_code'       
        )->addColumn(
            'referral_code',
            Table::TYPE_TEXT,
            128,
            ['nullable' => true],
            'referral_code'
        )->addColumn(
            'created_at',
            Table::TYPE_DATETIME,
            255,
            ['nullable' => true],
            'created_at'
        )->addColumn(
            'sent_at',
            Table::TYPE_DATETIME,
            255,
            ['nullable' => true],
            'sent_at'
        )->addColumn(
            'status',
            Table::TYPE_INTEGER,
            11,
            ['nullable' => true],
            'status)'
        )->addIndex(
                $setup->getIdxName('cv_crosssell_followup', ['id']),
                ['id']
        )->setComment(
            'Cross Sell '
        );
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
        
        $table = $installer->getConnection()->newTable(
        $installer->getTable('cv_autorelation_process_log')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
               ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                11,
                ['nullable' => false],
                'product_id'
            )->addColumn(
                'sku',
                Table::TYPE_TEXT,
                128,
                ['nullable' => false],
                'Sku'    
             )->addColumn(
                'rule_id',
                Table::TYPE_INTEGER,
                11,
                ['nullable' => false],
                'rule_id'        
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'created_at'
            )->addColumn(
                'count',
                 Table::TYPE_INTEGER,
                11,
                ['nullable' => false],
                'count'
            )->addColumn(
                'linkedIds',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'linkedIds'        
            )->addColumn(
                'info',
                 Table::TYPE_TEXT,
                128,
                ['nullable' => true],
                'info'
            )->addIndex(
                $setup->getIdxName('cv_autorelation_process_log', ['id']),
                ['id']
            )->setComment(
                'AutoRelation Process Log'
            );
        $installer->getConnection()->createTable($table);
        $installer->endSetup();

        $table = $installer->getConnection()->newTable(
            $installer->getTable('cv_product_recommendation_log')
                )->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                   ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'ID'
                )->addColumn(
                    'coupon_code',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'coupon_code'
                )->addColumn(
                    'product_linked',
                     Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'product_linked'
                )->addIndex(
                    $setup->getIdxName('cv_product_recommendation_log', ['id']),
                    ['id']
                )->setComment(
                    'AutoRelation Recommendation Log'
                );
            $installer->getConnection()->createTable($table);
            $installer->endSetup();
    }
}