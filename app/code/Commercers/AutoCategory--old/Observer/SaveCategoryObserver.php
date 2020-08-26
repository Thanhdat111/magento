<?php


namespace Commercers\AutoCategory\Observer;


use Magento\Framework\Event\Observer;

class SaveCategoryObserver implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @param Observer $observer
     * @return void
     */
    private $category = null;

    public function execute(Observer $observer)
    {
        $this->category = $observer->getEvent()->getCategory();
        $data = $this->category;
        $dataPrepare = $this->prepareData($data);
        $this->category->loadPost($dataPrepare);
        $this->category->save();
    }

    protected function prepareData($data)
    {

        if (isset($data['rule']['conditions'])) {
            $data['conditions'] = $data['rule']['conditions'];
        }

        unset($data['rule']);

        return $data;
    }
}