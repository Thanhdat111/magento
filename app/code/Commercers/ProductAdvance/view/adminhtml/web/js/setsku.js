define([
    'jquery',
    'uiComponent'
], function ( $,Component) {
    'use strict';
    return Component.extend({
        defaults: {
            //bodyTmpl: 'Commercers_ProductAdvance/adjust',
            template:       'ui/adjust',
            // fieldClass: {
            //     'sku': true
            // }
        }
    });

});