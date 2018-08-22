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
    public function beforeToCurrency(\Magento\Framework\CurrencyInterface $subject, ...$args)
    {
        $args = $args + [null, []];
        list($value, $options) = $args;

        $areaCode = $this->appState->getAreaCode();
        
        $currency = $this->config->getCurrency();

        if (!$currency) {
            return [$value, $options];
        }

        if (isset($currency['symbol_html']) && !empty($currency['symbol_html'])) {
            $symbolHtml = str_replace([
                '{{amount}}', '{{symbol}}', '{{symbol_image}}', '{{image}}',
            ], [
                '#,##0.00', '¤', $currency['symbolimage_src'], $currency['symbolimage_src'],
            ], $currency['symbol_html']);
        } else {
            $symbolHtml = '';
        }

        if (isset($currency['format']) && !empty($currency['format'])) {
            $options['format'] = str_replace([
                '{{amount}}', '{{symbol}}', '{{symbol_image}}', '{{image}}', '{{symbol_html}}',
            ], [
                '#,##0.00', '¤', $currency['symbolimage_src'], $currency['symbolimage_src'], $symbolHtml,
            ], $currency['format']);
        }

        // affect: admin dashboard, product list, product view NO AJAX
        // warning: in admin there are places where html tags are rendered as text

        if ($this->appState->getAreaCode() === \Magento\Framework\App\Area::AREA_FRONTEND && !empty($symbolHtml)) {
            $options['symbol'] = $symbolHtml;
        } else {
            $options['symbol'] = $currency['symbol'];
        }

        if (isset($currency['precision'])) {
            $options['precision'] = $currency['precision'];
        }
        
        return [$value, $options];
    }
}