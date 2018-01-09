<?php


class MetaboxFieldCreator {

   var $field_type_functions;

   var $mfc;


   function __construct() {

      $this->field_type_functions = array();


   }

   public function create_field( $field, $value, $translationKey=NULL, $i=-1 ) {


      $html = "";

      # code...
      switch( $field['field_type'] ) {

         case 'text':
         case 'email':
         case 'url':
         case 'number':
         case 'integer':
         case 'float':
         case 'datetime':
         $html .= $this->form_input( $field, $value, $translationKey );
         break;

         case 'html':
         case 'textarea':
         $html .= $this->textarea( $field, $value, $translationKey );
         break;

         case 'date':
         $html .= $this->date( $field, $value, $translationKey );
         break;

         case 'datetime':
         case 'time':
         $html .= $this->datetime( $field, $value, $translationKey );
         break;


         case 'related_post' :

         $html .= $this->related_post( $field, $value, $translationKey );
         break;


        case 'field_group' :

        $html .= $this->field_group( $field, $value, $translationKey, $i );
        break;


         case 'upload' :
         $html .= $this->upload_field( $field, $value, $translationKey );
         break;
      }




      return $html;

   }







   // Field Types:




   function form_input( $field, $value, $translationKey = NULL ) {

      ob_start();

      $field_name = $field['field_name'];
      if( $translationKey ) :
        $field_name .= "_" . $translationKey;
      endif;


      ?>

      <input
      type="<?php echo $field['field_type']; ?>"
      name="<?php echo $field['repeatable'] ? $field_name . '[]' : $field_name; ?>"
      value="<?php echo $value; ?>"
      name="<?php echo $field_name; ?>"

      <?php
      if( $translationKey ) : ?>
        lang="<?php echo $translationKey; ?>"
        class="input-translated"
      <?php endif; ?>

      <?php echo $field['field_type'] == "float" ? 'step="0.000000000001"': ''; ?>
      <?php echo $field['field_type'] == "float" ? 'step="0.000000000001"': ''; ?>
      <?php echo $field['repeatable'] ? 'class="repeatable-input w-80"' : ''; ?>
      >


      <?php

      if( $field['repeatable'] ) {
         ?>
         <button class="delete_this w-20">remove</button>
         <?php
      }

      $html = ob_get_contents();
      ob_end_clean();
      return $html;

   }


   function textarea( $field, $value ) {
      ob_start();
      ?>
      <div class="textarea-container">
         <textarea
         name="<?php echo $field['repeatable'] ? $field['field_name'] . '[]' : $field['field_name']; ?>"
         <?php echo $field['repeatable'] ? 'class="repeatable-input w-80"' : ''; ?>
         ><?php echo $value; ?></textarea>
         <?php
         if( $field['repeatable'] ) {
            ?>
            <button class="delete_this w-20">remove</button>
            <?php
         }
         ?>
      </div>
      <?php
      $html = ob_get_contents();
      ob_end_clean();
      return $html;
   }



   function date( $field, $value ) {
      ob_start();
      ?>

      <input type="datetime"
      data-target="<?php echo $field['field_name']; ?>"
      value="<?php echo $value; ?>"
      class="datepicker <?php echo $field['repeatable'] ? 'repeatable-input w-80' : ''; ?>">
      <input type="datetime"
      id="<?php echo $field['field_name']; ?>"
      name="<?php echo $field['repeatable'] ? $field['field_name'] . '[]' : $field['field_name']; ?>"
      value="<?php echo $value; ?>"
      class="hidden">

      <?php

      if( $field['repeatable'] ) {
         ?>
         <button class="delete_this w-20">remove</button>
         <?php
      }

      $html = ob_get_contents();
      ob_end_clean();
      return $html;

   }

   function datetime( $field, $value ) {
      ob_start();
      ?>
      <input
      type="datetime"
      name="<?php echo $field['repeatable'] ? $field['field_name'] . '[]' : $field['field_name']; ?>"
      value="<?php echo $value; ?>"
      <?php echo $field['repeatable'] ? 'class="repeatable-input w-80"' : ''; ?>
      >

      <?php

      if( $field['repeatable'] ) {
         ?>
         <button class="delete_this w-20">remove</button>
         <?php
      }

      $html = ob_get_contents();
      ob_end_clean();
      return $html;

   }




