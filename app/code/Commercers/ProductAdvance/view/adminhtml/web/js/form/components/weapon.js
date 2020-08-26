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
    'uiRegistry'
], function (_, Abstract, $, $t, alert, uiRegistry) {
    'use strict';

    return Abstract.extend({
        defaults: {
            elementTmpl: 'Commercers_ProductAdvance/ui/form/element/weapon'
        },
        /**
         * {@inheritdoc}
         */
        setInitialValue: function () {
            this._super();

            return this;
        },

        /**
         * {@inheritdoc}
         */
        initObservable: function () {
            this._super();

            return this;
        },
        getWeapon1 : function(){
            var uiWeapon = uiRegistry.get(this.name);
            var weaponName = 'FSK 14';
            uiWeapon.value(weaponName);
        },
        getWeapon2 : function(){
            var uiWeapon = uiRegistry.get(this.name);
            var weaponName = 'FSK 18';
            uiWeapon.value(weaponName);
        },
        getWeapon3 : function(){
            var uiWeapon = uiRegistry.get(this.name);
            var weaponName = 'W-Teile intern';
            uiWeapon.value(weaponName);
        },
        getWeapon4 : function(){
            var uiWeapon = uiRegistry.get(this.name);
            var weaponName = 'Anbauteile extern';
            uiWeapon.value(weaponName);
        },
        getWeapon5 : function(){
            var uiWeapon = uiRegistry.get(this.name);
            var weaponName = 'nein';
            uiWeapon.value(weaponName);
        },
        getWeapon6 : function(){
            var uiWeapon = uiRegistry.get(this.name);
            var weaponName = 'Munition';
            uiWeapon.value(weaponName);
        },
        getWeapon: function (data, event) {
            let href =  window.location.href;
            let product_id = window.location.href.split('/')[8];
            var uiWeapon = uiRegistry.get(this.name);
            var button_id = console.log(event.target.id);
            console.log(button_id);
            var weaponName = "";
            switch (event.target.id) {
                case "btn_1":
                    weaponName = 'FSK 14';
                    console.log(1);
                    break;
                case "btn_2":
                    weaponName = 'FSK 18';
                    console.log(2);
                    break;
                case "btn_3":
                    weaponName = 'W-Teile intern';
                    console.log(3);
                    break;
                case "btn_4":
                    weaponName = 'Anbauteile extern';
                    console.log(4);
                    break;
                case "btn_5":
                    weaponName = 'nein';
                    console.log(5);
                    break;
                case "btn_6":
                    weaponName = 'Munition';
                    console.log(6);
                    break;
                default:
                    weaponName = 'thanhdat_test';
                    break;
            }
            console.log(weaponName);
            uiWeapon.value(weaponName);
            // $.ajax({
            //     url : this.filterUrl,
            //     data: {
            //         referrer_id: "globals_admin",
            //         product_id: product_id
            //     },
            //     type : 'post',
            //     dataType : 'json',
            //     showLoader: true,
            //     success: function (response) {
            //         if (response.success) {
            //             uiWeapon.value(response.weapon);
            //         } else {
            //             alert({
            //                 content: $t('Can not set Weapon'),
            //             });
            //         }
            //
            //     }
            // });
        }
    });
});
