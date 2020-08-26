define([
    'jquery',
    'Magento_Ui/js/form/element/select'
], function ($, Select) {
    'use strict';

    return Select.extend({
        defaults: {
            customName: '${ $.parentName }.${ $.index }_input'
        },
        /**
         * Change currently selected option
         *
         * @param {String} id
         */
        selectOption: function(id){
            if(($("#"+id).val() == 0)||($("#"+id).val() == undefined)) {
                $('div[data-index="hostname"]').hide();
                $('div[data-index="username"]').hide();
                $('div[data-index="password"]').hide();
                $('div[data-index="type"]').hide();
                $('div[data-index="port"]').hide();
                $('div[data-index="folderftp"]').hide();
                $('div[data-index="donefolderftp"]').hide();
                $('div[data-index="localfolderftp"]').hide();
            } else if($("#"+id).val() == 1) {
                $('div[data-index="hostname"]').show();
                $('div[data-index="username"]').show();
                $('div[data-index="password"]').show();
                $('div[data-index="type"]').show();
                $('div[data-index="port"]').show();
                $('div[data-index="folderftp"]').show();
                $('div[data-index="donefolderftp"]').show();
                $('div[data-index="localfolderftp"]').show();
            }
        },
    });
});