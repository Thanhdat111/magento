<?php

namespace Commercers\AutoProductRelation\Helper;

class EmailLog extends \Magento\Framework\App\Helper\AbstractHelper
{    
    protected $_transportBuilder;
    protected $_inlineTranslation;
    protected $_escaper;

    public function __construct(
            \Magento\Framework\App\Helper\Context $context,
            \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
            \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
            \Magento\Framework\Escaper $escaper
    ) {
        parent::__construct($context);
        $this->_inlineTranslation = $inlineTranslation;
        $this->_escaper = $escaper;
        $this->_transportBuilder = $transportBuilder;
    }
    public function sendEmail($messages){
            //echo "<pre>";print_r($messages); exit;
        try{
            $this->_inlineTranslation->suspend();
            $emailAddress = 'tranthitravin2@gmail.com';
            //echo $emailAddress;exit;
            $transport = $this->_transportBuilder
                    ->setTemplateIdentifier('crosssell_email_log_template')
                    ->setTemplateOptions([
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store'=> \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ])
                    ->setTemplateVars([
                        'items' => $messages,
                    ])
                    ->addTo($emailAddress)
                    ->getTransport();
            $transport->sendMessage();
            $this->_inlineTranslation->resume();
            
        } catch (Exception $ex) {
            echo 'Can\'t send an email';
        }
    }
}