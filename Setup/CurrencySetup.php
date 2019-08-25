<?php
/**
 * CurrencySetup
 *
 * @copyright Copyright © 2017 Ecomm.pro. All rights reserved.
 * @author    dev@ecomm.pro
 */

namespace EcommPro\CustomCurrency\Setup;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;

/**
 * @codeCoverageIgnore
 */
class CurrencySetup extends EavSetup
{
    /**
     * Entity type for Currency EAV attributes
     */
    const ENTITY_TYPE_CODE = 'ecommpro_currency';

    /**
     * Retrieve Entity Attributes
     *
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function getAttributes()
    {
        $attributes = [];

        $attributes['code'] = [
            'type' => 'static',
            'label' => 'Code',
            'input' => 'text',
            'required' => true,
            'sort_order' => 10,
            'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
            'group' => 'General',
            'validate_rules' => 'a:2:{s:15:"max_text_length";i:100;s:15:"min_text_length";i:1;}'
        ];

        $attributes['status'] = [
            'type' => 'int',
            'label' => 'Status',
            'input' => 'smallint',
            'required' => true,
            'sort_order' => 10,
            'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
            'group' => 'General',
            'default' => 1,
        ];

        $attributes['precision'] = [
            'type' => 'static',
            'label' => 'Precision',
            'input' => 'text',
            'required' => true,
            'sort_order' => 10,
            'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
            'group' => 'General',
        ];

        $attributes['format_precision'] = [
            'type' => 'static',
            'label' => 'Format Precision',
            'input' => 'text',
            'required' => false,
            'sort_order' => 10,
            'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
            'group' => 'General',
        ];

        $attributes['name'] = [
            'type' => 'varchar',
            'label' => 'Singular Name',
            'input' => 'text',
            'required' => false,
            'sort_order' => 10,
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'General',
        ];

        $attributes['plural'] = [
            'type' => 'varchar',
            'label' => 'Plural Name',
            'input' => 'text',
            'required' => false,
            'sort_order' => 10,
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'General',
        ];

        $attributes['format'] = [
            'type' => 'varchar',
            'label' => 'Format',
            'input' => 'text',
            'required' => false,
            'sort_order' => 10,
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'General',
        ];

        $attributes['format_html'] = [
            'type' => 'varchar',
            'label' => 'Format (HTML)',
            'input' => 'text',
            'required' => false,
            'sort_order' => 11,
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'General',
        ];

        $attributes['symbol'] = [
            'type' => 'varchar',
            'label' => 'Format',
            'input' => 'text',
            'required' => false,
            'sort_order' => 12,
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'General',
        ];

        $attributes['symbol_html'] = [
            'type' => 'varchar',
            'label' => 'Format',
            'input' => 'text',
            'required' => false,
            'sort_order' => 14,
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'General',
        ];

        $attributes['symbolimage'] = [
            'type' => 'varchar',
            'label' => 'Symbol Image',
            'input' => 'image',
            'backend' => 'EcommPro\CustomCurrency\Model\Currency\Attribute\Backend\Symbolimage',
            'required' => false,
            'sort_order' => 99,
            'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
            'group' => 'General',
        ];

        return $attributes;
    }

    /**
     * Retrieve default entities: currency
     *
     * @return array
     */
    public function getDefaultEntities()
    {
        $entities = [
            self::ENTITY_TYPE_CODE => [
                'entity_model' => 'EcommPro\CustomCurrency\Model\ResourceModel\Currency',
                'attribute_model' => 'EcommPro\CustomCurrency\Model\ResourceModel\Eav\Attribute',
                'table' => self::ENTITY_TYPE_CODE . '_entity',
                'increment_model' => null,
                'additional_attribute_table' => self::ENTITY_TYPE_CODE . '_eav_attribute',
                'entity_attribute_collection' => 'EcommPro\CustomCurrency\Model\ResourceModel\Attribute\Collection',
                'attributes' => $this->getAttributes()
            ]
        ];

        return $entities;
    }
}
