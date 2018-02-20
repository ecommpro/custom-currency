<?php
namespace EcommPro\CustomCurrency\Plugin;

class DirectoryCurrencyPlugin
{
    protected $config;
    protected $_localeFormat;
    protected $_localeCurrency;

    public function __construct(
        \EcommPro\CustomCurrency\Model\Config $config,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Framework\Locale\CurrencyInterface $localeCurrency
    )
    {
        $this->config = $config;
        $this->_localeFormat = $localeFormat;
        $this->_localeCurrency = $localeCurrency;
    }

    public function beforeFormatPrecision(\Magento\Directory\Model\Currency $subject, ...$args)
    {
        $args[1] = $this->config->getPrecision();
        return $args;
    }

    // public function formatTxt($price, $options = [])
    public function aroundFormatTxt(\Magento\Directory\Model\Currency $subject, callable $proceed, ...$args)
    {
        $price = $args[0];
        if (count($args) > 1) {
            $options = $args[1];
        } else {
            $options = [];
        }

        if (!is_numeric($price)) {
            $price = $this->_localeFormat->getNumber($price);
        }
        /**
         * Fix problem with 12 000 000, 1 200 000
         *
         * %f - the argument is treated as a float, and presented as a floating-point number (locale aware).
         * %F - the argument is treated as a float, and presented as a floating-point number (non-locale aware).
         */
        $precision = $this->config->getPrecision();
        $price = sprintf("%.{$precision}F", $price);
        return $this->_localeCurrency->getCurrency($subject->getCode())->toCurrency($price, $options);
    }
}