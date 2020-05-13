<?php
namespace EcommPro\CustomCurrency\Plugin;

use Magento\Framework\Pricing\Amount\AmountInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class FrameworkPricingRenderAmountPlugin
{
    protected $config;
    protected $localeResolver;

    public function __construct(
        \Magento\Framework\Locale\Resolver $localeResolver,
        \EcommPro\CustomCurrency\Model\Config $config
    )
    {
        $this->localeResolver = $localeResolver;
        $this->config = $config;
    }

    public function aroundFormatCurrency(\Magento\Framework\Pricing\Render\AmountRenderInterface $subject, callable $proceed, $amount, $includeContainer = true, $precision = PriceCurrencyInterface::DEFAULT_PRECISION)
    {
        $currency = $this->config->getCurrency();
        if (!$currency) {
            return $proceed($amount, $includeContainer, $precision);
        }

        $locale = $this->localeResolver->getLocale();
        $formatOptions = [
            'locale' => $locale,
            'number_format' => '#,##0.00',
        ];

        if (isset($currency['format_precision'])) {
            $formatOptions['precision'] = $currency['format_precision'];
        }

        $amountStr = \Zend_Locale_Format::toNumber($amount, $formatOptions);
        $pattern = $this->config->getPatternHtml();

        $result = str_replace('{{amount}}', $amountStr, $pattern);

        if ($includeContainer) {
            return '<span class="price">' . $result . '</span>';
        }

        return $result;
    }
}
