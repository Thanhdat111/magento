define([
    'uiComponent',
    'ko',
    'jquery'
], function (Component, ko, $ ) {
    
       return function(responseAttr){
            $('.fields-available').html(responseAttr);
            //expand all
            $('.expand-button').on('click',function(){
                if($('.nested').hasClass('active')){
                    $('.nested').removeClass('active');
                }else{
                    $('.nested').addClass('active'); 
                }
               
            });
            //tree ul
            var toggler = document.getElementsByClassName("attr-name");
            var i;

            for (i = 0; i < toggler.length; i++) {
              toggler[i].addEventListener("click", function() {
                this.parentElement.querySelector(".nested").classList.toggle("active");
                this.classList.toggle("attr-name-down");
              });
            }
       }
       
});