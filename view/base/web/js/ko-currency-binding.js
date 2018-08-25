define([
    "jquery",
    "ko",
    "uiComponent"
], function($, ko, Component) {
    "use strict";

    ko.bindingHandlers.currency = {
       init: function(element, valueAccessor) {
           element.innerHTML = valueAccessor();
       }
    };
    
});