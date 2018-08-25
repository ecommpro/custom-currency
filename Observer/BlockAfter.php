<?php
namespace EcommPro\CustomCurrency\Observer;

use EcommPro\CustomCurrency\Model\Context;

class BlockAfter implements \Magento\Framework\Event\ObserverInterface
{
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
        $block = $observer->getData('block');
        $class = get_class($block);
        $name = $block->getNameInLayout();

		$status = Context::pop();
		$current = Context::current();

		switch($current) {
			case false:
				\EcommPro\CustomCurrency\Model\Config::disableHtml();
				break;
			case true:
				\EcommPro\CustomCurrency\Model\Config::enableHtml();
				break;
		}

		return $this;
	}
}