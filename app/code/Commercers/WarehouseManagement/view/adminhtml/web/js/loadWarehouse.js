require(['jquery', 'jquery/ui'], function($){ 
        $(document).keypress(
            function(event){
              if (event.which == '13') {
                event.preventDefault();
              }
          });
        $('.checkbox-select').on('click', function (event) {
           var inputQuantity = $(this).parent().next().find('input');
           
            var rowId = $(inputQuantity).data("id");
            
           if($(inputQuantity).attr('disabled')){
               $('#areaId-'+rowId).attr('name','areaId[]');
               $(inputQuantity).attr('name','qty[]');
               $(inputQuantity).prop('disabled',0);
           }else{
                $(inputQuantity).removeAttr('name');
                $(inputQuantity).val('');
                $('#areaId-'+rowId).removeAttr('name');
               $(inputQuantity).prop('disabled',1);
           }
           
        });       
        $('.qty').keyup( function (event) {
   
            var sum = 0;
            $('.qty').each(function(i, e) {
                
                 if($.isNumeric($(e).val()) || $(e).val() == ''){
                    sum += Number($(e).val());
                }else{
                    $(this).val('');
                    $(this).addClass("error");
                    $('.button-save').hide();
                    alert('Please enter a number greater than 0 in this field.')
                    return false;
                }
            });
            if(sum >  window.qtyReceivingLocation){
                    $(this).val('');
                    $('.button-save').hide();
                    $(this).addClass("error");
                    alert(' Please enter a number less than the allowed quantity.')
                    return false;
                }
            if(sum > 0){
                $(this).removeClass("error");
                $('.button-save').show(); 
            }
         
        });
    });