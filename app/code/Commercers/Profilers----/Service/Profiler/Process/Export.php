<?php

namespace Commercers\Profilers\Service\Profiler\Process;

use Commercers\Profilers\Service\DataSource\Factory as DataSourceFactory;

class Export extends \Commercers\Profilers\Service\Profiler\Process
{

    const SEPARATED_LIMIT = 100;
    protected $currentFilePath = false;
    public function __construct(
        DataSourceFactory $dataSourceFactory,
        \Magento\Framework\Filesystem\Io\File $file,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\DomDocument\DomDocumentFactory $domFactory,
        \Commercers\Profilers\Service\Log $processLog,
        \Commercers\Profilers\Service\Xml $xmlService,
        \Magento\Framework\Filesystem\Io\Ftp $ftp,
        \Magento\Framework\Filesystem $filesystem
    )
    {
        $this->dataSourceFactory = $dataSourceFactory;
        $this->_domFactory = $domFactory;
        $this->_file = $file;
        $this->_directoryList = $directoryList;
        $this->_processLog = $processLog;
        $this->xmlService = $xmlService;
        $this->ftp = $ftp;
        $this->filesystem = $filesystem;

    }
    
    
    public function getOutput($xslDoc, $data)
    {

        $dom = new \DOMDocument();
        $xsl = new \XSLTProcessor();
        //echo '<pre>';print_r($data);exit;
        
        function utf8_decode($value) {

                    $replace = [
                        '&lt;' => '', '&gt;' => '', '&#039;' => '', '&amp;' => '',
                        '&quot;' => '', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ae',
                        '&Auml;' => 'A', 'Å' => 'A', 'Ā' => 'A', 'Ą' => 'A', 'Ă' => 'A', 'Æ' => 'Ae',
                        'Ç' => 'C', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C', 'Ď' => 'D', 'Đ' => 'D',
                        'Ð' => 'D', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E',
                        'Ę' => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ĝ' => 'G', 'Ğ' => 'G',
                        'Ġ' => 'G', 'Ģ' => 'G', 'Ĥ' => 'H', 'Ħ' => 'H', 'Ì' => 'I', 'Í' => 'I',
                        'Î' => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Į' => 'I',
                        'İ' => 'I', 'Ĳ' => 'IJ', 'Ĵ' => 'J', 'Ķ' => 'K', 'Ł' => 'K', 'Ľ' => 'K',
                        'Ĺ' => 'K', 'Ļ' => 'K', 'Ŀ' => 'K', 'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N',
                        'Ņ' => 'N', 'Ŋ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O',
                        'Ö' => 'Oe', '&Ouml;' => 'Oe', 'Ø' => 'O', 'Ō' => 'O', 'Ő' => 'O', 'Ŏ' => 'O',
                        'Œ' => 'OE', 'Ŕ' => 'R', 'Ř' => 'R', 'Ŗ' => 'R', 'Ś' => 'S', 'Š' => 'S',
                        'Ş' => 'S', 'Ŝ' => 'S', 'Ș' => 'S', 'Ť' => 'T', 'Ţ' => 'T', 'Ŧ' => 'T',
                        'Ț' => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue', 'Ū' => 'U',
                        '&Uuml;' => 'Ue', 'Ů' => 'U', 'Ű' => 'U', 'Ŭ' => 'U', 'Ũ' => 'U', 'Ų' => 'U',
                        'Ŵ' => 'W', 'Ý' => 'Y', 'Ŷ' => 'Y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'Ž' => 'Z',
                        'Ż' => 'Z', 'Þ' => 'T', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
                        'ä' => 'ae', '&auml;' => 'ae', 'å' => 'a', 'ā' => 'a', 'ą' => 'a', 'ă' => 'a',
                        'æ' => 'ae', 'ç' => 'c', 'ć' => 'c', 'č' => 'c', 'ĉ' => 'c', 'ċ' => 'c',
                        'ď' => 'd', 'đ' => 'd', 'ð' => 'd', 'è' => 'e', 'é' => 'e', 'ê' => 'e',
                        'ë' => 'e', 'ē' => 'e', 'ę' => 'e', 'ě' => 'e', 'ĕ' => 'e', 'ė' => 'e',
                        'ƒ' => 'f', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g', 'ĥ' => 'h',
                        'ħ' => 'h', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i',
                        'ĩ' => 'i', 'ĭ' => 'i', 'į' => 'i', 'ı' => 'i', 'ĳ' => 'ij', 'ĵ' => 'j',
                        'ķ' => 'k', 'ĸ' => 'k', 'ł' => 'l', 'ľ' => 'l', 'ĺ' => 'l', 'ļ' => 'l',
                        'ŀ' => 'l', 'ñ' => 'n', 'ń' => 'n', 'ň' => 'n', 'ņ' => 'n', 'ŉ' => 'n',
                        'ŋ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'oe',
                        '&ouml;' => 'oe', 'ø' => 'o', 'ō' => 'o', 'ő' => 'o', 'ŏ' => 'o', 'œ' => 'oe',
                        'ŕ' => 'r', 'ř' => 'r', 'ŗ' => 'r', 'š' => 's', 'ù' => 'u', 'ú' => 'u',
                        'û' => 'u', 'ü' => 'ue', 'ū' => 'u', '&uuml;' => 'ue', 'ů' => 'u', 'ű' => 'u',
                        'ŭ' => 'u', 'ũ' => 'u', 'ų' => 'u', 'ŵ' => 'w', 'ý' => 'y', 'ÿ' => 'y',
                        'ŷ' => 'y', 'ž' => 'z', 'ż' => 'z', 'ź' => 'z', 'þ' => 't', 'ß' => 'ss',
                        'ſ' => 'ss', 'ый' => 'iy', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
                        'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
                        'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
                        'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
                        'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '',
                        'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a',
                        'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
                        'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l',
                        'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
                        'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
                        'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e',
                        'ю' => 'yu', 'я' => 'ya'
                    ];

                    return str_replace(array_keys($replace), $replace, $value);
                }
                
       
        $dom->loadXML($xslDoc);
        

        $xsl->importStyleSheet($dom);
        $xsl->registerPHPFunctions();
        
        $xmlSource = $this->_domFactory->create();
        //print_r($this->xmlService->arrayToXml($data));exit;
        $xmlSource->loadXML($this->xmlService->arrayToXml($data), LIBXML_PARSEHUGE|LIBXML_NSCLEAN);
        $out = $xsl->transformToXML($xmlSource);


        return $out;
    }


