<?php
/**
 * InstallData
 *
 * @copyright Copyright © 2017 Ecomm.pro. All rights reserved.
 * @author    dev@ecomm.pro
 */

namespace EcommPro\CustomCurrency\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

use Magento\Framework\Setup\SampleData\Context as SampleDataContext;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
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
    public function __construct(
        CurrencySetupFactory $currencySetupFactory,
        \EcommPro\CustomCurrency\Model\CurrencyFactory $currencyFactory
    ) {
        $this->currencySetupFactory = $currencySetupFactory;
        $this->currencyFactory = $currencyFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
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
