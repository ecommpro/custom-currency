<?php
namespace EcommPro\CustomCurrency\Observer;

use EcommPro\CustomCurrency\Model\Context;

class BlockBefore implements \Magento\Framework\Event\ObserverInterface
{
    public function __construct(
        \EcommPro\CustomCurrency\Model\Config $config
    ) {
        $this->config = $config;
    }

	public function execute(\Magento\Framework\Event\Observer $observer)
	{
        $block = $observer->getData('block');
        $class = get_class($block);
        $name = $block->getNameInLayout();
        
        $status = Context::current();

        $htmlblocks = $this->config->getEnabledHTMLBlocks();
        foreach($htmlblocks as $htmlblock) {
            if ($block instanceof $htmlblock) {
                $status = true;
                break;
            }
        }
        
        switch($status) {
            case true:
                \EcommPro\CustomCurrency\Model\Config::enableHtml();
        }
        Context::push($status);

		return $this;
	}
}