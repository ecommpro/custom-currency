<?php
namespace EcommPro\CustomCurrency\Observer;

class BlockBefore implements \Magento\Framework\Event\ObserverInterface
{
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
        return $this;

        /*
        $block = $observer->getData('block');
        $class = get_class($block);
        echo "[$class]";
        $name = $block->getNameInLayout();

        //catalog.navigation.state
        //echo "[" . $block->getNameInLayout() . "]";
		return $this;
        */
	}
}