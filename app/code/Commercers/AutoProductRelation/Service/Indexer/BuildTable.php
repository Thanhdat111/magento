<?php 
namespace Commercers\AutoProductRelation\Service\Indexer;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
class BuildTable
{
    protected $_productIndexerHelper;
    protected $_connection;
    protected $resource;
    protected $attributeFactory;
    protected $_cvAutoRelationIndex;
    protected $_productResource;
    protected $conditionCollection;
    public function __Construct(
        \Magento\Catalog\Helper\Product\Flat\Indexer $productIndexerHelper,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Catalog\Model\ResourceModel\Product $productResource,
        \Commercers\AutoProductRelation\Model\ResourceModel\CvAutoRelationIndex $cvAutoRelationIndex,
        \Magento\Catalog\Model\Product\Attribute\Repository $attributeFactory,
        \Commercers\AutoProductRelation\Model\ResourceModel\Rule\CollectionFactory $conditionCollection,
        SchemaSetupInterface $setup
    ){
        $this->_productResource = $productResource;
        $this->attributeFactory = $attributeFactory; 
        $this->_cvAutoRelationIndex = $cvAutoRelationIndex;
        $this->_productIndexerHelper = $productIndexerHelper;
        $this->resource = $resource;
        $this->setup = $setup;
        $this->conditionCollection = $conditionCollection;
        $this->_connection = $resource->getConnection();
    }
    Public function execute(){
    }
    public function build($conditionAttributes, $actionAttributes) {
        $attributeCode = array();
        if ($conditionAttributes) {
            foreach ($conditionAttributes as $conditionAttribute) {
                if (isset($conditionAttribute['conditions'])) {
                    foreach ($conditionAttribute['conditions'] as $value) {
                        $attributeCode[] = $value['attribute'];
                    }
                }
            }
        }
        if ($actionAttributes) {
            foreach ($actionAttributes as $actionAttribute) {
                if (isset($actionAttribute['conditions'])) {
                    foreach ($actionAttribute['conditions'] as $value) {
                        $attributeCode[] = $value['attribute'];
                    }
                }
            }
        }
        //ham loai bo cac attribute trung nhau
        $attributeCode = array_diff($attributeCode, array('category_ids','attribute_set_id'));
        $attributeCode = array_unique($attributeCode);
        //print_r($attributeCode);exit;
        $installer = $this->setup;
        $installer->startSetup();

        $tableName = $installer->getTable('cv_autorelation_index');

        //if table exist
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            //create new table
            $this->createTable($installer,$tableName,$attributeCode);
        }else{
            //drop table
            $installer->getConnection()->dropTable($tableName);
            //create new table
            $this->createTable($installer,$tableName,$attributeCode); 
        }

        $installer->endSetup();
    }

    public function getAttributesByCode($attributeCodes) {
        $attributes = array();
        foreach($attributeCodes as $attributeCode){
             $attributes[] = $this->attributeFactory->get($attributeCode);
        }
        return $attributes;
    }

    public function createTable($installer,$tableName,$attributeCode){
        $table = $installer->getConnection()
            ->newTable($tableName)
            ->setComment('Auto Relation Table')
            ->setOption('type', 'InnoDB')
            ->setOption('charset', 'utf8');
            //
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            $indexerTable = $resource->getTableName('cv_autorelation_index');
            $dataProductFiels =    $connection->describeTable($resource->getTableName('catalog_product_entity'));
            $attributes = $this->getAttributesByCode($attributeCode);
            $checkFields = array(); 
            foreach($dataProductFiels as $dataProductFiel){
                if($dataProductFiel['DATA_TYPE'] == 'int'){
                    $dataProductFiel['DATA_TYPE'] = Table::TYPE_INTEGER;
                }
                if($dataProductFiel['DATA_TYPE'] == 'varchar'){
                    $dataProductFiel['DATA_TYPE'] = Table::TYPE_TEXT;
                }
                $checkFields[] = $dataProductFiel['COLUMN_NAME'];
//              echo "<pre>";print_r($dataProductFiel);
                $table->addColumn(
                    $dataProductFiel['COLUMN_NAME'], $dataProductFiel['DATA_TYPE'], $dataProductFiel['LENGTH'], ['identity' => $dataProductFiel['IDENTITY'], 'unsigned' => $dataProductFiel['UNSIGNED'], 'nullable' => $dataProductFiel['NULLABLE'], 'primary' => $dataProductFiel['PRIMARY']]
            );
        }
//        print_r($attributeCode);exit;
        if ($attributeCode) {
            foreach ($attributes as $attribute) {

                if ($attribute['backend_type'] == 'int') {
                    $type = Table::TYPE_INTEGER;
                }
                if ($attribute['backend_type'] == 'varchar') {
                    $type = Table::TYPE_TEXT;
                }
                if ($attribute['backend_type'] == 'static') {
                    $type = Table::TYPE_TEXT;
                }
                $table->addColumn(
                        $attribute['attribute_code'], $type
                );
            }
        }

        $table->addColumn(
                        'qty_ordered',
                        Table::TYPE_INTEGER
                );
            $table->addColumn(
                        'qty',
                        Table::TYPE_INTEGER
                );
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}

