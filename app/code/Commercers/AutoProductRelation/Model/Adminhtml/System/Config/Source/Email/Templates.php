<?php
namespace Commercers\AutoProductRelation\Model\Adminhtml\System\Config\Source\Email;

class Templates {
	protected $emailTemplateCollectionFactory;
	
	public function __construct(
		\Magento\Email\Model\ResourceModel\Template\CollectionFactory $emailTemplateCollectionFactory
	){
			$this->emailTemplateCollectionFactory = $emailTemplateCollectionFactory;
	}
	public function toOptionArray(){
		$emailTemplate = $this->emailTemplateCollectionFactory->create()->toOptionArray();        
        return $emailTemplate;
	}
}