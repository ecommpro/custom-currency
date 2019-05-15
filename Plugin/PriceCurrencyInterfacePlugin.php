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
        $precision
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
}
