<?php
namespace EcommPro\CustomCurrency\Plugin;

class ConfigurableProductProductViewTypeConfigurablePlugin
{ 

    public function __construct(
        \EcommPro\CustomCurrency\Model\Config $config,
        \Magento\Framework\Locale\FormatInterface $localeFormat
    ) {
        $this->config = $config;
        $this->_localeFormat = $localeFormat;
    }

    public function afterGetJsonConfig(\Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject,
        $result
    ) {

        $array = json_decode($result, true);
        $format = $this->_localeFormat->getPriceFormat();

        $currency = $this->config->getCurrency();
        
        if ($currency) {
            $pattern = $this->config->getPatternHtml();
            $format['pattern'] = str_replace('{{amount}}', '%s', $pattern);
            if (isset($currency['precision'])) {
                $format['precision'] = $currency['precision'];
                $format['requiredPrecision'] = $currency['precision'];
            }
        }

        $array['priceFormatHTML'] = $format;
        return json_encode($array);
    }
}
