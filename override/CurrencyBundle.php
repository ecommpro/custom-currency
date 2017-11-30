<?php
namespace Magento\Framework\Locale\Bundle;

if (!class_exists('\Magento\Framework\Locale\Bundle\CurrencyBundle')) :
class CurrencyBundle extends DataBundle
{
    protected $path = 'ICUDATA-curr';

    public function toArray($bundle) {
        $aux = [];
        foreach($bundle as $k => $v) {
            $aux[$k] = is_object($v) ? $this->toArray($v) : $v;
        }
        return $aux;
    }
 
    public function get($locale)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $config = $objectManager->get('\EcommPro\CustomCurrency\Model\Config');
        $currencies = $config->getCurrencies();
        
        $bundle = parent::get($locale);
        $bundleAsArray = $this->toArray($bundle);

        foreach($currencies as $currency) {
            $bundleAsArray['Currencies'][$currency['code']] = [
                $currency['code'],
                $currency['singular'],
            ];
            
            $bundleAsArray['CurrencyPlurals'][$currency['code']] = [
                'one' => $currency['singular'],
                'other' => $currency['plural'],
            ];
        }

        return $bundleAsArray;
    }
}
endif;