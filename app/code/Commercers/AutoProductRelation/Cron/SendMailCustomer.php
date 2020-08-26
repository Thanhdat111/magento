<?php

namespace Commercers\AutoProductRelation\Cron;

class SendMailCustomer {
    const FOLLOWUP_SENT_MAIL = 'section_cross_sell/group_commercers_auto_product_relation_followup_email/enable_auto_product_relation_followup_email_sent_followup_email';
    const FOLLOWUP_SENT_STATUS = 'section_cross_sell/group_commercers_auto_product_relation_followup_email/enable_auto_product_relation_followup_email_order_status';

    protected $crossSellFollowCollection;
    protected $sendEmailWithCrossSellProduct;
    protected $date;
    protected $scopeConfig;
    protected $order;
    public function __construct(
        \Commercers\AutoProductRelation\Model\Services\CrossSell\SendEmailWithCrossSellProduct $sendEmailWithCrossSellProduct,
        \Commercers\AutoProductRelation\Model\ResourceModel\CrossSellFollow\CollectionFactory $crossSellFollowCollection,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Sales\Api\Data\OrderInterface $order, 
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->crossSellFollowCollection = $crossSellFollowCollection;
        $this->sendEmailWithCrossSellProduct = $sendEmailWithCrossSellProduct;
        $this->date = $date;
        $this->scopeConfig = $scopeConfig;
        $this->order = $order;
    }

    public function execute() {
        $valueDay = $this->scopeConfig->getValue(self::FOLLOWUP_SENT_MAIL, \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
        $statusSentMails = $this->scopeConfig->getValue(self::FOLLOWUP_SENT_STATUS, \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
        $statusSentMails = explode(',', $statusSentMails);
        $today = $this->date->gmtDate();
        $crossSellFollowCollection = $this->crossSellFollowCollection->create();
        foreach ($crossSellFollowCollection as $value) {
            $sendDay = date("Y-m-d", strtotime(date("Y-m-d", strtotime($value->getCreatedAt())) . " + " . $valueDay . " day"));
            $orderId = $value->getOrderId();
            $order = $this->order->load($orderId);
            $checkStatus = '';
            if ($value->getStatus() == 0) {
                foreach ($statusSentMails as $statusSentMail) {
                    if ($statusSentMail == $order->getStatus()) {
                        $checkStatus = true;
                    }
                }
                if ($checkStatus) {
                    if (strtotime($today) >= strtotime($sendDay)) {
                        $email = $value->getCustomerEmail();
                        $idSendMail = $value->getId();
                        $this->sendEmailWithCrossSellProduct->execute($idSendMail, $orderId, $email);
                    }
                }
            }
        }
    }

}
