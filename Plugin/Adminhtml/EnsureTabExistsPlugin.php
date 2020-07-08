<?php
namespace EcommPro\CustomCurrency\Plugin\Adminhtml;

use Magento\Framework\Config\Data;

class EnsureTabExistsPlugin
{
    public function afterGet(\Magento\Config\Model\Config\Structure\Data $subject, $result)
    {
        if (isset($result) && isset($result['sections']) && isset($result['tabs']) && !isset($result['tabs']['ecommpro'])) {
            return array_merge_recursive($result, [
                'tabs' => [
                    'ecommpro' => [
                        'id' => 'ecommpro',
                        'translate' => 'label',
                        'sortOrder' => 10,
                        'label' => 'EcommPro',
                        '_elementType' => 'tab',
                    ]
                ]
            ]);
        }

        return $result;
    }
}
