// https://github.com/magento/magento2/issues/7322

define([
    'jquery',
    'Magento_Catalog/js/price-utils',
    'mage/template'
], function ($, utils, mageTemplate) {
    'use strict';

    return function (priceBox) {        
        return $.widget('mage.priceBox', priceBox, {
            _init: function initPriceBox() {
                this.options.priceTemplate = '<span class="price"><%= data.formatted %></span>';
                this._super();
            },
            reloadPrice: function reDrawPrices() {
                var priceFormat = (this.options.priceConfig && (this.options.priceConfig.priceFormatHTML || this.options.priceConfig.priceFormat)) || {},
                    priceTemplate = mageTemplate(this.options.priceTemplate);

                _.each(this.cache.displayPrices, function (price, priceCode) {
                    price.final = _.reduce(price.adjustments, function (memo, amount) {
                        return memo + amount;
                    }, price.amount);

                    price.formatted = utils.formatPrice(price.final, priceFormat);

                    $('[data-price-type="' + priceCode + '"]', this.element).html(priceTemplate({
                        data: price
                    }));
                }, this);
            }
        });
    };
});