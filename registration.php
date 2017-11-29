<?php
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'EcommPro_CustomCurrency',
    __DIR__
);

require_once(__DIR__ . '/override/CurrencyBundle.php');