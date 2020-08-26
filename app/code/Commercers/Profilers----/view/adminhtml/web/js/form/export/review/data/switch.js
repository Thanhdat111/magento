define([
    'uiComponent',
    'ko',
    'jquery',
    'Commercers_Profilers/js/form/export/review/data/result'
    
], function (Component, ko, $ , exportResult) {
       return function(responseArray){
            
            var id = $('#field-available-id').val();
            
             $.ajax(
                    window.profilerConfig.action_get_product_attribute,
                    {
                        type: 'post',
                        data: {
                            profiler_id: responseArray.id,
                            id : id
                        }, 
                        showLoader: true,
                        success: function (response) {
                            exportResult(response);
                        }
                    });
       }
});