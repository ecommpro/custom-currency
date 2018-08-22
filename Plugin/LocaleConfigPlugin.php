<?php
namespace EcommPro\CustomCurrency\Plugin;

class LocaleConfigPlugin
{
    protected $config;

    public function __construct(
        \EcommPro\CustomCurrency\Model\Config $config
    )
    {
        $this->config = $config;
    }

    public function afterGetAllowedCurrencies(\Magento\Framework\Locale\Config $subject, $result)
    {
        $currencies = $this->config->getAllowedCurrencies();
        $result = array_merge($result, array_keys($currencies));
        return $result;     
    }
}