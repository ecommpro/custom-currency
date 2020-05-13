<?php
namespace EcommPro\CustomCurrency\Plugin;

class FrameworkCurrencyInterfacePlugin
{
    protected $config;
    protected $appState;
    protected $localeResolver;

    public function __construct(
        \EcommPro\CustomCurrency\Model\Config $config,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\State $appState,
        \Magento\Framework\Locale\Resolver $localeResolver
    )
    {
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->appState = $appState;
        $this->localeResolver = $localeResolver;
    }

    //public function toCurrency($value = null, array $options = array())
    public function aroundToCurrency(\Magento\Framework\CurrencyInterface $subject, callable $proceed, ...$args)
    {
        $args = $args + [null, []];
        list($value, $options) = $args;

        $currency = $this->config->getCurrency();

        if (!$currency || isset($options['display']) && $options['display']) {
            return $proceed(...$args);
        }

        $locale = $this->localeResolver->getLocale();
        $formatOptions = [
            'locale' => $locale,
            'number_format' => '#,##0.00',
        ];

        if (isset($currency['format_precision'])) {
            $formatOptions['precision'] = $currency['format_precision'];
        }

        //$format = \Zend_Locale_Data::getContent($locale, 'currencynumber');

        $valueStr = \Zend_Locale_Format::toNumber($value, $formatOptions);
        $pattern = $this->config->getPattern();

        return str_replace('{{amount}}', $valueStr, $pattern);
    }
}
