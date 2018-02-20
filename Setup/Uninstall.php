<?php

/**
 * Uninstall.php
 *
 * @copyright Copyright © 2017 Ecomm.pro. All rights reserved.
 * @author    dev@ecomm.pro
 */
namespace EcommPro\CustomCurrency\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class Uninstall implements UninstallInterface
{
    /**
     * @var array
     */
    protected $tablesToUninstall = [
        CurrencySetup::ENTITY_TYPE_CODE . '_entity',
        CurrencySetup::ENTITY_TYPE_CODE . '_eav_attribute',
        CurrencySetup::ENTITY_TYPE_CODE . '_entity_datetime',
        CurrencySetup::ENTITY_TYPE_CODE . '_entity_decimal',
        CurrencySetup::ENTITY_TYPE_CODE . '_entity_int',
        CurrencySetup::ENTITY_TYPE_CODE . '_entity_text',
        CurrencySetup::ENTITY_TYPE_CODE . '_entity_varchar'
    ];

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context) //@codingStandardsIgnoreLine
    {
        $setup->startSetup();

        foreach ($this->tablesToUninstall as $table) {
            if ($setup->tableExists($table)) {
                $setup->getConnection()->dropTable($setup->getTable($table));
            }
        }

        $setup->endSetup();
    }
}
