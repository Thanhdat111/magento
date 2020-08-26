<?php
namespace Commercers\Workshop\Model\Grid;

class JoinProductCollection extends \Commercers\Workshop\Model\ResourceModel\WorkshopReferences\Collection
{
    public function _initSelect()
    {
        parent::_initSelect();
        $this->addFilterToMap('value','status');
        $this->addFieldToFilter('reference_type', ['eq'=> \Commercers\Workshop\Model\WorkshopReferences::TYPE_ORDER_REFERENCE]);
        $this->addFieldToFilter('t.status', ['eq'=> \Commercers\Workshop\Ui\Component\Workshop\Status::STATUS_TASK_COMPLETED]);
        $this->addFieldToFilter('s.status', ['neq'=> \Magento\Sales\Model\Config\Source\Order\Status::STATE_COMPLETE]);
        $this->addFieldToFilter('s.status', ['neq'=> \Magento\Sales\Model\Config\Source\Order\Status::STATE_CANCELED]);
        $this->addFieldToFilter('s.status', ['neq'=> 'pre_complete']);
        $this->getSelect()->join(
            array('t' => 'workshop/workshopTask'),
            'main_table.fk_workshop_task_id = t.pk_entity_id',
            array('status' => 't.status', 'task_id' => 't.pk_entity_id', 'task_price' => 't.offer_price', 'task_type' => 't.type'));
        $this->getSelect()->join(
            array('s' => 'sales/order'),
            'main_table.reference_id = s.entity_id',
            array('order_status' => 's.status', 'increment_id' => 's.increment_id', 'order_id' => 's.entity_id'));
        return $this;
    }
}