# Custom Currency for Magento 2

- No critical rewrites.
- Add as many currencies as you want.
- Preloaded with the general purpose currency *Point* and some of top cryptocurencies: *Bitcoin (BTC)*, *Ethereum (ETH)*, *Bitcoin Cash (BCH)*, *Ripple (XRP)*, *Bitcoin Gold (BTG)*, *DASH*, *Litecoin (LTC)*, *IOTA*, *Ethereum Classic (ETC)*, *Monero (XMR)*, *Cardano (ADA)*, *NEO*, *NEM*, *Stellar Lumen (XLM)*, *Qtum (QTUM)*, *Zcash (ZEC)*.

You can add your own currencies from the admin page or via dependency injection XML:

## Admin Page

Visit `Stores > Configuration > EcommPro > Custom Currency` and add the currencies you want in the text box, separated by blank lines, with the format:

```
{code}
{singular}
{plural}
```

Example:

```
XMPL
Examplium
Exampliums

DOGE
DogeCoin
DogeCoins
```

## Dependency Injection XML

If you want your currencies be available at install time, this should be the chosen method.

Add the currencies as the array of arrays argument of the `EcommPro\CustomCurrency\Model\Config` object constructor:

```xml
<type name="EcommPro\CustomCurrency\Model\Config">
    <arguments>
        <argument name="currencies" xsi:type="array">
            <item name="XMPL" xsi:type="array">
                <item name="code" xsi:type="string">XMPL</item>
                <item name="singular" xsi:type="string">Examplium</item>
                <item name="plural" xsi:type="string">Exampliums</item>
            </item>
            <item name="DOGE" xsi:type="array">
                <item name="code" xsi:type="string">DOGE</item>
                <item name="singular" xsi:type="string">DogeCoin</item>
                <item name="plural" xsi:type="string">DogeCoins</item>
            </item>
        </argument>
    </arguments>
</type>
```

## Internals

This module is based in these observations:

- Magento class loader doesn't load a class if it was previously loaded.
- Magento doesn't check the type of PHP ResourceBundle returned by `Magento\Framework\Locale\Bundle\CurrencyBundle` and simply uses it as an array, as it implements array access and iteration.
- In magento core files, `Magento\Framework\Locale\Bundle\CurrencyBundle` is always instantiated via constructor with `new`: `(new CurrencyBundle())`

```bash
grep -hr CurrencyBundle vendor/*
```

```txt
use Magento\Framework\Locale\Bundle\CurrencyBundle;
            $currencies = (new CurrencyBundle())->get($this->localeResolver->getLocale())['Currencies'];
use Magento\Framework\Locale\Bundle\CurrencyBundle as CurrencyBundle;
                        $allCurrencies = (new CurrencyBundle())->get(
class CurrencyBundle extends DataBundle
use Magento\Framework\Locale\Bundle\CurrencyBundle;
        $currencies = (new CurrencyBundle())->get($this->localeResolver->getLocale())['Currencies'] ?: [];
        $currencyBundle = new \Magento\Framework\Locale\Bundle\CurrencyBundle();
use Magento\Framework\Locale\Bundle\CurrencyBundle;
        $currencies = (new CurrencyBundle())->get(Resolver::DEFAULT_LOCALE)['Currencies'];
```

So, the key idea is to load our own version of `Magento\Framework\Locale\Bundle\CurrencyBundle` (a really simple class), override the `get` method and return a modified array imitating the original `ResourceBundle`.
