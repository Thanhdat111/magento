<?php

namespace Commercers\WarehouseManagement\Setup;

use \Magento\Framework\Setup\InstallSchemaInterface;
use \Magento\Framework\Setup\ModuleContextInterface;
use \Magento\Framework\Setup\SchemaSetupInterface;
use \Magento\Framework\DB\Ddl\Table;


class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $tableName = $setup->getTable('commercers_warehouse_management');

        if ($setup->getConnection()->isTableExists($tableName) != true) {
            $table = $setup->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'warehouse_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Warehouse ID'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false],
                    'Name'
                )
                ->addColumn(
                    'address',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false],
                    'Address'
                )
                ->addColumn(
                    'description',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'Description'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Date Add'
                )
                ->setComment('Commercers Warehouse Management - WareHouse');
            $setup->getConnection()->createTable($table);
        }
        $setup->endSetup();
        //warwhouse area
        $setup->startSetup();
            $table = $setup->getConnection()->newTable(
                $setup->getTable('commercers_warehouse_area')
                    )->addColumn(
                        'area_id',
                        Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'unsigned' => true,
                            'nullable' => false,
                            'primary' => true
                        ],
                        'ID'     
                    )->addColumn(
                        'warehouse_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['nullable' => false],
                        'warehouse_id'
                    )
                    ->addColumn(
                        'area',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => false],
                        'area warehouse'
                    )->addColumn(
                        'is_receiving_area',
                        Table::TYPE_SMALLINT,
                        null,
                        ['nullable' => false, 'default' => '0'],
                        'is_receiving_area'
                    )->addColumn(
                        'record_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => true],
                        'record_id'
                    )
                    ->setComment(
                        'warehouse_id')
                    ->setComment('Commercers Warehouse Management - WareHouse Cell');
                $setup->getConnection()->createTable($table);
        $setup->endSetup();
        //table row
        $setup->startSetup();
            $table = $setup->getConnection()->newTable(
                $setup->getTable('commercers_warehouse_row')
                    )->addColumn(
                        'id',
                        Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'unsigned' => true,
                            'nullable' => false,
                            'primary' => true
                        ],
                        'ID'     
                    )->addColumn(
                        'linking_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['nullable' => false],
                        'warehouse_id'
                    )->addColumn(
                        'rack_row',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => true],
                        'rack_row'
                    )
                    ->addColumn(
                        'rack_level',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => true],
                        'rack_level'
                    )
                    ->setComment(
                        'row_id')
                    ->setComment('Commercers Warehouse Management - WareHouse Row');
                $setup->getConnection()->createTable($table);
        $setup->endSetup();
        //table product in warehouse
        $setup->startSetup();
            $table = $setup->getConnection()->newTable(
                $setup->getTable('commercers_product_warehouse_linking')
                    )->addColumn(
                        'id',
                        Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'unsigned' => true,
                            'nullable' => false,
                            'primary' => true
                        ],
                        'ID'
                    )
                    ->addColumn(
                        'product_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => true],
                        'rack_row'
                    )
                    ->addColumn(
                        'area_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => true],
                        'area_id'
                    )
                    ->addColumn(
                        'quantity',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable'=>true],
                        'Quanlity'
                    )->addColumn(
                        'priority',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable'=>true,'default' => 0],
                        'Priority'
                    )
                    ->setComment('Commercers Warehouse Management - WareHouse Product');
                $setup->getConnection()->createTable($table);
        $setup->endSetup();
         $setup->startSetup();
            $table = $setup->getConnection()->newTable(
                $setup->getTable('commercers_warehouse_log')
                    )->addColumn(
                        'id',
                        Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'unsigned' => true,
                            'nullable' => false,
                            'primary' => true
                        ],
                        'ID'
                    )->addColumn(
                        'type',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => true],
                        'type'
                    )
                    ->addColumn(
                        'product_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => true],
                        'rack_row'
                    )->addColumn(
                        'warehouse_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => true],
                        'Warehouse'
                    )->addColumn(
                        'area',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => true],
                        'Area'
                    )->addColumn(
                        'rack_row',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => true],
                        'rack_row'
                    )->addColumn(
                        'rack_level',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => true],
                        'rack_level'
                    )->addColumn(
                        'qty_before',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable'=>true],
                        'Quanlity Before'
                    )->addColumn(
                        'qty_afterwards',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable'=>true],
                        'Quanlity After'
                    )->addColumn(
                        'difference',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable'=>true],
                        'difference'
                    )->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Date Add'
                )
                    ->setComment('Warehouse Management - WareHouse Log');
                $setup->getConnection()->createTable($table);
        $setup->endSetup();
    }

}
