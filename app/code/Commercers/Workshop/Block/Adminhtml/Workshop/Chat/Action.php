<?php

namespace Commercers\Workshop\Block\Adminhtml\Workshop\Chat;
 
class Action extends \Magento\Backend\Block\Template
{
    protected $_template = 'Commercers_Workshop::chat/action.phtml';
    public $url = 11111111111;
     public function actionConfig(){
         $urls = array(
             'action_get_message' => $this->getUrl('workshop/chat/message'),
             'action_get_upload' => $this->getUrl('workshop/chat/upload')
         );
         $this->url =  json_encode($urls);
     }
}