    public function execute($profiler)
    {

        $dataProcessLog = $this->_processLog->execute($profiler->getId());
        $dataSource = $this->dataSourceFactory->get($profiler->getDataSource());

        $count = $dataSource->getCount($profiler);

        $pages = $count/static::SEPARATED_LIMIT + 1;

        $date = new \DateTime();
        $fileName = "export_" . $profiler->getData('data_source') . "_" . $date->format('dmYHis') . "." . $profiler->getData('format');

        for($page = 1; $page <= $pages; $page++){
            $limits = array(
                'limit' => static::SEPARATED_LIMIT,
                'offset' => static::SEPARATED_LIMIT*($page-1)
            );
            $data = $dataSource->getData($profiler, $limits);
            try {
                $xslDoc = $profiler->getData('export_output_template');
                $out = $this->getOutput($xslDoc, $data);

                $this->writeFileFromOutputFromat($profiler, $fileName, $out);
                $status = 1;
                $message = "Success!";
            } catch (Exception $e) {
                $status = 0;
                $message = $e->getMessage();
            }
        }
        if($status){
            /*FTP */
            $enableLocal = $profiler->getData('local_enable');
            $enableFtp = $profiler->getData('ftp_enable');
            $localFolderFtp = $profiler->getData('ftp_folder');


            if ($this->currentFilePath && $enableFtp == 1) {
                $filePath = DIRECTORY_SEPARATOR . $localFolderFtp;
                $tmpDir = 'import_export_tmp';
                $tmpPath =$this->_directoryList->getPath('var').$tmpDir.'/';

                $this->writeFileIntoFtp($profiler, $fileName, $this->currentFilePath .'/'.$fileName);

                if(file_exists($tmpPath .'/'.$fileName)){
                    $fileName->rm($tmpPath .'/'.$fileName);
                }
            }
        }


        $this->_processLog->executeAfter($dataProcessLog['process_id'], $status, $message);


    }



    public function writeFileFromOutputFromat($profiler, $fileName,$out)
    {
//       echo "<pre>";print_r($profiler->getData());exit;
        if(!$this->currentFilePath){
            $enableLocal = $profiler->getData('local_enable');
            $enableFtp = $profiler->getData('ftp_enable');
            $localFolder = $profiler->getData('local_folder');
            $localFolderFtp = $profiler->getData('ftp_folder');

            $tmpDir = 'import_export_tmp';

            if ($enableLocal == 1) {
                $filePath = DIRECTORY_SEPARATOR . $localFolder;

            }
            $tmpPath =$this->_directoryList->getPath('var').$tmpDir.'/';

            if($filePath){
              $path = $this->_directoryList->getPath('var').$filePath.'/';
            }else{
              $path = $this->_directoryList->getPath('var').$tmpDir.'/';
            }
            //print_r($profiler->getData()); exit;
            $this->currentFilePath = $path;

            if (!is_dir($this->currentFilePath)) {
                $this->_file->mkdir($this->currentFilePath, 0775);
            }

        }

	$writer = $this->filesystem->getDirectoryWrite('var');

	$file = $writer->openFile($this->currentFilePath.'/'.$fileName, 'a');
	try {
		$file->lock();
		try {
			$file->write($out);
		}
		finally {
			$file->unlock();
		}
	}
	finally {
		$file->close();
	}

    }

    public function writeFileIntoFtp($profiler, $fileName, $sourceFile){


        $hostName = $profiler->getData('ftp_hostname');
        $userName = $profiler->getData('ftp_username');
        $password = $profiler->getData('ftp_password');
        $type =  $profiler->getData('ftp_type');
        $ftpFolder = $profiler->getData('ftp_folder');
        $isSSL = false;
        if($type == 1){
            $isSSL = true;
        }
        $port = $profiler->getData('port');


        $open = $this->ftp->open(
               array(
                   'host' => $hostName,
                   'user' => $userName,
                   'password' => $password,
                   'ssl' => $isSSL,
                   'passive' => true
               )
           );
        $this->ftp->mkdir($ftpFolder);
        $this->ftp->write($ftpFolder.'/'.$fileName, file_get_contents($sourceFile));
        $this->ftp->close();
    }




}
