<?php
namespace EcommPro\CustomCurrency\Plugin;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Locale\FormatInterface as LocaleFormat;

class CheckoutDefaultConfigProviderPlugin
{
    public function __construct(
        CheckoutSession $checkoutSession,
        LocaleFormat $localeFormat
    ) {
        $this->localeFormat = $localeFormat;
        $this->checkoutSession = $checkoutSession;
    }

    public function afterGetConfig(\Magento\Checkout\Model\ConfigProviderInterface $subject, $output)
    {
        \EcommPro\CustomCurrency\Model\Config::enableHtml();

        $output['priceFormatHtml'] = $this->localeFormat->getPriceFormat(
            null,
            $this->checkoutSession->getQuote()->getQuoteCurrencyCode()
        );
        $output['basePriceFormatHtml'] = $this->localeFormat->getPriceFormat(
            null,
            $this->checkoutSession->getQuote()->getBaseCurrencyCode()
        );

        \EcommPro\CustomCurrency\Model\Config::disableHtml();
        return $output;
    }

}
