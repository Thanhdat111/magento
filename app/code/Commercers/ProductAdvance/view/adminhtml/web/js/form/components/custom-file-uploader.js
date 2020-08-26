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

    return Element.extend({
        defaults: {
            elementTmpl: 'Commercers_ProductAdvance/ui/form/element/logo'
        },
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

        /**
         * Handler of the file upload complete event.
         *
         * @param {Event} e
         * @param {Object} data
         */
        // onFileUploaded: function (e, data) {
        //     this._super(e, data);
        //     var response = data.result;
        //     var fileUrl = response.url;
        //     var fileData = new Array();
        //
        //     fileData.push(fileUrl);
        //     console.log(fileData);
        //     document.getElementById("file_info").value = fileData;
        // }
    });
});