    function field_group( $field, $value, $translationKey, $i=-1 ) {

        // var_dump($value);

       $fieldName = $field['field_name'];

       if( $translationKey ) :
         $fieldName .= "_" . $translationKey;
       endif;

       ?>

       <fieldset class="field_inputs <?php echo $field['repeatable'] ? 'class="repeatable-input w-80"' : ''; ?>">

         <?php

         $keys = array_keys($field['field_group']);

         foreach ( $keys as $key ) :

           $eachField = $field['field_group'][$key];

           $eachFieldLabel = $field['field_group'][$key]['field_label'];

           if( $translationKey ) :
             $eachFieldLabel = $eachField['translations'][$translationKey]['field_label'];
           endif;

           $name = $fieldName;


           if( $i > -1 ) {
             $name .= '['.$i.']';
             // $eachFieldGroupValue = $value[$i][$key];
           } else {
           //   $eachFieldGroupValue = NULL;
           }

           $eachFieldGroupValue = $value[$key];
           $name .= '['.$key.']';
           //
           // var_dump($i);
           // var_dump($key);
           // var_dump($value);
           // var_dump($value[$i]);
         ?>

           <label for="">
             <?php echo $eachFieldLabel; ?>
           </label>


           <input
           type="text"
           name="<?php echo $name; ?>"

           <?php if( $translationKey ) : ?>
             lang="<?php echo $translationKey; ?>"
             class="input-translated"
           <?php endif; ?>

           value="<?php echo $eachFieldGroupValue; ?>"
           >

         <?php endforeach; ?>


         <?php if( $field['repeatable'] ) { ?>

           <button class="delete_this w-20">remove</button>

         <?php } ?>


       </fieldset>

       <?php
       $html = ob_get_contents();
       ob_end_clean();
       return $html;

    }



   function upload_field( $field, $value, $translationKey, $i=-1 ) {


      $fieldName = $field['field_name'];

      if( $translationKey ) :
        $fieldName .= "_" . $translationKey;
      endif;
      wp_enqueue_script('jquery');

      wp_enqueue_media();

      ob_start();

      $upload_input_id = "upload_input-" . $fieldName;
      $upload_button_id = "upload_button-" . $fieldName;
      ?>

      <input
      type="url"
      name="<?php echo $field['repeatable'] ? $fieldName . '[]' : $fieldName; ?>"
      class="upload_input"

      <?php if( $translationKey ) : ?>
        lang="<?php echo $translationKey; ?>"
        class="input-translated"
      <?php endif; ?>

      value="<?php echo $value; ?>"
      >
      <input
      type="button"
      name="<?php echo $upload_button_id; ?>"
      class="upload-button button-secondary"
      value="Upload File"
      >

      <?php
      if( $field['repeatable'] ) {
        ?>
        <button class="delete_this w-20">remove</button>
        <?php
      }


      $html = ob_get_contents();
      ob_end_clean();
      return $html;
   }

   public function related_post( $field, $value ) {

      $related_posts = $value;
      ob_start();


      if( is_array( $field['related_post_types'] ) )
      foreach ($field['related_post_types'] as $related_post_type ) {

         $related_post_type_posts = get_posts( array( 'post_type' => $related_post_type, 'numberposts' => -1 ) );


         if( $field['repeatable'] ) {
            $related_post_type_field_name .= '[]';

            $field_name .= $repeatable ? '[]' : '';
            ?>

            <select
            name="<?php echo $field['repeatable'] ? $field['field_name'] . '[]' : $field['field_name']; ?>"
            class="columns <?php echo $field['repeatable'] ? 'w-80' : ''; ?>">

            <option value="0" <?php echo ! $value ? 'selected' : ''; ?>></option>
            <?php foreach( $related_post_type_posts as $related_post ) : ?>

               <option value="<?php echo $related_post->ID; ?>" <?php echo $value==$related_post->ID ? 'selected="true"' : ''; ?>>
                  <?php echo $related_post->post_title; ?>
               </option>

               <?php
            endforeach;
            ?>

         </select>

         <button class="delete_this w-20">remove</button>
         <?php

      }


   }

   $html = ob_get_contents();
   ob_end_clean();

   return $html;


}







};

?>
