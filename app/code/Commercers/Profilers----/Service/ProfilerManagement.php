<?php

namespace Commercers\Profilers\Service;

use Magento\Framework\Module\Dir;

class ProfilerManagement {

    const MODULE_ETC_DIR = 'etc';

    private $_profilersFactory;
    protected $_profilers = array();
    protected $_dir;
    protected $_scopeConfig;
    protected $_parser;
    protected $_moduleReader;

    public function __construct(
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\Filesystem\DirectoryList $dir, \Magento\Framework\Xml\Parser $parser, \Magento\Framework\Filesystem $filesystem, \Magento\Framework\Module\Dir\Reader $reader, \Commercers\Profilers\Model\ProfilersFactory $profilersFactory
    ) {
        $this->_dir = $dir;
        $this->_scopeConfig = $scopeConfig;
        $this->_parser = $parser;
        $this->_moduleReader = $reader;
        $this->_profilersFactory = $profilersFactory;
    }

    public function getProfilersFromDataBase() {
        $data = array();
        $profilersFactory = $this->_profilersFactory->create();
        $collection = $profilersFactory->getCollection();
        $data = $collection->getData();
        return $data;
    }

    public function getProfilersFromXml($configPath) {
        $moduleDirectory = $this->_moduleReader->getModuleDir(Dir::MODULE_ETC_DIR, 'Commercers_Profilers');
        if ($configPath) {
            $listPathXmlFile = $moduleDirectory . DIRECTORY_SEPARATOR . $configPath;
            if (is_dir($listPathXmlFile)) {
                $iterator = new \FilesystemIterator($listPathXmlFile);
                if ($iterator->getPathName() != '') {
                    /** @var \FilesystemIterator $file */
                    foreach ($iterator as $file) {
                        $pathFileNameLocal[] = $file->getPathname();
                        $getFileName[] = $file->getFilename();
                    }
                    /**
                     * Read XML and convert to array
                     */
                    for ($i = 0; $i < count($pathFileNameLocal); $i++) {
                        $parserArray = $this->_parser->load($pathFileNameLocal[$i])->xmlToArray();
                    }

                    foreach ($parserArray as $profiler) {
                        $profiler[] = $profiler;
                    }
                    foreach ($profiler as $key => $value) {
                        $data = $value;
                    }
                    return $data;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getProfilersSelected($selectedProfilers = false, $enableProfilersFromXml, $configPath = false) {
        if ($enableProfilersFromXml == 1) {
            $data = $this->getProfilersFromXml($configPath);
            if ($selectedProfilers != false) {
                $selectedProfilers = explode(',', $selectedProfilers);
                foreach ($data as $value) {
                    if (isset($value[0])) {
                        foreach ($value as $values) {
                            if ($selectedProfilers !== false && in_array($values['id'], $selectedProfilers)) {
                                $dataProfilers[] = $values;
                            } else {
                                continue;
                            }
                        }
                    } else {
                        if ($selectedProfilers !== false && in_array($value['id'], $selectedProfilers)) {
                            $dataProfilers[] = $value;
                        } else {
                            continue;
                        }
                    }
                }
            }
            return $dataProfilers;
        } else {
            $data = $this->getProfilersFromDataBase();
            if ($selectedProfilers != false) {
                $selectedProfilers = explode(',', $selectedProfilers);
                foreach ($data as $value) {
                    if (isset($value[0])) {
                        foreach ($value as $values) {
                            if ($selectedProfilers !== false && in_array($values['id_profiler'], $selectedProfilers)) {
                                $dataProfilers[] = $values;
                            } else {
                                continue;
                            }
                        }
                    } else {
                        if ($selectedProfilers !== false && in_array($value['id_profiler'], $selectedProfilers)) {
                            $dataProfilers[] = $value;
                        } else {
                            continue;
                        }
                    }
                }
                if (isset($dataProfilers)) {
                    foreach ($dataProfilers as $profiler) {
                        $getGataProfilers[] = $this->getProfilers($profiler);
                    }
                    return $getGataProfilers;
                }
            }
        }
    }

    public function getMappings($mapping) {
        if (!is_null($mapping)) {
            $mapping = preg_split('/\\r\\n|\\r|\\n/', $mapping);

            $value = array();
            foreach ($mapping as $_mapping) {
                $attribute = explode('=', trim($_mapping));
                $value[$attribute[0]] = $attribute[1];
            }

            return $value;
        }
        return false;
    }

    public function getFtpFromProfiler($profiler) {

        $ftp['hostname'] = $profiler['hostname'];
        $ftp['username'] = $profiler['username'];
        $ftp['password'] = $profiler['password'];
        $ftp['type'] = $profiler['type'];
        $ftp['port'] = $profiler['port'];
        $ftp['localfolder'] = $profiler['localfolder'];
        $ftp['done'] = $profiler['done'];
        $ftp['folderftp'] = $profiler['folderftp'];
        $ftp['donefolderftp'] = $profiler['donefolderftp'];
        $ftp['localfolderftp'] = $profiler['localfolderftp'];

        return $ftp;
    }

    public function getProfilers($profiler) {

        $ftp = $this->getFtpFromProfiler($profiler);
        if ($profiler['mapping']) {
            $mappings = $this->getMappings($profiler['mapping']);
            $data['mapping'] = $mappings;
        }
        $data['name'] = $profiler['name'];
        $data['id'] = $profiler['id_profiler'];
        $data['data_source'] = $profiler['data_source'];
        $data['file_prefix'] = $profiler['file_prefix'];
        $data['delimiter'] = $profiler['delimiter'];
        $data['enablelocal'] = $profiler['enablelocal'];
        $data['enableftp'] = $profiler['enableftp'];
        $data['format'] = $profiler['format'];
        $data['ftp'] = $ftp;
        $data['outputformat'] = $profiler['outputformat'];
        $data['id_primary'] = $profiler['id'];
        return $data;
    }

}
