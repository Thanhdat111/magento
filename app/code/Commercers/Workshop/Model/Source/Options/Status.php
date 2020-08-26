<?php
namespace Commercers\Workshop\Model\Source\Options;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    const STATUS_TASK_NEW = 1;
    const STATUS_OFFER_CREATED = 2;
    const STATUS_OFFER_CREATED_FREE = 3;
    const STATUS_OFFER_ACCEPTED = 4;
    const STATUS_WAITING_FOR_WEAPON = 5;
    const STATUS_WAITING_FOR_PAYMENT = 14;
    const STATUS_TASK_EXECUTE = 6;
    const STATUS_TASK_FINISHED = 7;
    const STATUS_BILLING_CLAIM = 8;
    const STATUS_BILLING_REFUND = 9;
    const STATUS_BILLING_BALANCED = 10;
    const STATUS_TASK_COMPLETED = 11;
    const STATUS_TASK_CANCELED = 12;
    const STATUS_TASK_REOPENED = 13;

    protected $_options;
    /**
     * Get options
    *
    * @return array
    */
    public function toOptionArray($isMultiselect = false)
    {
        $status[static::STATUS_TASK_NEW] = 'task_new';
        $status[static::STATUS_OFFER_CREATED] = 'offer_created';
        $status[static::STATUS_OFFER_CREATED_FREE] = 'offer_created_free';
        $status[static::STATUS_OFFER_ACCEPTED] = 'offer_accepted';
        $status[static::STATUS_WAITING_FOR_WEAPON] = 'waiting_for_weapon';
        $status[static::STATUS_WAITING_FOR_PAYMENT] = 'waiting_for_payment';
        $status[static::STATUS_TASK_EXECUTE] = 'task_execute';
        $status[static::STATUS_TASK_FINISHED] = 'task_finished';
        $status[static::STATUS_BILLING_CLAIM] = 'billing_claim';
        $status[static::STATUS_BILLING_REFUND] = 'billing_refund';
        $status[static::STATUS_BILLING_BALANCED] = 'billing_balanced';
        $status[static::STATUS_TASK_COMPLETED] = 'task_completed';
        $status[static::STATUS_TASK_CANCELED] = 'task_canceled';
        $status[static::STATUS_TASK_REOPENED] = 'task_reopened';

        if (!$this->_options) {
            $optionsStatus = array();
            foreach ($status as $key => $value) {
                array_push($optionsStatus,
                        array('value' => $key, 'label' => $value)
                );
            }
            $this->_options = $optionsStatus;
        }

        $options = $this->_options;
        if (!$isMultiselect) {
            array_unshift($options,
                    array('value' => '', 'label' => __('--Please Select--')));
        }
        return $options;
    }
}