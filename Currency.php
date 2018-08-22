<?php
namespace EcommPro\CustomCurrency;

class Currency extends \Magento\Framework\Currency
{
    protected $config;

    public function __construct(
        CacheInterface $appCache,        
        $options = null,
        $locale = null,
        \Magento\Framework\App\State $state,
        \EcommPro\CustomCurrency\Model\Config $config
    ) {
        $this->state = $state;
        $this->config = $config;
        parent::__construct($appCache, $options, $locale);
    }

    public function toCurrency($value = null, array $options = array())
    {
        //print_r($options);
        //print_r($this->getLocale());
        //$options['format'] = '#,##0.00¤';

        /*
        if (!isset($options['override']) || !$options['override']) {
            $options['format'] = '#,##0.00 ¤';
        }
        */

        $options['precision'] = $this->config->getPrecision();
        return parent::toCurrency($value, $options);
    }
}