<?php
namespace Commercers\Workshop\Model\ResourceModel\WorkshopReferences;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init("Commercers\Workshop\Model\WorkshopReferences","Commercers\Workshop\Model\ResourceModel\WorkshopReferences");
    }

    protected function _renderFiltersBefore() {
        $joinTable = $this->getTable('commercers_workshop_task');

        $this->getSelect()->JoinLeft($joinTable.' as secondtable','main_table.fk_workshop_task_id = secondtable.pk_entity_id', array('type'));
        parent::_renderFiltersBefore();
   }

    protected function _initSelect()
    {
        parent::_initSelect();
    
        $this->getSelect()->joinLeft(
                ['secondTable' => $this->getTable('commercers_workshop_task')],
                'main_table.fk_workshop_task_id = secondTable.pk_entity_id',
                ['type'=>'type']
            );
        $this->addFilterToMap('type', 'secondTable.type');
        return $this;
    }
}