<?php

namespace Commercers\AutoProductRelation\Helper;

class Email extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_ERROR_NOTIFICATION_TO = 'section_stockimport/group_stockimport/error_notification_to';
    
    protected $_transportBuilder;
    protected $_inlineTranslation;
    protected $_escaper;
    protected $_scopeConfig;
    
    public function __construct(
            \Magento\Framework\App\Helper\Context $context,
            \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
            \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
            \Magento\Framework\Escaper $escaper,
            \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->_inlineTranslation = $inlineTranslation;
        $this->_escaper = $escaper;
        $this->_transportBuilder = $transportBuilder;
        $this->_scopeConfig = $scopeConfig;
    }
    public function sendEmail($data){
		//print_r($data); exit;
        try{
            $this->_inlineTranslation->suspend();
            
            $email = $this->_scopeConfig->getValue(self::XML_ERROR_NOTIFICATION_TO, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $transport = $this->_transportBuilder
                    ->setTemplateIdentifier('crosssell_email_template')
                    ->setTemplateOptions([
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store'=> \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ])
                    ->setTemplateVars([
                        'items' => $data,
                    ])
                    ->addTo('vobachuc@gmail.com')
                    ->getTransport();
            $transport->sendMessage();
            $this->_inlineTranslation->resume();
            
        } catch (Exception $ex) {
            echo 'Can\'t send an email';
        }
    }
}