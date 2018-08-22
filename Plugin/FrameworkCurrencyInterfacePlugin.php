<?php
namespace EcommPro\CustomCurrency\Plugin;

class FrameworkCurrencyInterfacePlugin
{
    protected $config;
    protected $appState;

    public function __construct(
        \EcommPro\CustomCurrency\Model\Config $config,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\State $appState
    )
    {
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->appState = $appState;
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

        $areaCode = $this->appState->getAreaCode();
        $locale = $subject->getLocale();
        $formatOptions = [
            'locale' => $locale,
            'number_format' => '#,##0.00',
        ];

        if (isset($currency['precision'])) {
            $formatOptions['precision'] = $currency['precision'];
        }

        //$format = \Zend_Locale_Data::getContent($locale, 'currencynumber');
        
        $valueStr = \Zend_Locale_Format::toNumber($value, $formatOptions);
        $pattern = $this->config->getPattern();

        return str_replace('{{amount}}', $valueStr, $pattern);
    }
}