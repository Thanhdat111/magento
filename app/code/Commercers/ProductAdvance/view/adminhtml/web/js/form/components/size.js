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
            elementTmpl: 'Commercers_ProductAdvance/ui/form/element/size'
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

        getSize: function () {
            let href =  window.location.href;
            let product_id = window.location.href.split('/')[8];
            console.log(product_id);
            var uiSize = uiRegistry.get(this.name);

            $.ajax({
                url : this.filterUrl,
                data: {
                    referrer_id: "globals_admin",
                    product_id: product_id
                },
                type : 'post',
                dataType : 'json',
                showLoader: true,
                success: function (response) {
                    if (response.success) {
                        console.log(response.size);
                        uiSize.value(response.size);
                    } else {
                        alert({
                            content: $t('Can not set Size'),
                        });
                    }

                }
            });
        }
    });
});
