<?php
/**
 * Commercers VN
 * HieuND
 */
namespace Commercers\Workshop\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        /**
        * Create table 'commercers_workshop_chat'
        */
        $table = $setup->getConnection()
            ->newTable($setup->getTable('commercers_workshop_chat'))
            ->addColumn(
                'pk_entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'consecutive id'
            )
            ->addColumn(
                'fk_workshop_task_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['nullable' => false],
                'the reference to the corresponding wokshop task'
            )
            ->addColumn(
                'fk_customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['nullable' => true],
                'the customer id as foreign key'
            )
            ->addColumn(
                'fk_admin_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['nullable' => true],
                'contains the admins user id when the task was created by an admin'
            )
            ->addColumn(
                'message',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                128,
                ['nullable' => true],
                'message the person has sent'
            )
            ->addColumn(
                'link_file',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                128,
                ['nullable' => false, 'default' => ''],
                'link_file'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => true],
                'the creation date'
            )
            ->setComment("This table stores customer approval details and their assigned informations.");
        $setup->getConnection()->createTable($table);
        
        /**
        * Create table 'commercers_workshop_claims'
        */
        $table = $setup->getConnection()
            ->newTable($setup->getTable('commercers_workshop_claims'))
            ->addColumn(
                'pk_entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'consecutive id'
            )
            ->addColumn(
                'fk_workshop_task_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['nullable' => false],
                'the reference to the corresponding wokshop task'
            )
            ->addColumn(
                'amount',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false],
                'the amount of the claim'
            )
            ->addColumn(
                'payed_status',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false],
                'weather the amount is payed or not (or variations, see implementation)'
            )
            ->addColumn(
                'comment',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => true],
                'a comment'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'the creation date'
            )
            ->addColumn(
                'last_changed',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'the date when the approval was last changed'
            )
            ->setComment("This table stores information about what begadi aditionally claims from the customer.");
        $setup->getConnection()->createTable($table);

        /**
        * Create table 'commercers_workshop_note'
        */
        $table = $setup->getConnection()
            ->newTable($setup->getTable('commercers_workshop_note'))
            ->addColumn(
                'pk_entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'consecutive id'
            )
            ->addColumn(
                'note',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => false],
                'written notes'
            )
            ->addColumn(
                'fk_author_admin_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false],
                'AuthorID who has pushed the notes'
            )
            ->addColumn(
                'fk_responsible_admin_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false],
                'responsible-AuthorID who has pushed the notes'
            )
            ->addColumn(
                'responsible_admin',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => true],
                'responsible-AuthorName who has pushed the notes'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => true],
                'the creation date'
            )
            ->addColumn(
                'closed_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'the date when the note closed'
            )
            ->setComment("This table stores workshop notes details.");
        $setup->getConnection()->createTable($table);

        /**
        * Create table 'commercers_workshop_references'
        */
        $table = $setup->getConnection()
            ->newTable($setup->getTable('commercers_workshop_references'))
            ->addColumn(
                'pk_entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'consecutive id'
            )
            ->addColumn(
                'fk_workshop_task_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['nullable' => false],
                'the reference to the corresponding workshop task'
            )
            ->addColumn(
                'reference_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false],
                'the type of reference we want to refer to (hehe), see implementation what this numebrs represent'
            )
            ->addColumn(
                'reference_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false],
                'contains the id if the reference'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'the date when the reference was connected to the task'
            )
            ->addColumn(
                'comment',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => true],
                'an additional comment'
            )
            ->setComment("This table stores workshop reference details.");
        $setup->getConnection()->createTable($table);

        /**
        * Create table 'commercers_workshop_refunds'
        */
        $table = $setup->getConnection()
            ->newTable($setup->getTable('commercers_workshop_refunds'))
            ->addColumn(
                'pk_entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'consecutive id'
            )
            ->addColumn(
                'fk_workshop_task_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['nullable' => false],
                'the reference to the corresponding workshop task'
            )
            ->addColumn(
                'amount',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false],
                'the amount of the claim'
            )
            ->addColumn(
                'payed_status',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false],
                'weather the amount is payed or not (or variations, see implementation)'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'the creation date'
            )
            ->addColumn(
                'last_changed',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'the date when the approval was last changed'
            )
            ->addColumn(
                'comment',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => true],
                'a comment'
            )
            ->setComment("This table stores information about what begadi should refunds to the customer.");
        $setup->getConnection()->createTable($table);

        /**
        * Create table 'commercers_workshop_log'
        */
        $table = $setup->getConnection()
            ->newTable($setup->getTable('commercers_workshop_log'))
            ->addColumn(
                'pk_entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'consecutive id'
            )
            ->addColumn(
                'fk_admin_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['nullable' => false,'unsigned' => true],
                'the admin id as foreign key'
            )
            ->addColumn(
                'type',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false],
                'the type of the task, se implementation for what the id means'
            )
            ->addColumn(
                'comment',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => true],
                'a comment'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'the creation date'
            )
            ->setComment("This table log");
        $setup->getConnection()->createTable($table);

        /**
        * Create table 'commercers_workshop_task'
        */
        $table = $setup->getConnection()
            ->newTable($setup->getTable('commercers_workshop_task'))
            ->addColumn(
                'pk_entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'consecutive id'
            )
            ->addColumn(
                'fk_customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['nullable' => false,'unsigned' => true],
                'the customer id as foreign key'
            )
            ->addColumn(
                'fk_store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['nullable' => false,'unsigned' => true],
                'the store views id of wherethe customer created the task from'
            )
            ->addColumn(
                'fk_product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['nullable' => true,'unsigned' => true],
                'contains the product id if a product was selected'
            )
            ->addColumn(
                'reference_product_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => true],
                'reference to product'
            )
            ->addColumn(
                'fk_admin_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['nullable' => true,'unsigned' => true],
                'contains the admins user id when the task was created by an admin'
            )
            ->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false],
                'the status of the task, see implementation for what the id means'
            )
            ->addColumn(
                'type',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false],
                'the type of the task, se implementation for what the id means'
            )
            ->addColumn(
                'approval_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false],
                'states the approval id of the task'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => true],
                'the date when the task was created'
            )
            ->addColumn(
                'additional_status_ask_for_weapon_marked_early',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'default' => 0],
                'when admin marks the task to ask user to send in his weapon earlier than it should be (by status)'
            )
            ->addColumn(
                'additional_status_weapon_received',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['nullable' => false],
                'weather the weapon was received by begadi or not'
            )
            ->addColumn(
                'additional_status_weapon_received_date',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => true],
                'the date when the weapon was received'
            )
            ->addColumn(
                'additional_status_task_ready_for_execution',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['nullable' => false],
                'when billing department marks the original order / task item inside the original order as ready for execution of the workshop task'
            )
            ->addColumn(
                'additional_status_task_processed',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['nullable' => false],
                'when workshop admin marks the -pure- task as already processed (may not be payed when this is set)'
            )
            ->addColumn(
                'additional_status_chat_answer_not_necessary',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'default' => 0],
                'when admin marks the last chat message from customer as not needed to answer'
            )
            ->addColumn(
                'additional_status_task_will_stay_in_budget',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'default' => 0],
                'when workshop user marks the tast that it will stay priced as it is'
            )
            ->addColumn(
                'employee',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => true],
                'for the BEAR-285, the color of the traffic light'
            )
            ->addColumn(
                'order_increment_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => true],
                'the given input the customer inserted when creating the task - might contain gibberish'
            )
            ->addColumn(
                'offer_price',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => true],
                'the price which the admin offers for the task'
            )
            ->addColumn(
                'weapon_limitation',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['nullable' => true],
                'if customer wishes a limitation for the weapopn or not'
            )
            ->addColumn(
                'weapon_manufacturer',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => true],
                'the manufacturer of the weapon'
            )
            ->addColumn(
                'weapon_description',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => true],
                'the description of the weapon'
            )
            ->addColumn(
                'chosen_parcel_service',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                50,
                ['nullable' => true],
                'the parcel service which the customer has chosen'
            )
            ->addColumn(
                'begadi_intern_comment',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => true],
                'intern comment field for begadi to workshop task'
            )
            ->setComment("This table stores workshop task details.");
        $setup->getConnection()->createTable($table);
    }
}