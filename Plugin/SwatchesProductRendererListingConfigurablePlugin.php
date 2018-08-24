<?php
namespace EcommPro\CustomCurrency\Plugin;

class SwatchesProductRendererListingConfigurablePlugin
{ 

    public function __construct(
        \EcommPro\CustomCurrency\Model\Config $config,
        \Magento\Framework\Locale\FormatInterface $localeFormat
    ) {
        $this->config = $config;
        $this->_localeFormat = $localeFormat;
    }

    public function aroundGetPriceFormatJson(\Magento\Swatches\Block\Product\Renderer\Listing\Configurable $subject,
        callable $proceed,
        ...$args
    ) {
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
        return json_encode($format);
    }
}
