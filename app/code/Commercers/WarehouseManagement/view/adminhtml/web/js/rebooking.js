define([
    'uiComponent',
    'ko',
    'jquery',
    'jquery/ui',
], function (Component, ko, $, ui) {
    'use strict';
    return Component.extend({
        
        initialize: function () {
            this._super();
            this.formProcessing();
        },
        formProcessing: function () {
            $('.button-save').hide();
            var self = this;
            self.skuProduct =  ko.observable('ComVN_Test_01');
            
            self.loadWarehouse = function () {
                $.ajax(
                  window.urlAjax.url_load_warehouse,
                    {
                      type: "POST",
                      dataType: "json",
                      data: {sku: self.skuProduct()},
                      showLoader: true,
                      success: function (response) {
                        if(response.template){
                            $('.load-warehouse').html(response.template);
                        }
                        if(response.error){
                            alert(response.error);
                            $('.button-save').hide()
                            $('.load-warehouse').html('');
                        }
                      }
                });
            }
        }
    });
});