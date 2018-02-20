<?php
namespace EcommPro\CustomCurrency\Plugin;

class FrameworkPricingPriceCurrencyPlugin
{
    protected $config;

    public function __construct(
        \EcommPro\CustomCurrency\Model\Config $config
    )
    {
        $this->config = $config;
    }

    /*
    public function format(
        $amount,
        $includeContainer = true,
        $precision = self::DEFAULT_PRECISION,
        $scope = null,
        $currency = null
    ) {
    */
    public function beforeFormat(\Magento\Framework\Pricing\PriceCurrencyInterface $subject, ...$args)
    {
        if (count($args >= 3)) {
            $args[2] = $this->config->getPrecision();
        } else {
            if (count($args) < 2) $args[] = true;
            if (count($args) < 3) $args[] = $this->config->getPrecision();
        }
        return $args;
    }

    //public function convertAndRound($amount, $scope = null, $currency = null, $precision = self::DEFAULT_PRECISION)    
    public function beforeConvertAndRound(\Magento\Framework\Pricing\PriceCurrencyInterface $subject, ...$args)
    {
        if (count($args) >= 4) {
            $args[3] = $this->config->getPrecision();
        } else {
            if (count($args) < 2) $args[] = null;
            if (count($args) < 3) $args[] = null;
            if (count($args) < 4) $args[] = $this->config->getPrecision();
        }
        return $args;
    }

    // public function round($price)
    public function aroundRound(\Magento\Framework\Pricing\PriceCurrencyInterface $subject, callable $proceed, ...$args)
    {
        $result = round($args[0], $this->config->getPrecision());
        return $result;
    }

}