<?php
namespace EcommPro\CustomCurrency\Plugin;

use Magento\Catalog\Api\Data\ProductRender\FormattedPriceInfoInterfaceFactory;
use Magento\Catalog\Api\Data\ProductRender\PriceInfoInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class ProductRenderFormattedPriceInfoBuilderPlugin
{
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        FormattedPriceInfoInterfaceFactory $formattedPriceInfoFactory
    ) {
        $this->priceCurrency = $priceCurrency;
        $this->formattedPriceInfoFactory = $formattedPriceInfoFactory;
    }

    public function aroundBuild(\Magento\Catalog\Model\ProductRender\FormattedPriceInfoBuilder $subject, callable $proceed, PriceInfoInterface $priceInfo, $storeId, $currencyCode)
    {
        $formattedPriceInfo = $this->formattedPriceInfoFactory->create();

        foreach ($priceInfo->getData() as $key => $value) {
            if (is_numeric($value)) {
                $formattedValue = $this->priceCurrency
                    ->format(
                        $value,
                        true,
                        PriceCurrencyInterface::DEFAULT_PRECISION,
                        $storeId,
                        $currencyCode
                    );
                $formattedPriceInfo->setData($key, $formattedValue);
            }
        }
        $priceInfo->setData('formatted_prices_txt', $formattedPriceInfo);

        $proceed($priceInfo, $storeId, $currencyCode);
    }
}