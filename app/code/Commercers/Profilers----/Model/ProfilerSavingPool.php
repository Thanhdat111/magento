<?php
namespace Commercers\Profilers\Model;

class ProfilerSavingPool 
{
    
    /**
     * @var ConfigProviderInterface[]
     */
    private $savingHandlers;
 
    /**
     * @param ConfigProviderInterface[] $configProviders
     * @codeCoverageIgnore
     */
    public function __construct(
        array $savingHandlers = []
    ) {
        $this->savingHandlers = $savingHandlers;
    }
 
    public function save($profiler, $data){
        if($this->savingHandlers){
            foreach($this->savingHandlers as $savingHandler){
                $savingHandler->execute($profiler, $data);
            }
        }
    }
}
