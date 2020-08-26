<?php

namespace Commercers\Profilers\Service;



interface DataSource {
    
    
      public function getCount($profiler);
      
      public function getData($profiler, $limits);
      
      public function getItemById($id, $profiler);

      public function getDataManual($dataForm, $profiler);


}