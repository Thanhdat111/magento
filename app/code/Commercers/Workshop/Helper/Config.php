<?php

namespace Commercers\Workshop\Helper;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
     protected $scopeConfig;

     public function __construct(
          \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     ){
          $this->scopeConfig = $scopeConfig;
     }

     public function isSendMailOnWaitingForWeapon()
     {
          return $this->scopeConfig->getValue('workshop/frontend_group/waiting_for_weapon', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) === '1';
     }

     public function isSendMailOnBillingCreatedClaim(){
          return $this->scopeConfig->getValue('workshop/frontend_group/subsequent_claims_created', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) === '1';
     }

     public function isSendMailOnOfferCreated(){
          return $this->scopeConfig->getValue('workshop/frontend_group/offer_created', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId) === '1';
     }

     public function getDefaultApprovalId(){
          return $this->scopeConfig->getValue('workshop/frontend_group/standard_fsk', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
     }

     // public function isSendMailOnTaskNew(){
     //      $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
     //      return $this->scopeConfig->getValue('workshop/frontend_group/offer_created', $storeScope, $storeId) === '1';
     // }

     public function getEmailTemplateId($field)
     {
          return $this->scopeConfig->getValue('workshop/frontend_group/'.$field,\Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
     }
     
}