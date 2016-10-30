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
      $(this).parent().find('.repeatable-container.hidden').clone().detach().removeClass('hidden').appendTo( '.field-repeatable-inputs' );
   })

   $('.delete_this').click(function(){
      $(this).parent().remove();
   })

}
