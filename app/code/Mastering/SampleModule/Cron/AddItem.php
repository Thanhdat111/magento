<?php


namespace Mastering\SampleModule\Cron;


use Mastering\SampleModule\Model\ItemsFactory;
use Mastering\SampleModule\Model\Config;

class AddItem
{
    private $itemFactory;
    private $config;
    public function __construct(ItemsFactory $itemFactory, Config $config)
    {
        $this->itemFactory = $itemFactory;
        $this->config = $config;
    }

    public function execute()
    {
        if ($this->config->isEnabled()) {
            $this->itemFactory->create()
                ->setName('Scheduled item')
                ->setDescription('Created at ' . date("Y-m-d", time()))
                ->save();
        }
    }
}