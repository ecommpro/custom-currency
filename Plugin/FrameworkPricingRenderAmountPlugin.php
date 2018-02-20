<?php
namespace EcommPro\CustomCurrency\Plugin;

class FrameworkPricingRenderAmountPlugin
{
    protected $config;

    public function __construct(
        \EcommPro\CustomCurrency\Model\Config $config
    )
    {
        $this->config = $config;
    }

    public function beforeFormatCurrency(\Magento\Framework\Pricing\Render\AmountRenderInterface $subject, ...$args)
    {
        $args[2] = $this->config->getPrecision();
        return $args;
    }
}