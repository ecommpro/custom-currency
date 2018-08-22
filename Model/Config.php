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

    protected $cache = [];

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \EcommPro\CustomCurrency\Model\ResourceModel\Currency\CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Psr\Log\LoggerInterface $logger,
        $currencies = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->resourceConnection = $resourceConnection;
        $this->logger = $logger;
        $this->currencies = $currencies;
    }

    public function getAllowedCurrencies()
    {
        if (isset($this->cache['allowed'])) {
            return $this->cache['allowed'];
        }

        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName('ecommpro_currency_entity');
        return $this->cache['allowed'] = $connection->fetchCol("SELECT code FROM $tableName");
    }

    public function getCurrency($code = null)
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
            $data['singular'] = $data['name'];
            if (!empty($data['symbolimage'])) {
                $data['symbolimage_src'] = $item->getSymbolimageSrc();
            } else {
                $data['symbolimage_src'] = '';
            }
            
            $currencies[$item->getCode()] = $data;
        }

        return $this->cache[$key] = $currencies;
    }

    public function getPrecision()
    {
        return 2;
    }
}