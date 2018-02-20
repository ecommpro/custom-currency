<?php
namespace EcommPro\CustomCurrency\Plugin;

class FrameworkLocaleFormatConfigPlugin
{
    protected $config;

    public function __construct(
        \EcommPro\CustomCurrency\Model\Config $config
    )
    {
        $this->config = $config;
    }

    public function afterGetPriceFormat(\Magento\Framework\Locale\FormatInterface $subject, $result)
    {
        $result['precision'] = $this->config->getPrecision();
        $result['requiredPrecision'] = $this->config->getPrecision();
        return $result;
    }
}