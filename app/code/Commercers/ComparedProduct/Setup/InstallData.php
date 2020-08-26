<?php
namespace Commercers\ComparedProduct\Setup;

class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{
    public function install(\Magento\Framework\Setup\ModuleDataSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $data = [
            ['link_type_id' => \Commercers\ComparedProduct\Model\Product\Link::LINK_TYPE_COMPARED, 'code' => 'compared'],
        ];

        foreach($data as $bind) {
            $setup->getConnection()->insertForce($setup->getTable('catalog_product_link_type'), $bind);
        }

        $data = [
            [
                'link_type_id' => \Commercers\ComparedProduct\Model\Product\Link::LINK_TYPE_COMPARED,
                'product_link_attribute_code' => 'position',
                'data_type' => 'int',
            ]
        ];

        $setup->getConnection()->insertMultiple($setup->getTable('catalog_product_link_attribute'), $data);
    }
}