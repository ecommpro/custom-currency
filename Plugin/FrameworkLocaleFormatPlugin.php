<?php
namespace EcommPro\CustomCurrency\Plugin;

class FrameworkLocaleFormatPlugin
{
    protected $config;

    public function __construct(
        \EcommPro\CustomCurrency\Model\Config $config,
        \Magento\Framework\App\State $appState
    )
    {
        $this->config = $config;
        $this->appState = $appState;
    }


    public function afterGetPriceFormat(\Magento\Framework\Locale\FormatInterface $subject, $result)
    {
        $currency = $this->config->getCurrency();
        if (!$currency) {
            return $result;
        }

        $pattern = $this->config->getPattern();
        //$result['pattern'] = preg_replace('/%s(?![ $])/', '%s ', $result['pattern']);
        $result['pattern'] = str_replace('{{amount}}', '%s', $pattern);

        $currency = $this->config->getCurrency();

        if (isset($currency['format_precision'])) {
            $result['precision'] = $currency['format_precision'];
            $result['requiredPrecision'] = $currency['format_precision'];
        }
        return $result;
    }
}