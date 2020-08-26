<?php
namespace Commercers\Profilers\Ui\Component\Profilers\Form;
 
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     */
    protected $session;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->collection = $objectManager->create('\Commercers\Profilers\Model\Profilers')->getCollection();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    
    /**
     * Get session object
     *
     * @return SessionManagerInterface
     */
    protected function getSession()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        if ($this->session === null) {
            $this->session = $objectManager->get('\Magento\Framework\Session\SessionManagerInterface');
        }
        return $this->session;
    }


    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    { 
        //return du lieu ra form edit
        $this->loadedData = [];
        foreach($this->getCollection()->getItems() as $item){
            $this->loadedData[$item->getId()] = array(
                'general' => $item->getData(),
                'ftp' => $item->getData(),
                'local' => $item->getData(),
                'mapping' => $item->getData(),
                'cron' => $item->getData(),
                'outputformat' => $item->getData(),
                'conditions' => $item->getData(),
                'disable' => true
            );
        }
        $currentProfilerId = $this->getSession()->getProfilerId();
        
        if($currentProfilerId){
            
            $this->loadedData[$currentProfilerId]['disabled'] = true;
            if($this->loadedData[$currentProfilerId]['general']['type'] == 1){
                    $this->loadedData[$currentProfilerId]['hide-import'] = true;
                    $this->loadedData[$currentProfilerId]['hide-export'] = 0;
                    $this->loadedData[$currentProfilerId]['disabled_when_export'] =  false;
                    $this->loadedData[$currentProfilerId]['disabled_when_import'] =  true;
                    
                }
                if($this->loadedData[$currentProfilerId]['general']['type'] == 0){
                    $this->loadedData[$currentProfilerId]['hide-import'] = 0;
                    $this->loadedData[$currentProfilerId]['hide-export'] = true;
                    
                    $this->loadedData[$currentProfilerId]['disabled_when_export'] =  true;
                    $this->loadedData[$currentProfilerId]['disabled_when_import'] =  false;
                    
                }
             
             
        }else{
            $this->loadedData[$currentProfilerId]['disabled'] = false;
        }
        
        
        $this->loadedData[$currentProfilerId]['disable_field_name'] =  false;
        $this->loadedData[$currentProfilerId]['disable_field_id_profiler'] =  false;
        $this->loadedData[$currentProfilerId]['disable_field_data_source'] =  false;
        $this->loadedData[$currentProfilerId]['disable_field_import_export'] =  false;
        
        
        return $this->loadedData;
    }
}