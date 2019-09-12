# Custom Currency for Magento 2

- No critical rewrites.
- Add as many currencies as you want.
- Customize default currencies like USD or EUR.
- Customize precision (decimal places).
- Preloaded with the general purpose currency *Point* and some of top cryptocurencies like *Bitcoin (BTC)* or *Ethereum (ETH)*.

## Installation

    composer require ecommpro/module-custom-currency

Execute the following commands

    php bin/magento module:enable EcommPro_CustomCurrency
    php bin/magento setup:upgrade

Access the Magento 2 administration panel and configure the module.

## User Guide

### Description

EcommPro Custom Currency allows you to create and edit new currencies directly from the Magento administrator. Manage the options of these currencies such as the precision of decimals, the symbol as image, emoji or text, position, etc. Advisable for cryptocurrency.

Regarding **currencies**, **Magento 2** has a lack.

The currency system is heavily dependent on the PHP intl [ICU](http://site.icu-project.org/) extension.

This is not bad. The intl extension is battle tested and powerful.

But it covers only official fiat currencies.

What about using custom currencies like *points*? Or what about the increasingly popular **crypto currencies** like Bitcoin (BTC), Tron (TRX), Ripple (XRP), among others?

At the moment there’s no a solution provided from the **Magento 2** core.

We’ve created a module to manage **custom currencies**, preloaded with some useful data like *points* currency and some of the most popular cryptos.

We’ve decided to publish it in GitHub as open source.

<a class="uri" href="https://github.com/ecommpro/custom-currency">https://github.com/ecommpro/custom-currency</a>

We hope it is useful for you.

### What can I do with EcommPro Custom Currency?

- Create as many coins as you need.
- Enables, disables or eliminates coins.
- Give them a code, a name in the singular and in the plural.
- Set the precision in decimals, ideal for cryptocurrency.
- Set the symbol position (before or after the price).
- Manage the currency symbol as text, emoji or image.

### Module Guide

You can access the module options easily from the side menu, `Stores > Currency > EcommPro Custom Currency`.

You can list and manage new currencies easily from a Magento Grid.

![Image 2](https://ecomm.pro/wp-content/uploads/2019/01/02.png)

Customize your new coins with the variety of possible options such as number of decimals, position, name or symbol.

![Image 3](https://ecomm.pro/wp-content/uploads/2019/01/03.png)

From the Magento coin configuration section, select your new coins.

![Image 4](https://ecomm.pro/wp-content/uploads/2019/01/04.png)

Go to a listing or product listing and you will see your new currency in action.

![Image 5](https://ecomm.pro/wp-content/uploads/2019/01/05.png)


You can add your own currencies from the admin page or via dependency injection XML:

### Admin Page

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

### Dependency Injection XML

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

This module is based on these observations:

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


## Help and information

If you need help or a specialized service you can receive support by writing an email from the following form:
https://ecomm.pro/en/contact-us/

