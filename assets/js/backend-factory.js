jQuery(document).ready(function($){

   var datepicker = $('input.datepicker');

   datepicker.each(function(){
      console.log( $(this).data('target') );
      $(this).datepicker({
         altField: '#' + $(this).data('target'),
         altFormat: "yy-mm-dd"
      });

   })

   console.log("Backend Factory: ready");

});
