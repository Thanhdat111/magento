<?php



namespace Commercers\Profilers\Cron;
use Magento\Framework\App\ObjectManager as ObjectManager;
class Task 
{ 
    protected $_profilerFactory;
    
    public function __construct(
        \Commercers\Profilers\Model\ProfilersFactory $profilerFactory
        
    ){
        $this->_profilerFactory = $profilerFactory;
    }
    
    public function execute($schedule){
        //echo get_class($schedule);exit;
        $jobCode = $schedule->getJobCode();
        $profilers = $this->_profilerFactory->create()->getCollection()->addFieldToFilter('code',array('eq' => $jobCode));
        $profiler = $profilers->getFirstItem();
        
        $type = $profiler->getData("type");
        
        if($type == \Commercers\Profilers\Model\Constant::EXPORT_PROFILER){
            $process = \Magento\Framework\App\ObjectManager::getInstance()->get("\Commercers\Profilers\Service\Profiler\Process\Export");
            $process->execute($profiler);
        }
 
       if($type == \Commercers\Profilers\Model\Constant::IMPORT_PROFILER){
            $process = \Magento\Framework\App\ObjectManager::getInstance()->get("\Commercers\Profilers\Service\Profiler\Process\Import");
            $process->execute($profiler);
        }
 
        
        
    }
}