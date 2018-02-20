<?php
/**
 * InstallData
 *
 * @copyright Copyright © 2017 Ecomm.pro. All rights reserved.
 * @author    dev@ecomm.pro
 */

namespace EcommPro\CustomCurrency\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * Currency setup factory
     *
     * @var CurrencySetupFactory
     */
    protected $currencySetupFactory;

    /**
     * Init
     *
     * @param CurrencySetupFactory $currencySetupFactory
     */
    public function __construct(CurrencySetupFactory $currencySetupFactory)
    {
        $this->currencySetupFactory = $currencySetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context) //@codingStandardsIgnoreLine
    {
        /** @var CurrencySetup $currencySetup */
        $currencySetup = $this->currencySetupFactory->create(['setup' => $setup]);

        $setup->startSetup();

        $currencySetup->installEntities();
        $entities = $currencySetup->getDefaultEntities();
        foreach ($entities as $entityName => $entity) {
            $currencySetup->addEntityType($entityName, $entity);
        }

        $setup->endSetup();
    }
}
