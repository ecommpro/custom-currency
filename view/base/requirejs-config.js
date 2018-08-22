var config = {
    map: {
        '*': {
            'Magento_Catalog/js/price-utils':'EcommPro_CustomCurrency/js/price-utils'
        }
    },
    config: {
        mixins: {
            'Magento_Catalog/js/price-box': {
                'EcommPro_CustomCurrency/js/custom-price-box': true
            }
        }
    }
};