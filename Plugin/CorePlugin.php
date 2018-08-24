<?php
namespace EcommPro\CustomCurrency\Plugin;

class CorePlugin
{ 
    public function afterGetResult(\Magento\Backend\Model\Menu\Builder $subject,
        $result
    ) {
        return $result;
    }
}
