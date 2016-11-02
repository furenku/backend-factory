$ = jQuery.noConflict();

$(document).ready(function(){

   var datepicker = $('input.datepicker');

   datepicker.each(function(){
      console.log( $(this).data('target') );
      $(this).datepicker({
         altField: '#' + $(this).data('target'),
         altFormat: "yy-mm-dd"
      });

   })

   setup_repeatables();

   console.log("Backend Factory: ready");

});


function setup_repeatables() {

   $('.add_repeatable').click(function(){
      console.log( $(this).parent().attr('class') );
      var model = $(this).parent().find('.repeatable-model .input-container').clone().detach();
      $(this).parent().find('.field-repeatable-inputs').append( model );
      return false;
   })


   $('.delete_this').click(function(){

      if( $(this).parent().index() == $(this).parent().parent().find('.input-container').length - 1 ) {

         $(this).parent().find('input').val('');
         $(this).parent().find('.delete_this').addClass('disabled');
      } else {
         $(this).parent().remove();
      }
      return false;
   })

   if($('.repeatable-container:not(.hidden)').length>1) {
      $('.repeatable-container .delete_this').removeClass('disabled')
      $('.repeatable-container').each(function(){
         if( $(this).find('input').val().length == 0 ) {
            $(this).find('.delete_this').addClass('disabled')
         }
      });

   }


   $('.repeatable-container input').keyup(function(){
      if( $(this).val().length != 0 ) {
console.log("test!!");
         $(this).parent().find('.delete_this').removeClass('disabled');
      } else {
console.log("test!!!!");
         $(this).parent().find('.delete_this').addClass('disabled');
      }
   })

}
