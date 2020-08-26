define([
    'uiComponent',
    'ko',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/modal/confirm',
    "jquery",
    "mage/calendar",
    'Commercers_Profilers/js/form/export/review/data/switch',
    'mage/validation'


], function (Component, ko, alert, confirmation, $, calendar, exportSwitch) {


    return Component.extend({
        initialize: function () {
            this._super();

        },
        showAvailableButton: function () { 

            
            var pathname = new URL(window.location.href).pathname;
            var profiler_id = pathname.split("profilers_id=")[1];
            $.ajax(
                window.profilerConfig.action_get_profiler_data,
                {
                    type: 'post',
                    data: {profiler_id: profiler_id },
                    showLoader: false,
                    success: function (response) {
                        exportSwitch(response);
                }
            });
            
        }

    });
});

