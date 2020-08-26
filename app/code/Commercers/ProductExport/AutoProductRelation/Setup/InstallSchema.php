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
            $installer->getTable('commercers_crosssell_followup')
        )->addColumn(
            'followup_email_id',
            Table::TYPE_INTEGER,
            null,
            [ 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'followup_email_id'
        )->addColumn(
            'customer_id',
            Table::TYPE_INTEGER,
            11,
            ['nullable' => true],
            'customer_id'
        )->addColumn(
            'product_id',
            Table::TYPE_INTEGER,
            11,
            ['nullable' => true],
            'product_id'
        )->addColumn(
            'product_name',
            Table::TYPE_TEXT,
            512,
            ['nullable' => true],
            'product_name'       
        )->addColumn(
            'product_sku',
            Table::TYPE_TEXT,
            128,
            ['nullable' => true],
            'product_sku'
        )->addColumn(
            'order_id',
            Table::TYPE_INTEGER,
            '2M',
            ['nullable' => true],
            'order_id'
        )->addColumn(
            'order_increment',
            Table::TYPE_TEXT,
            128,
            ['nullable' => false],
            'order_increment'
        )->addColumn(
            'email',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'email)'
        )->addColumn(
            'name',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'name'
        )
		->addColumn(
            'sent',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => true, 'default' => '0'],
            'sent'
        )
		->addColumn(
            'utm_code',
            Table::TYPE_TEXT,
            512,
            ['nullable' => false],
            'utm_code'
        )
		->addColumn(
            'coupon_code',
            Table::TYPE_TEXT,
            512,
            ['nullable' => false],
            'coupon_code'
        )
		->addColumn(
            'type',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'type'
        )
		->addColumn(
            'created',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false],
            'created'
        )->addColumn(
            'updated',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false],
            'updated'
        )
		->addIndex(
                $setup->getIdxName('commercers_crosssell_followup', ['followup_email_id']),
                ['followup_email_id']
        )->setComment(
            'Cross Sell '
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
            512,
            ['nullable' => true],
            'customer_email'
        )->addColumn(
            'promotion_code',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'promotion_code'       
        )->addColumn(
            'referral_code',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'referral_code'
        )->addColumn(
            'created_at',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'created_at'
        )->addColumn(
            'sent_at',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'sent_at'
        )->addColumn(
            'status',
            Table::TYPE_TEXT,
            255,
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
    }
}