<?php
namespace EcommPro\CustomCurrency\Model;

class Config
{
    protected $_additionalCurrencies = [
        [ 'code' => 'POINT', 'singular' => 'Point', 'plural' => 'Points' ],        
        [ 'code' => 'BTC', 'singular' => 'Bitcoin', 'plural' => 'Bitcoins' ],
        [ 'code' => 'sat', 'singular' => 'Satoshi', 'plural' => 'Satoshis' ],
        [ 'code' => 'ETH', 'singular' => 'Ethereum', 'plural' => 'Ethereums' ],
        [ 'code' => 'BCH', 'singular' => 'Bitcoin Cash', 'plural' => 'Bitcoins Cash' ],
        [ 'code' => 'XRP', 'singular' => 'Ripple', 'plural' => 'Ripples' ],
        [ 'code' => 'BTG', 'singular' => 'Bitcoin Gold', 'plural' => 'Bitcoins Gold' ],
        [ 'code' => 'DASH', 'singular' => 'DASH', 'plural' => 'DASH' ],
        [ 'code' => 'LTC', 'singular' => 'Litecoin', 'plural' => 'Litecoins' ],
        [ 'code' => 'IOTA', 'singular' => 'IOTA', 'plural' => 'IOTAs' ],
        [ 'code' => 'ETC', 'singular' => 'Ethereum Classic', 'plural' => 'Ethereums Classic' ],
        [ 'code' => 'XMR', 'singular' => 'Monero', 'plural' => 'Moneros' ],
        [ 'code' => 'ADA', 'singular' => 'Cardano', 'plural' => 'Cardanos' ],
        [ 'code' => 'NEO', 'singular' => 'NEO', 'plural' => 'NEOs' ],
        [ 'code' => 'NEM', 'singular' => 'NEM', 'plural' => 'NEMs' ],
        [ 'code' => 'XLM', 'singular' => 'Stellar Lumen', 'plural' => 'Stellar Lumens' ],
        [ 'code' => 'QTUM', 'singular' => 'Qtum', 'plural' => 'Qtum' ],
        [ 'code' => 'ZEC', 'singular' => 'Zcash', 'plural' => 'Zcash' ],
        [ 'code' => 'TRX', 'singular' => 'Tron', 'plural' => 'Tron' ],
        [ 'code' => 'LIFE', 'singular' => 'Life', 'plural' => 'Lifes' ],
        [ 'code' => 'XLC', 'singular' => 'Leviar', 'plural' => 'Leviars' ],
    ];

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger,
        $currencies = []
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->currencies = $currencies;
    }
    
    public function getCurrencies()
    {
      	$result = $this->_additionalCurrencies;

      	$string = "";
      	try {
	        $string = $this->scopeConfig->getValue('customcurrency/general/currencies');
      	} catch(\Zend_Db_Statement_Exception $e) {
	        //$this->logger->critical($e);
      	}

		$string = preg_replace('~(*BSR_ANYCRLF)\R~', "\n", $string);
		$string = trim($string, "\n");
		$string = preg_replace('#^\h*#mi', "", $string);
		$string = preg_replace('#\h*$#mi', "", $string);

      	$groups = preg_split("/\h*[\n]{2,}$/mi", $string);

		foreach($groups as $group) {        
			$lines = preg_split("/\n/mi", trim($group, "\n"));
			$code = array_shift($lines);        
			$singular = count($lines) ? array_shift($lines) : $code;
			$plural = count($lines) ? array_shift($lines) : $code;

			$result[$code] = [
				"code" => $code,
				"singular" => $singular,
				"plural" => $plural
			];
		}
		return array_merge($this->currencies, $result);
    }

    public function getPrecision()
    {
        return 8;
    }
}