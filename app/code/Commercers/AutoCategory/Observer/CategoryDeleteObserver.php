<?php


namespace Commercers\AutoCategory\Observer;



use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Commercers\AutoCategory\Model\RuleFactory;

/**
 * Category delete observer
 */
class CategoryDeleteObserver implements ObserverInterface
{
    /**
     * Rule factory
     *
     * @var RuleFactory
     */
    protected $ruleFactory;

    /**
     * Intialize observer
     *
     * @param RuleFactory $ruleFactory
     */
    public function __construct(
        RuleFactory $ruleFactory
    ) {
        $this->ruleFactory = $ruleFactory;
    }

    /**
     * Handler for category delete event
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $category = $observer->getEvent()->getCategory();
        /** @var \Faonni\SmartCategory\Model\Rule $rule */
        $rule = $this->ruleFactory->create()
            ->load($category->getId());

        if ($rule->getId()) {
            $rule->delete();
        }
    }
}