<?php


namespace Commercers\AutoCategory\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Category save observer
 */
class CategorySaveObserver implements ObserverInterface
{
    /**
     * Handler for category save event
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $category = $observer->getEvent()->getCategory();
        $nameCategory = $category->getName();
        if ($category->getIsEnable()) {
            if ($category->getSmartRuleError()) {
                throw new LocalizedException(
                    $category->getSmartRuleError()
                );
            } else {
                $rule = $category->getSmartRule();
                if ($rule) {
                    $rule->setId($category->getId());
                    $rule->setTitle($nameCategory);
                    $rule->save();
                }
            }
        }
    }
}
