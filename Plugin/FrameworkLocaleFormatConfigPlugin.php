<?php
namespace EcommPro\CustomCurrency\Plugin;

class FrameworkLocaleFormatConfigPlugin
{
    protected $config;

    public function __construct(
        \EcommPro\CustomCurrency\Model\Config $config,
        \Magento\Framework\App\State $state
    )
    {
        $this->config = $config;
        $this->state = $state;
    }
    

    public function afterGetPriceFormat(\Magento\Framework\Locale\FormatInterface $subject, $result)
    {
        $result['pattern'] = preg_replace('/%s(?![ $])/', '%s ', $result['pattern']);
        $currency = $this->config->getCurrency();
        if (isset($currency['precision'])) {
            $result['precision'] = $currency['precision'];
            $result['requiredPrecision'] = $currency['precision'];
        }
        return $result;
    }
}