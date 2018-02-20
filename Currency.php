<?php
namespace EcommPro\CustomCurrency;

class Currency extends \Magento\Framework\Currency
{
    protected $config;

    public function __construct(
        \EcommPro\CustomCurrency\Model\Config $config
    )
    {
        $this->config = $config;
    }

    public function toCurrency($value = null, array $options = array())
    {
        //print_r($options);
        //print_r($this->getLocale());

        //$options['format'] = '#,##0.00¤';
        $options['precision'] = $this->config->getPrecision();
        return parent::toCurrency($value, $options);
    }
}