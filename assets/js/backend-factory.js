$ = jQuery.noConflict();

$(document).ready(function(){

   setup_inputs();
   setup_repeatables();
   setup_metabox_language();

   console.log("Backend Factory: ready");

});


function setup_repeatables() {

   $('.add_repeatable').click(function(){

      if( $(this).parent().find('.field-translated').length > 0 ) {

        var translation_container = $(this).parent()

        var models = translation_container.find('.repeatable-model .input-container')

        var target_container


        models.each(function(){

          var model = $(this).clone().detach();

          target_container = $(this).parent().parent()

          if( model.data('type') == 'field_group' ) {
            var i = target_container.find('[data-type=field_group]').last().data('i')
            i++
            model.attr('data-i',i)
            model.find('input').each(function(){
              var input = $(this)
              var basename = input.attr('name').split('[')[0]
              var newName = basename + "[" + i + "][" + input.attr('name').split('[')[1]
              input.attr('name',newName)
            })

          }

          target_container.append( model );

        })

      } else {

        var model = $(this).parent().find('.repeatable-model .input-container').clone().detach();


        if( model.data('type') == 'field_group' ) {
          var last = $(this).parent().find('[data-type=field_group]').last();

          var i = last.data('i')
          i++
          model.attr('data-i',i)
          model.find('input').each(function(){
            var input = $(this)
            var basename = input.attr('name').split('[')[0]
            var newName = basename+"["+i+"]["+ input.attr('name').split('[')[1]
            input.attr('name',newName)
          })

        }

        target_container = $(this).parent().find('.field-repeatable-inputs')
        target_container.append( model );

      }





      setup_inputs();
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
         var datepicker = $('input.datepicker');

         datepicker.each(function(){
            console.log( $(this).data('target') );
            $(this).datepicker({
               altField: '#' + $(this).data('target'),
               altFormat: "yy-mm-dd"
            });

         })

         if( $(this).find('input').val().length == 0 ) {
            $(this).find('.delete_this').addClass('disabled')
         }
      });

   }


   $('.repeatable-container input').keyup(function(){
      if( $(this).val().length != 0 ) {
         $(this).parent().find('.delete_this').removeClass('disabled');
      } else {
         $(this).parent().find('.delete_this').addClass('disabled');
      }
   })

}





function setup_inputs() {
   setup_datepicker();
   setup_uploader();
}


function setup_datepicker() {

   var datepicker = $('input.datepicker');

   datepicker.each(function(){
      console.log( $(this).data('target') );
      $(this).datepicker({
         altField: '#' + $(this).data('target'),
         altFormat: "yy-mm-dd"
      });

   })

}


function setup_uploader() {
   $('.upload-button').click(function(e) {
      e.preventDefault();

      var button = $(this);

      var file = wp.media({
         title: 'Upload Image',
         // mutiple: true if you want to upload multiple files at once
         multiple: false
      }).open()
      .on('select', function(e){

         var uploaded_file = file.state().get('selection').first();

         var file_url = uploaded_file.toJSON().url;

         button.parent().find('.upload_input').val(file_url);
      });
   });
}





function setup_metabox_language() {

  $('.field-translated').hide();
  $('.field-translated[lang=es]').show();


   $('.metabox-language-selector li a').click(function(){
     var metabox = $(this).parent().parent().parent().parent()
     // console.log(metabox);
     var lang = $(this).attr('lang')
     console.log(lang);

     metabox.find('.field-translated').hide();
     metabox.find('.field-translated[lang='+lang+']').show();

     return false;

   })

}
