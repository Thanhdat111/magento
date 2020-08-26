<?php

namespace Commercers\Profilers\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface

{
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context) {
        $setup->startSetup();
        $conn = $setup->getConnection();
        $tableNameProfilers = $setup->getTable('profilers');
        $context->getVersion();
        if($conn->isTableExists($tableNameProfilers) != true){
            $table = $setup->getConnection()
                ->newTable($setup->getTable('profilers'))
                ->addColumn(
                    'id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'ID'
                )
                ->addColumn(
                    'name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Name'
                )
                ->addColumn(
                    'id_profiler',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Id Profiler'
                )
                ->addColumn(
                    'file_prefix',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'File Prefix'
                )
                ->addColumn(
                    'delimiter',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Delimiter'
                )
                ->addColumn(
                    'data_source',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Data Source'
                )
                ->addColumn(
                    'status',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    10,
                    ['nullable' => false, 'default' => ''],
                    'Enable'
                )
                ->addColumn(
                    'format',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Format'
                )
                ->addColumn(
                    'ftp_hostname',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'HostName'
                )
                ->addColumn(
                    'ftp_username',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'UserName'
                )
                ->addColumn(
                    'ftp_password',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Password'
                )
                ->addColumn(
                    'ftp_type',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Type'
                )
                ->addColumn(
                    'ftp_port',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Port'
                )
                ->addColumn(
                    'local_folder',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'LocalFolder'
                )
                ->addColumn(
                    'local_done',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Done'
                )
                ->addColumn(
                    'ftp_folder',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Folder Ftp'
                )
                ->addColumn(
                    'ftp_done_folder',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'DoneFolderFtp'
                )
                ->addColumn(
                    'ftp_local_tmp',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'LocalFolderFtp'
                )
                ->addColumn(
                    'enable_disable_cron',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    1,
                    ['nullable' => false, 'default' => 0],
                    'Enable Disable Cron'
                )
                ->addColumn(
                    'code',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Code'
                )
                ->addColumn(
                    'schedule',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Schedule'
                )
                ->addColumn(
                    'import_input_template',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '2M',
                    ['nullable' => false, 'default' => ''],
                    'Mapping'
                )
                ->addColumn(
                    'local_enable',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    1,
                    ['nullable' => false, 'default' => 0],
                    'Enable Local'
                )
                ->addColumn(
                    'ftp_enable',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    1,
                    ['nullable' => true, 'default' => 1],
                    'Enable Ftp'
                )
                ->addColumn(
                    'type',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    2,
                    ['nullable' => false, 'default' => 0],
                    'Import - 1 Or Export -2 '
                )
                ->addColumn(
                    'export_output_template',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '2M',
                    ['nullable' => false, 'default' => ''],
                    'Output Format'
                )
                ->addColumn(
                    'run_model_cronjob',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Run model cronjob'
                )
                ->addColumn(
                    'conditions_serialized',
                    Table::TYPE_TEXT,
                    '2M',
                    [],
                    'Conditions Serialized'
                )
                ->setComment("Profilers table");
            $setup->getConnection()->createTable($table);
        }
        $conn = $setup->getConnection();
        $tableNameProfilersLog = $setup->getTable('profiler_process_log');
        $context->getVersion();
        if($conn->isTableExists($tableNameProfilersLog) != true){
            $table = $setup->getConnection()
                ->newTable($setup->getTable('profiler_process_log'))
                ->addColumn(
                    'process_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'ID'
                )
                ->addColumn(
                    'id_profiler',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Id Profiler'
                )
                ->addColumn(
                    'status',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    255,
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
                // ->addColumn(
                //     'create_at',
                //     \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                //     null,
                //     ['nullable' => false, 'default' => ''],
                //       'Date'
                // )
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
                ->setComment("Profilers Process table");
            $setup->getConnection()->createTable($table);
        }
        $conn = $setup->getConnection();
        $tableNameProfilersRule = $setup->getTable('commercers_profilers_rule');
        $context->getVersion();
        if($conn->isTableExists($tableNameProfilersRule) != true){
            $table = $setup->getConnection()
                ->newTable($setup->getTable('commercers_profilers_rule'))
                ->addColumn(
                    'rule_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    11,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Rule ID'
                )
                ->addColumn(
                    'profiler_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    11,
                    ['nullable' => true],
                    'Profiler Id '
                )
                ->addColumn(
                    'conditions_serialized',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'Conditions Serialized'
                )
                ->addColumn(
                    'data_source',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => true, 'default' => ''],
                    'Data Source'
                )

                ->setComment("Profilers Rule table");
            $setup->getConnection()->createTable($table);
        }
        $setup->endSetup();
    }
}
