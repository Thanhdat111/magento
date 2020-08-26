<?php
namespace Commercers\Workshop\Block\Adminhtml\Workshop\Button;

use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic;

/**
 * Class DeleteButton
 */
class DeleteButton extends Generic
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\''
                    . __('Are you sure you want to delete this note ?')
                    . '\', \'' . $this->getUrl('workshop/note/deletenote') . '\')',
                'sort_order' => 20,
            ];
        return $data;
    }
}