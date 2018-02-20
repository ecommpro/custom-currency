<?php
namespace EcommPro\CustomCurrency\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function __construct(PriceDecimalFixer $priceDecimalFixer)
    {
        $this->priceDecimalFixer = $priceDecimalFixer;
    }

    public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
        $this->priceDecimalFixer->execute($setup, $context);
    }
}