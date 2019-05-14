<?php
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'EcommPro_CustomCurrency',
    isset($file) ? realpath(dirname($file)) : __DIR__
);

require_once(__DIR__ . '/override/CurrencyBundle.php');