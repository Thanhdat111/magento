define([
    'uiComponent',
    'ko',
    'jquery',
    'jquery/ui',
    'uiRegistry'
], function (Component, ko, $, ui,reg) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'Commercers_Workshop/chat/action.phtml'
        },
        initialize: function () {
            this._super();
            this.formProcessing();
        },
        formProcessing: function () {
            var idTask = window.location.href.split('/id/')[1];
            console.log(idTask);
            var url = window.location.href.split('/workshop')[0]+'/workshop/chat/message';
            this.customer_message = ko.observable('');
            this.sendMessageToCustomer = function(){
                var message =  this.customer_message;
                var file = $('#file_info').val();
                $.ajax(
                    url,
                    {
                        dataType: 'json',
                        type: 'post',
                        data : {idTask: idTask,file: file,message:message},
                        showLoader: true,
                        success : function(response){
                            if(!response.error){
                                var target =  reg.get('workshop_chat_2.workshop_chat_data_source');
                                if (target && typeof target === 'object') {
                                    target.set('params.t ', Date.now());
                                }
                            }
                        }
                    }
                );
            }.bind(this);

        }
    });
});