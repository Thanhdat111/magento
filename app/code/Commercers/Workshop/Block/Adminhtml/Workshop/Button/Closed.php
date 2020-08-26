<?php

namespace Commercers\Workshop\Block\Adminhtml\Workshop\Button;
 
class Closed extends \Magento\Backend\Block\Template
{
     protected $_template = 'Commercers_Workshop::notes/closed.phtml';

     public function getCurrentId(){
          return $this->getRequest()->getParam('pk_entity_id');
     }
}