<?php
namespace EcommPro\CustomCurrency\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class PriceDecimalFixer
{
    public function execute(SchemaSetupInterface $setup) {
        $installer = $setup;

        $installer->startSetup();

        /* DEPRECATED, but we decided to keep it commented out */
        /*
        $priceFields = [
            'catalog_product_entity_decimal' => ['value'],
            'catalog_product_entity_tier_price' => ['value'],
            'catalog_product_option_price' => ['price'],
            'catalog_product_option_type_price' => ['price'],
            'catalog_product_index_eav_decimal' => ['value'],
            'catalog_product_index_price' => ['price', 'final_price', 'min_price', 'max_price', 'tier_price'],
            'catalog_product_index_tier_price' => ['min_price'],
            'catalog_product_index_price_cfg_opt_agr_idx' => ['price', 'tier_price'],
            'catalog_product_index_price_cfg_opt_agr_tmp' => ['price', 'tier_price'],
            'catalog_product_index_price_cfg_opt_idx' => ['min_price', 'max_price', 'tier_price'],
            'catalog_product_index_price_cfg_opt_tmp' => ['min_price', 'max_price', 'tier_price'],
            'catalog_product_index_price_final_idx' => ['orig_price', 'price', 'min_price', 'max_price', 'tier_price', 'base_tier'],
            'catalog_product_index_price_final_tmp' => ['orig_price', 'price', 'min_price', 'max_price', 'tier_price', 'base_tier'],
            'catalog_product_index_price_opt_idx' => ['min_price', 'max_price', 'tier_price'],
            'catalog_product_index_price_opt_tmp' => ['min_price', 'max_price', 'tier_price'],
            'catalog_product_index_price_opt_agr_idx' => ['min_price', 'max_price', 'tier_price'],
            'catalog_product_index_price_opt_agr_tmp' => ['min_price', 'max_price', 'tier_price'],
            'catalog_product_index_eav_decimal_idx' => ['value'],
            'catalog_product_index_eav_decimal_tmp' => ['value'],
            'catalog_product_index_price_idx' => ['price', 'final_price', 'min_price', 'max_price', 'tier_price'],
            'catalog_product_index_price_tmp' => ['price', 'final_price', 'min_price', 'max_price', 'tier_price'],

            'catalog_product_index_eav_decimal_replica' => ['value'],
            'catalog_product_index_price_replica' => ['price', 'final_price', 'min_price', 'max_price', 'tier_price'],
        ];
        */

        $connection = $installer->getConnection();

        $tables = $connection->getTables();
        foreach($tables as $table) {
            $columns = $connection->describeTable($table);
            foreach($columns as $columnName => $column) {
                if ($column['DATA_TYPE'] !== 'decimal') {
                    continue;
                }

                if ((int)($column['SCALE']) !== 4) {
                    continue;
                }

                $precision = $column['PRECISION'] + 4;

                $columnData = $connection->getColumnCreateByDescribe($column);
                $columnData['length'] = $precision . ',8';

                try {
                    $connection->modifyColumn(
                        $installer->getTable($table),
                        $columnName,
                        $columnData
                    );
                } catch(\PDOException $e) {
                    echo "[EE] $table::$columnName\n";
                }
            }
        }

        $installer->endSetup();
    }
}