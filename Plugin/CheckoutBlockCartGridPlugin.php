<?php
namespace EcommPro\CustomCurrency\Plugin;

class CheckoutBlockCartGridPlugin
{
    public function aroundToHtml(\Magento\Checkout\Block\Cart\Grid $subject, callable $proceed, ...$args)
    {
        \EcommPro\CustomCurrency\Model\Config::enableHtml();
        $result = $proceed(...$args);
        \EcommPro\CustomCurrency\Model\Config::disableHtml();
        return $result;
    }
}
