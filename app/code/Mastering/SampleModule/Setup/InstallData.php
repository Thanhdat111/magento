<?php

namespace Mastering\SampleModule\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface{

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        // TODO: Implement install() method.
        $setup->startSetup();
        $setup->getConnection()->insert(
            $setup->getTable('mastering_sample_item'),
            [
                'name' =>'Item 1',
            ]
        );
        $setup->getConnection()->insert(
            $setup->getTable('mastering_sample_item'),
            [
                'name' =>'Item 2',
            ]
        );
        $setup->endSetup();
    }
}