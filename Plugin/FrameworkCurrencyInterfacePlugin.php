<?php
namespace EcommPro\CustomCurrency\Plugin;

class FrameworkCurrencyInterfacePlugin
{
    protected $config;
    protected $appState;

    public function __construct(
        \EcommPro\CustomCurrency\Model\Config $config,
        \Magento\Framework\App\State $appState
    )
    {
        $this->config = $config;
        $this->appState = $appState;
    }

    //public function toCurrency($value = null, array $options = array())
    public function beforeToCurrency(\Magento\Framework\CurrencyInterface $subject, ...$args)
    {
        $args = $args + [null, []];
        list($value, $options) = $args;

        $areaCode = $this->appState->getAreaCode();        
        $locale = $subject->getLocale();

        //$options['format'] = '#,##0.00 ¤';
        //$options['symbol'] = 'TRX';

        $options['precision'] = $this->config->getPrecision();
        return [$value, $options];
    }
}