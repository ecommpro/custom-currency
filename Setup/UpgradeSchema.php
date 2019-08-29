<?php
namespace EcommPro\CustomCurrency\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $setup->startSetup();

        $tableName = CurrencySetup::ENTITY_TYPE_CODE . '_entity';

        $setup->getConnection()->addColumn($setup->getTable($tableName), 'format_precision', [
            'type' => Table::TYPE_INTEGER,
            'nullable' => true,
            'length' => '11',
            'comment' => 'Format Precision',
            'after' => 'precision'
        ]);

        $setup->endSetup();
    }
}