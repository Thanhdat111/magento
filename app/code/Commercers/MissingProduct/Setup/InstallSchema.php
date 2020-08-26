<?php


namespace Commercers\MissingProduct\Setup;


use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        // TODO: Implement install() method.
        $setup->startSetup();
        $tableName = $setup->getTable('commercers_missing_product');
        if ($setup->getConnection()->isTableExists($tableName) != true) {
            $table = $setup->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'nullable' => false, 'primary' => true]
                )->addColumn(
                    'ean',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'New EAN'
                )->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'New Name'
                )->addColumn(
                    'brand',
                    Table::TYPE_TEXT,
                    '255',
                    ['nullable' => true],
                    'New Brand'
                )
                ->addColumn(
                    'price',
                    Table::TYPE_FLOAT,
                    ['nullable' => true],
                    'Price'
                )->addColumn(
                    'sku',
                    Table::TYPE_TEXT,
                    '255',
                    ['nullable' => true],
                    'New SKU'
                )->addColumn(
                    'size',
                    Table::TYPE_TEXT,
                    '255',
                    ['nullable' => true],
                    'New Size'
                )->addColumn(
                    'website',
                    Table::TYPE_TEXT,
                    '255',
                    ['nullable' => false, 'default' => 'Main as default'],
                    'Website'
                )->setComment('Commercers Missing Product Table');
            $setup->getConnection()->createTable($table);
            $setup->endSetup();
        }
    }
}