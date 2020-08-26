<?php
namespace Commercers\Workshop\Controller\Frontend;

use Magento\Framework\Controller\ResultFactory;

class Save extends \Magento\Framework\App\Action\Action
{
     protected $_pageFactory;
	protected $customerSession;
	protected $_storeManager; 
	protected $product; 
	protected $helper; 
	protected $config; 
	protected $email; 
	protected $resultJsonFactory; 

	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Customer\Model\Session $customerSession,
          \Magento\Catalog\Model\ProductFactory $product,
          \Commercers\Workshop\Helper\Data $helper,
		\Commercers\Workshop\Helper\Config $config,
		\Commercers\Workshop\Helper\Email $email,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\View\Result\PageFactory $pageFactory)
	{
		$this->resultJsonFactory = $resultJsonFactory; 
		$this->config = $config; 
		$this->helper = $helper; 
		$this->email = $email; 
		$this->product = $product; 
		$this->_storeManager = $storeManager; 
		$this->customerSession = $customerSession;
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	public function execute(){
		$data  = $this->getRequest()->getParams();
		$weaponLimitation = null;
		$weaponManufacturer = null;
		$weaponDescription = null;
		$orderId = isset($data['customerOrderNumber']) ? $data['customerOrderNumber'] : null;
		//get chosen parcel service
		$chosenCarrier = isset($data['chosenCarrier']) ? $data['chosenCarrier'] : null;
		$productId = isset($data['weapon_id']) ? $data['weapon_id'] : null;
		$customerId = $this->customerSession->getCustomerId();
		$storeId = $this->_storeManager->getStore()->getId();
		if(isset($productId)){
			$product = $this->product->create()->load($productId);
			$approvalId = $product->getData('begadi_approval');
		}else{
			//if no product was found, send customer declaration from weapon
			$weapon_manufacturer = htmlspecialchars(isset($data['customerWeaponManufacturer']) ? $data['customerWeaponManufacturer'] : null);
			$weapon_description = htmlspecialchars(isset($data['customerWeaponDescription']) ? $data['customerWeaponDescription'] : null);
			$approvalId = $this->config->getDefaultApprovalId();
		}
		// $chosen_carrier = $data('chosen-parcel-service');
		if (isset($data['limitation'])) {
			$weaponLimitation = true;
			//if limitation is wished, approval is FSK 14
			$approvalId = 72;
          }
		$type = $data['type'];
		//distinguish between type new and repair, set type and status value
		if ($type === 'new') {
			$type = \Commercers\Workshop\Model\Source\Options\Type::TYPE_NEW;
		} elseif ($type === 'repair') {
			$type = \Commercers\Workshop\Model\Source\Options\Type::TYPE_REPAIR_AND_TUNING;
		} elseif ($type === 'spare') {
			$type = \Commercers\Workshop\Model\Source\Options\Type::TYPE_SPARE_PART_ORDER;
          }
		//since BEAR-4, approval should be FSK 0 for spare parts type(s)
		switch ($type) {
			case \Commercers\Workshop\Model\Source\Options\Type::TYPE_SPARE_PART_IMPORT:
			case \Commercers\Workshop\Model\Source\Options\Type::TYPE_SPARE_PART_ORDER:
			    //FTODO: Grab approval id from something more reliable
			    $approvalId = 73;
			    break;
			default:
			    //dont change approval
			    break;
          }
		//set status as new for frontend cases
		$status = \Commercers\Workshop\Model\Source\Options\Status::STATUS_TASK_NEW;
          $message = htmlspecialchars($data['chat_message']);
		//create task:
		$this->helper->createWorkshopTask($type, $customerId, $message, $storeId,
		$approvalId, $productId, null, $status, $orderId, $weaponLimitation,
          $weaponManufacturer, $weaponDescription, $chosenCarrier);
          // echo $type;
          // exit;
		//send mail
		$sendMail = $this->config->isSendMailOnWaitingForWeapon();
		if($sendMail === true){
			$this->mail->sendEmail($this->config->getEmailTemplateId('new_order_created'),$customer,$idTask);
		}
		$result = $this->resultJsonFactory->create();
          return $result->setData(['success' => true]);
	}
}