jQuery(document).ready(function (){
   var $ = jQuery;


   // Hide/Show Replace Empty Word Field
    $('.trs-invoice-replace-empty-word').click(function (){

        if ($(this).find('.switch-options label.selected').next().val().length < 1)
            $(this).parents('tr').next().removeClass('hidden').find('fieldset').removeClass('hidden');
        else
            $(this).parents('tr').next().addClass('hidden').find('fieldset').addClass('hidden');
    });

   // Hide/Show Debug Field
   $('.redux-container-switch').click(function (){

       if ($(this).find('.switch-options label.selected').next().val().length < 1)
           $(this).parents('tr').next().removeClass('hidden').find('fieldset').removeClass('hidden');
       else
           $(this).parents('tr').next().addClass('hidden').find('fieldset').addClass('hidden');
   });

});
