define([
    'uiComponent',
    'ko', 
    "jquery"
   


], function (Component, ko, $) {


    return Component.extend({
        initialize: function () {
            this._super();
            
            $('.expand-button').on('click',function(){
                if($('.nested').hasClass('active')){
                    $('.nested').removeClass('active');
                }else{
                    $('.nested').addClass('active'); 
                }
               
            });
            //tree ul
            /*
            var toggler = document.getElementsByClassName("attr-name");
            var i;

            for (i = 0; i < toggler.length; i++) {
                toggler[i].addEventListener("click", function() {
                this.parentElement.querySelector(".nested").classList.toggle("active");
                this.classList.toggle("attr-name-down");
              });
            }
            */
           console.log($(".attr-name"));
           $(".attr-name").click(function(){
               console.log($(this).parent());
               console.log($(this).next('.nested'));
               
               $(this).next('.nested').toggle('active');
           });

        }

    });
});

