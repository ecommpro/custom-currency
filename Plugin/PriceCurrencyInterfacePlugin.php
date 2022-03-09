<?php
namespace EcommPro\CustomCurrency\Plugin;

class PriceCurrencyInterfacePlugin
{
    public function __construct(\EcommPro\CustomCurrency\Model\Config $config)
    {
        $this->config = $config;
    }

    public function beforeRoundPrice(\Magento\Framework\Pricing\PriceCurrencyInterface $subject,
        $price,
        $precision = 2
    ) {
        $currency = $this->config->getCurrency();
        
        if (!$currency) {
            return [$price, $precision];
        }

        $currency = array_merge([
            'precision' => $precision,
        ], $currency);

        return [$price, $currency['precision']];
    }

    public function aroundRound(\Magento\Framework\Pricing\PriceCurrencyInterface $subject,
        callable $proceed,
        $price
    ) {
        $currency = $this->config->getCurrency();
        
        if (!$currency) {
            return $proceed($price);
        }

        $currency = array_merge([
            'precision' => 2,
        ], $currency);

        return round($price, $currency['precision']);
    }
}
