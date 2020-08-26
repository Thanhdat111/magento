<?php


namespace Commercers\ProductAdvance\Observer;


use Magento\Framework\App\State;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ProductSaveObserver implements ObserverInterface
{
    /**
     * @var State
     */
    protected $_state;

    public function __construct(
        State $state
    ) {
        $this->_state = $state;

    }

    public function execute(Observer $observer)
    {
        if ($this->_state->getAreaCode() !== 'adminhtml') {
            //when not inside adminpanel dont change ean
            return;
        }
        $product = $observer->getProduct();
        $productSku = $product->getSku();
        $productBegadiPrice = $product->getBegadiMpdPrice();
        $productBegadiSize = $product->getBegadiMpdGroesse();
        $productBegadiWeapon = $product->getBegadiMpdWaffe();
        $product->setBegadiMpdPrice($productBegadiPrice);
        $product->setBegadiMpdGroesse($productBegadiSize);
        $product->setBegadiMpdWaffe($productBegadiWeapon);
//        $product->save();
        
        return $this;
    }
}
