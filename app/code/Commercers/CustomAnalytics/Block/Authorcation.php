<?php
namespace Commercers\CustomAnalytics\Block;
        
use Magento\Backend\Model\Auth\Session;
use Magento\Authorization\Model\UserContextInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory as UserCollectionFactory;
class Authorcation extends \Magento\Backend\Block\Template
    {
        protected $authSession;
        protected $userContext;
        protected $userCollectionFactory;
        public function __construct( \Magento\Backend\Block\Template\Context $context,
        Session $authSession,
        UserContextInterface $userContext,
        UserCollectionFactory $userCollectionFactory,
        array $data = []) {
        $this->authSession = $authSession;
        $this->userContext = $userContext;
        $this->userCollectionFactory = $userCollectionFactory;
        parent::__construct($context, $data);
    }
 
    public function getCurrentUser(){
    
        return $this->authSession->getUser();
        }
    public function getUserRoleName(){
        $collection = $this->userCollectionFactory->create();
        $userId = $this->userContext->getUserId();
        $collection->addFieldToFilter('main_table.user_id', $userId);
        $userData = $collection->getFirstItem();    
        return $userData->getData()['role_name'];
        }
    }
    
?>
 