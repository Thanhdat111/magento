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
], function (_, Abstract, $, $t, alert, uiRegistry, ko) {
    'use strict';

    return Abstract.extend({
        defaults: {
            elementTmpl: 'Commercers_ProductAdvance/ui/form/element/logo'
        },
        /**
         * {@inheritdoc}
         */
        setInitialValue: function () {
            this._super();

            return this;
        },
        file: ko.observable(),

        /**
         * {@inheritdoc}
         */
        initObservable: function () {
            this._super();
            return this;
        },
        fileSelect: function (elemet, event) {
            var files = event.target.files;// FileList object
            var image = event.target.files[0];
            console.log(image);
            this.file = image;
            // Loop through the FileList and render image files as thumbnails.
            for (var i = 0, f; f = files[i]; i++) {

                // Only process image files.
                if (!f.type.match('image.*')) {
                    continue;
                }

                var reader = new FileReader();

                // Closure to capture the file information.
                reader.onload = (function (theFile) {
                    return function (e) {
                        //     self.files.push(new FileModel(escape(theFile.name),e.target.result));
                    };
                })(f);
                // Read in the image file as a data URL.
                reader.readAsDataURL(f);
            }
        },
        update: function (element, valueAccessor, allBindingsAccessor) {
            console.log(element);
            var allBindings = allBindingsAccessor()
            if (!!FileReader && valueAccessor() && element.files.length) {
                var reader = new FileReader();
                reader.onload = function (event) {
                    var dataUri = event.target.result
                    allBindings.imagePreview(dataUri)
                }
                reader.onerror = function (e) {
                    console.log("error", stuff)
                }
                reader.readAsDataURL(element.files[0])
            }
        },
        upload: function () {
            var uiImage = uiRegistry.get(this.name);
            console.log(this.file);
            var formData = new FormData();
            formData.append("img", this.file);
            $.ajax({
                url: this.filterUrl,
                type: 'post',
                // data: {
                //     image : formData
                // },
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                showLoader: true,
                success: function (response) {
                    if (response.success) {
                        console.log(response.size);
                        uiImage.value(response.size);
                    } else {
                        alert({
                            content: $t('Can not set Size'),
                        });
                    }
                }
            })
        },
    });
})
;