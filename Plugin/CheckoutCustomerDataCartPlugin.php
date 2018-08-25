<?php
namespace EcommPro\CustomCurrency\Plugin;

class CheckoutCustomerDataCartPlugin extends \Magento\Tax\Plugin\Checkout\CustomerData\Cart
{
    public function afterGetSectionData(\Magento\Checkout\CustomerData\Cart $subject, $result)
    {
        \EcommPro\CustomCurrency\Model\Config::enableHtml();

        $result['subtotal_incl_tax'] = $this->checkoutHelper->formatPrice($this->getSubtotalInclTax());
        $result['subtotal_excl_tax'] = $this->checkoutHelper->formatPrice($this->getSubtotalExclTax());
        $items =$this->getQuote()->getAllVisibleItems();
        if (is_array($result['items'])) {
            foreach ($result['items'] as $key => $itemAsArray) {
                if ($item = $this->findItemById($itemAsArray['item_id'], $items)) {
                    $this->itemPriceRenderer->setItem($item);
                    $this->itemPriceRenderer->setTemplate('checkout/cart/item/price/sidebar.phtml');
                    $result['items'][$key]['product_price']=$this->itemPriceRenderer->toHtml();
                }
            }
        }

        $totals = $this->getQuote()->getTotals();
        $subtotalAmount = $totals['subtotal']->getValue();

        $result['subtotal'] = isset($totals['subtotal'])
            ? $this->checkoutHelper->formatPrice($subtotalAmount)
            : 0;

        \EcommPro\CustomCurrency\Model\Config::disableHtml();
        return $result;
    }

}
