<?php
namespace EcommPro\CustomCurrency\Observer;

class BlockAfter implements \Magento\Framework\Event\ObserverInterface
{
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
        //$block = $observer->getData('block');
        //echo "[/" . $block->getNameInLayout() . "]";
		return $this;
	}
}