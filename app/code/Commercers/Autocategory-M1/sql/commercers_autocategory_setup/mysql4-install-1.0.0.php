<?php
$installer = $this;

$installer->startSetup();

$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('autocategory/rule')}(
    `rule_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `category_id` int(11),
    `is_enabled` int(1),
    `stock_qty` VARCHAR(50),
    `last_update` DATETIME,
    `rule_update` int(1),
    `last_index` int(11),
    `conditions_serialized` MEDIUMTEXT,
    PRIMARY KEY (`rule_id`)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->endSetup();