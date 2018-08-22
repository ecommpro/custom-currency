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

use Magento\Framework\Setup\SampleData\Context as SampleDataContext;

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
    public function __construct(
        CurrencySetupFactory $currencySetupFactory,
        \EcommPro\CustomCurrency\Model\CurrencyFactory $currencyFactory,
        SampleDataContext $sampleDataContext
    ) {
        $this->currencySetupFactory = $currencySetupFactory;
        $this->currencyFactory = $currencyFactory;
        $this->fixtureManager = $sampleDataContext->getFixtureManager();
        $this->csvReader = $sampleDataContext->getCsvReader();
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


        // Add sample currencies
        $fileName = $this->fixtureManager->getFixture('EcommPro_CustomCurrency::fixtures/currencies.csv');

        $rows = $this->csvReader->getData($fileName);
        $header = array_shift($rows);

        $currency = $this->currencyFactory->create();
        foreach ($rows as $row) {
            $data = [];
            foreach ($row as $key => $value) {
                $data[$header[$key]] = $value;
            }
            $row = $data;

            $currency->unsetData();
            $currency->setData($data);
            $currency->setStoreId(\Magento\Store\Model\Store::DEFAULT_STORE_ID);
            $currency->save();
        }

        $setup->endSetup();
    }
}
