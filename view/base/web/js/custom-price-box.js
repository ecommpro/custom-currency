// https://github.com/magento/magento2/issues/7322

define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';

    return function (priceBox) {        
        return $.widget('mage.priceBox', priceBox, {
            _init: function initPriceBox() {
                this.options.priceTemplate = '<span class="price"><%= data.formatted %></span>';
                this._super();
            }
        });
    };
});