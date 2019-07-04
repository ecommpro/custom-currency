<?php
namespace Magento\Framework\Locale\Bundle;

$autoloadFunctions = spl_autoload_functions();
foreach($autoloadFunctions as $f) {
    spl_autoload_unregister($f);
}
$classExists = class_exists('Magento\Framework\Locale\Bundle\CurrencyBundle');
foreach($autoloadFunctions as $f) {
    spl_autoload_register($f);
}

if (!$classExists) :
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

	   if (!isset($currency['plural'])) {
               $currency['plural'] = $currency['singular'];
	   }

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
