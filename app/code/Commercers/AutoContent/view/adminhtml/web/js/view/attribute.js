define([
    'uiComponent',
    'ko',
    'jquery',
    'jquery/ui',
    'Magento_Ui/js/modal/modal'
], function (Component, ko, $, ui, modal) {
    'use strict';
           var options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                title: 'Attribute Infomation',
                buttons: [{
                    text: $.mage.__('Close'),
                    class: 'action- scalable primary',
                    click: function () {
                        this.closeModal();
                    }
                }]
            };
            var popup = modal(options, $('.info-attribute'));
            $("#attribute-popup").on('click',function(){ 
                $(".info-attribute").modal("openModal");
            });       
    //we'll make our game a Component to allow someone to easily modify it in the future
    return Component.extend({
        initialize: function () {
            //next line is basically equivalent of parent::__construct() in php 
            this._super();
            //let's divide our component to 2 separate elements
            //one responsible for populating our game's interface
            this.populateUi();
            //console.log(this.params.storeId);
        },
        keypressInput: function(){
            $('.tester-see').on('keypress',function(event){
                 var keycode = (event.keyCode ? event.keyCode : event.which);
                 if(keycode == '13'){
                       return false;
                 }
             });
           
        },
        populateUi: function () {
            this.storeId = ko.observable(this.params.storeId);
            this.ruleId = ko.observable(this.params.ruleId);
            var that = this;
            this.attributes = window.productAttribute;
            var productAttributeExpression = window.productAttributeExpression;
            this.attributesExpression = productAttributeExpression[that.storeId()];
            this.sku_test = '';
            var check = 0;
            if( productAttributeExpression[that.storeId()]){
                check = this.attributesExpression.length;
            }
            var count = 0;
            var setAttribute = function (initializeAttribute, initializeAttributeExpression) {
                this.attribute = ko.observable(initializeAttribute);
                this.expression = '';
                this.id = '';
                //this.sku_test =  '';
                this.store_id = that.storeId();
                this.use_default = ko.observable(false);
                if (check > 0) {
                    this.expression = initializeAttributeExpression.expression;
                    var use_default = initializeAttributeExpression.use_default;
                    this.id = initializeAttributeExpression.id;
                    if(use_default == 1)
                    this.use_default = ko.observable(true);
                }
            }.bind(this);
            var setAttributeNew = function (initializeAttribute) {
                this.attribute = ko.observable(initializeAttribute);
                this.expression = '';
//                this.sku_test =  '';
                this.store_id = that.storeId();
                this.use_default = ko.observable(false);
            }.bind(this);
            this.attributeCollection = ko.observableArray([]);
            if(that.ruleId() != 0){
                count = check;
                for(var i = 0; i < check ;i++){
                    if(that.ruleId() == this.attributesExpression[i].rule_id){
                        for(var j=0;j < this.attributes.length;j++){
                            if( this.attributesExpression[i].attribute_code === this.attributes[j].attribute_code)
                            this.attributeCollection.push(new setAttribute(this.attributes[j], this.attributesExpression[i]));
                        }
                    }
                }
            } 

            this.addAttribute = function () {
                if (count < check) {
                        for(var j=0;j < this.attributes.length;j++){
                            if( this.attributesExpression[count].attribute_code === this.attributes[j].attribute_code)
                            this.attributeCollection.push(new setAttribute(this.attributes[j], this.attributesExpression[count]));
                        }
                }
                else{
                    this.attributeCollection.push(new setAttributeNew(this.attributes[count]));
                }
                    count++;
            }
            this.tester = function () {
                this.lastSavedJson(JSON.stringify(ko.toJS(this.attributeCollection), null, 2));
                var data = this.lastSavedJson;
                $.ajax(
                        window.attributeConfig.get_url_tester,
                        {
                            type: "POST",
                            dataType: "json",
                            data: {data: data,sku:this.sku_test},
                            showLoader: true,
                            success: function (response) {
                                if(response.block != null){
                                    var popup = $('<div class="popup"/>').html(response.block).modal({
                                    modalClass: 'changelog',
                                    buttons: [{
                                        text: 'Close',
                                        click: function () {
                                            this.closeModal();
                                        }
                                    }]
                                });
                                popup.modal('openModal');
                                }
                                if(response.message  != null)
                                alert(response.message)
                            }
                        });
            }.bind(this);

            this.removeAttribute = function (attribute) {
                this.attributeCollection.remove(attribute);
            };
            this.lastSavedJson = ko.observable("");
            this.save = function () {
                this.lastSavedJson(JSON.stringify(ko.toJS(this.attributeCollection), null, 2));
            };

            this.saveAttribute = function () {
                this.lastSavedJson(JSON.stringify(ko.toJS(this.attributeCollection), null, 2));
                var data = this.lastSavedJson;
                $.ajax(
                        window.attributeConfig.get_product_attribute,
                        {
                            type: "POST",
                            dataType: "json",
                            data: {data: data,ruleId: that.ruleId(),storeId: that.storeId()},
                            showLoader: true,
                            success: function (response) {
                                alert('attribute saved');
                            }
                        });
            };
        }
    });

});