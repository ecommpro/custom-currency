<?php
namespace EcommPro\CustomCurrency\Model;

class Config
{
    static $enabledHtml = false;
    static $override = [];

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

    protected $cache = [];

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \EcommPro\CustomCurrency\Model\ResourceModel\Currency\CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\State $appState,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        $currencies = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->resourceConnection = $resourceConnection;
        $this->logger = $logger;
        $this->appState = $appState;
        $this->assetRepo = $assetRepo;
        $this->currencies = $currencies;
    }

    public static function enableHtml()
    {
        self::$enabledHtml = true;
    }

    public static function disableHtml()
    {
        self::$enabledHtml = false;
    }

    public function getAllowedCurrencies()
    {
        if (isset($this->cache['allowed'])) {
            return $this->cache['allowed'];
        }

        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName('ecommpro_currency_entity');

        if (!$connection->isTableExists($tableName)) {
            return [];
        }

        return $this->cache['allowed'] = $connection->fetchCol("SELECT code FROM $tableName");
    }

    public function getCurrency($code = null)
    {
        $currency = $this->_getCurrency($code);
        return $currency ? array_merge($currency, self::$override) : $currency;
    }

    public function _getCurrency($code = null)
    {
        $storeId = $this->storeManager->getStore()->getStoreId();
        if ($code === null) {
            $code = $this->storeManager->getStore()->getCurrentCurrency()->getCode();
        }
        $key = "currency:$code:$storeId";

        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $currencies = $this->getCurrencies();
        if (isset($currencies[$code])) {
            return $this->cache[$key] = $currencies[$code];
        }

        return $this->cache[$key] = false;
    }

    public function getCurrencies()
    {
        //echo $this->assetRepo->getUrl("EcommPro_CustomCurrency::image/star.svg");
        //exit;

        $storeId = $this->storeManager->getStore()->getStoreId();
        $key = 'currencies:' . $storeId;

        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $collection = $this->collectionFactory->create();
        $collection->setStoreId($storeId);
        $collection->addAttributeToFilter('status', 1);
        $collection->addAttributeToSelect('*');
        $collection->load();

        $currencies = [];

        foreach($collection as $item) {
            $data = $item->getData();

            if (!isset($data['name'])) {
                $data['name'] = $data['code'];
            }


            $data['singular'] = $data['name'];
            if (!empty($data['symbolimage'])) {
                $data['symbolimage_src'] = $item->getSymbolimageSrc();
            } else {
                $data['symbolimage_src'] = '';
            }

            $currency = $data;

            if (empty($currency['symbol'])) {
                $currency['symbol'] = $currency['code'];
            }

            if ('' === trim($currency['precision'])) {
                $currency['precision'] = 2;
            }

            if ('' === trim($currency['format_precision'])) {
                $currency['format_precision'] = $currency['precision'];
            }

            if (isset($currency['symbol_html']) && !empty($currency['symbol_html'])) {
                $symbolHtml = str_replace([
                    '{{symbol}}', '{{symbol_image}}', '{{image}}',
                ], [
                    $currency['symbol'], $currency['symbolimage_src'], $currency['symbolimage_src'],
                ], $currency['symbol_html']);
            } else {
                $symbolHtml = $currency['symbol'];
            }

            $currency['symbol_html_final'] = $symbolHtml;


            if (isset($currency['format']) && !empty($currency['format'])) {
                $format = str_replace([
                    '{{symbol}}', '{{symbol_image}}', '{{image}}', '{{symbol_html}}',
                ], [
                    $currency['symbol'], $currency['symbolimage_src'], $currency['symbolimage_src'], $symbolHtml,
                ], $currency['format']);

                $currency['format_final'] = $format;
            } else {
                $currency['format_final'] = '';
            }


            if (isset($currency['format_html']) && !empty($currency['format_html'])) {
                $formatHtml = str_replace([
                    '{{symbol}}', '{{symbol_image}}', '{{image}}', '{{symbol_html}}',
                ], [
                    $currency['symbol'], $currency['symbolimage_src'], $currency['symbolimage_src'], $symbolHtml,
                ], $currency['format_html']);

                $currency['format_html_final'] = $formatHtml;
            } else {
                $currency['format_html_final'] = $currency['format_final'];
            }

            $currency['symbol_html_final'] = $this->parse($currency['symbol_html_final']);
            $currency['format_html_final'] = $this->parse($currency['format_html_final']);

            $currencies[$item->getCode()] = $currency;
        }

        return $this->cache[$key] = $currencies;
    }

    public function getEnabledHTMLBlocks()
    {
        $key = 'htmlblocks';

        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $htmlblocks = $this->scopeConfig->getValue(
            'customcurrency/general/htmlblocks',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );

        $blocks = array_map(function($item) {
            return trim($item);
        }, preg_split("/[\n\r ]+/", trim($htmlblocks)));

        return $blocks;
    }

    public function parse($string)
    {
        $parsed = $string;
        $matches = [];
        preg_match_all('/{{(.*?)}}/mi', $string, $matches);
        foreach($matches[1] as $k => $match) {
            if (strpos($match, '::') === false) {
                continue;
            }
            $value = $this->assetRepo->getUrl($match);
            if ($value) {
                $parsed = str_replace($matches[0][$k], $value, $parsed);
            }
        }
        return $parsed;
    }

    public function getPatternHtml()
    {
        $currency = $this->getCurrency();
        return $currency['format_html_final'];
    }

    public function getPatternTxt()
    {
        $currency = $this->getCurrency();
        return $currency['format_final'];
    }

    public function getPattern()
    {
        if (self::$enabledHtml && $this->appState->getAreaCode() === \Magento\Framework\App\Area::AREA_FRONTEND) {
            return $this->getPatternHtml();
        } else {
            return $this->getPatternTxt();
        }
    }

    public static function beginOverride($settings)
    {
        self::$override = $settings;
    }

    public static function endOverride()
    {
        self::$override = [];
    }


}
