<?php

namespace Commercers\Workshop\Helper;

class Email extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_transportBuilder;
    protected $_inlineTranslation;
    protected $_escaper;
    protected $_scopeConfig;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->_inlineTranslation = $inlineTranslation;
        $this->_escaper = $escaper;
        $this->urlBuilder = $urlBuilder;
        $this->_transportBuilder = $transportBuilder;
        $this->_scopeConfig = $scopeConfig;

    }
    public function sendEmail($templateId, $customer, $idTask){
        try{
            $senderEmail = $this->scopeConfig->getValue('trans_email/ident_general/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $senderName = $this->scopeConfig->getValue('trans_email/ident_general/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $sender = [
                'email'=>$senderEmail,
                'name'=>$senderName

            ];
            $emailAddress = 'daoduytoan.it@gmail.com';
            $customerName = $customer->getName();
            $this->_inlineTranslation->suspend();
            $transport = $this->_transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions([
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store'=> \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ])
                ->setTemplateVars([
                    'name' => $customerName,
                    'workshoptask_id' => $idTask,
                    'link_task' =>$this->urlBuilder->getUrl('workshop/front/mytask',array('id'=>$idTask))
                ])
                ->setFrom($sender)
                ->addTo($emailAddress)
                ->getTransport();
            $transport->sendMessage();
            $this->_inlineTranslation->resume();

        } catch (Exception $ex) {
            echo 'Can\'t send an email';
        }
    }
}