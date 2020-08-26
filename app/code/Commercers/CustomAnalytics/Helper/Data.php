<?php
namespace Commercers\CustomAnalytics\Heplper;
        
use Magento\Backend\Model\Auth\Session;
use \Magento\Framework\App\Helper\AbstractHelper;
 
class Data extends AbstractHelper
    {
        protected $authSession;
        public function __construct(Session $authSession) {
        $this->authSession = $authSession;
    }
 
    public function getCurrentUser(){
        return $this->authSession->getUser();
        }
    public function test(){
            return "thanhdat123";
        }
    }
    
?>
 