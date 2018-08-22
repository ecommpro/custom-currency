<?php
namespace Mazedonia\CelsisCurrency\Locale;

class Format extends \Magento\Framework\Locale\Format
{
    public function getPriceFormat($localeCode = null, $currencyCode = null)
    {
        $format = parent::getPriceFormat($localeCode, $currencyCode);
        $format['pattern'] = preg_replace('/%s/', '<span class="amount">%s</span>', $format['pattern']);
        return $format;
    }
}