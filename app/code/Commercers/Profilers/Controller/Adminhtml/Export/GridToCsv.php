<?php

namespace Commercers\Profilers\Controller\Adminhtml\Export;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Response\Http\FileFactory;

class gridToCsv extends Action {

    protected $resultRawFactory;
    protected $csvWriter;
    protected $fileFactory;
    protected $directoryList;

    public function __construct(
    \Magento\Backend\App\Action\Context $context, 
    \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, 
    \Magento\Framework\App\Response\Http\FileFactory $fileFactory, 
    \Magento\Framework\File\Csv $csvWriter, 
    \Magento\Framework\Filesystem $filesystem, 
    \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        $this->csvWriter = $csvWriter;
        $this->resultRawFactory = $resultRawFactory;
        $this->fileFactory = $fileFactory;
        $this->directoryList = $directoryList;
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        parent::__construct($context);
    }

    public function exportCsv() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('profilers');
        $select = $connection->select()
                ->from(
                $tableName
        );
        $data = $connection->fetchAll($select);
        $fileDirectory = \Magento\Framework\App\Filesystem\DirectoryList::MEDIA;
        $fileName = 'profilers.csv';
        $filePath = $this->directoryList->getPath($fileDirectory) . "/" . $fileName;
        $columns = $this->getColumnHeader();
        $stream = $this->directory->openFile($filePath, 'w+');
        $stream->lock();
        if (is_array($columns)) {
            foreach ($columns as $column) {
                $header[] = $column;
            }
            $stream->writeCsv($header);
            if (is_array($data)) {
                foreach ($data as $value) {
                    $stream->writeCsv($value);
                }
                $content = array();
                $content['type'] = 'filename'; // must keep filename
                $content['value'] = $filePath;
                $content['rm'] = '1'; //remove csv from var folder
                return $this->fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
            }
        }
    }

    public function execute() {
        $this->_view->loadLayout(false);
        $this->exportCsv();
    }

    public function getColumnHeader() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('profilers');
        $select = $connection->select()
                ->from(
                $tableName
        );
        $data = $connection->fetchAll($select);
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $b[] = $key;
            }
            if (is_array($value)) {
                foreach ($value as $key => $value) {
                    $a[] = $key;
                }
            }
        }
        return $a;
    }

}
