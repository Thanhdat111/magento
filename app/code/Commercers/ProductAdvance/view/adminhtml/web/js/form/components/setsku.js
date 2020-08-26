/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'underscore',
    'Magento_Ui/js/form/element/abstract',
    'jquery',
    'mage/translate',
    'Magento_Ui/js/modal/alert',
    'uiRegistry',
    'ko'
], function (_, Abstract, $, $t, alert, uiRegistry,ko) {
    'use strict';

    return Abstract.extend({
        defaults: {
            elementTmpl: 'Commercers_ProductAdvance/ui/form/element/setsku'
        },
        setInitialValue: function () {
            this._super();
            return this;
        },
        initObservable: function () {
            this._super();
            return this;
        },
        //labelSku: "Set New Sku: " +$("input[name='product[sku]']").val(),
        labelSku: ko.pureComputed(function () {

            var old_sku = $("input[name='product[sku]']").val();

            var new_sku= old_sku+"_New"
            return "Set New Sku:" + new_sku;
        }),
        setSku: function () {
            let new_sku = $("#new_sku").text().split(':')[1];
            var uiSku = uiRegistry.get(this.name);
            $("input[name='product[sku]']").val(new_sku);
            console.log( $("input[name='product[sku]']").val());
            $("input[name='product[sku]']").value=new_sku;
            uiSku.value(new_sku);
            // let href =  window.location.href.split('/');
            // let product_id = 0;
            // for (var i = 0; i <= href.length; i++) {
            //     if(href[i] === 'id') {
            //         product_id = href[i + 1];
            //         break;
            //     }
            // }
            // $.ajax({
            //     url : this.filterUrl + '?isAjax=true',
            //     data: {
            //         product_id: product_id,
            //         sku: new_sku,
            //     },
            //     type : 'post',
            //     dataType : 'json',
            //     showLoader: true,
            //     success: function (response) {
            //         uiSku.value(response);
            //         console.log(response)
            //     }
            // });
        }

    });
});
