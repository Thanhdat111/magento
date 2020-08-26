<?php
/**
 *  Commercers Vietnam
 *  Toan Dao 
 */
namespace Commercers\WarehouseManagement\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{

    /**
     * @param Context $context
     * @param EncryptorInterface $encryptor
     */
    public function __construct(

    )
    {
 
    }

    public function getOption()
    {
        return 1234;
    }
   
    
}