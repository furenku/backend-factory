<?php

class MetaboxFieldCreator {

   var $field_type_functions;

   var $mfc;


   public function test_func( $param1, $param2 ) {

      var_dump( "testfunc" );

      var_dump( $param1, $param2 );

   }
   function __construct() {

      $this->field_type_functions = array();

   }

   public function create_field( $field, $value ) {


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
         $html .= $this->form_input( $field, $value );
         break;

         case 'related_post' :
         $html .= $this->related_post( $field, $value );
         break;

      }




      return $html;

   }







   // Field Types:






   function form_input( $field, $value ) {


      ob_start();
      ?>
      <input
      type="<?php echo $field['field_type']; ?>"
      name="<?php echo $field['repeatable'] ? $field['field_name'] . '[]' : $field['field_name']; ?>"
      value="<?php echo $value; ?>"
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




   public function date_input( $field, $value ) {
      ?>


      <input type="datetime" data-target="<?php echo $field['field_name']; ?>" value="<?php echo $value; ?>" class="datepicker">
      <input type="datetime" id="<?php echo $field['field_name']; ?>" name="<?php echo $field['field_name']; ?>" value="<?php echo $value; ?>" class="hidden">


      <?php

   }



   public function related_post( $field, $value ) {

      $related_posts = $value;


      if( is_array( $field['related_post_types'] ) )
      foreach ($field['related_post_types'] as $related_post_type ) {

         $related_post_type_field_name =  $metabox['post_type'] . '-' . $related_post_type;

         $posts = get_posts( array( 'post_type' => $related_post_type, 'numberposts' => -1 ) );


         if( $field['repeatable'] ) {
            $related_post_type_field_name .= '[]';
            $this -> related_post_selector( $related_post_type_field_name, $posts, 0, true, true );
         }

         if(is_array($related_posts)) {

            foreach( $related_posts as $related_post ) {
               if( $related_post != "0" ) {
                  $this -> related_post_selector( $related_post_type_field_name, $posts, $related_post, $field['repeatable'] );
               }
            }

         } else {
            if( ! $field['repeatable'] ) {
               $this -> related_post_selector( $related_post_type_field_name, $posts, (int)$related_posts, $field['repeatable'] );
            }
         }

         if( $field['repeatable'] ) {
            $this -> related_post_selector( $related_post_type_field_name, $posts, $field['repeatable']  );
         }

      }


   }




   // Custom Form Components:

   public function related_post_selector( $name, $related_posts, $id=0, $repeatable = false, $hidden=false ) {


      $name .= $repeatable ? '[]' : '';
      ?>
      <div class="<?php echo $repeatable ? 'repeatable' : ''; echo " "; echo $hidden ? 'hidden':''; ?>">

         <select class="columns w-80" name="<?php echo $name; ?>">
            <option value="0" <?php echo ! $id ? 'selected' : ''; ?>></option>
            <?php foreach( $related_posts as $related_post ) : ?>

               <option value="<?php echo $related_post->ID; ?>" <?php echo $id==$related_post->ID ? 'selected="true"' : ''; ?>>
                  <?php echo $related_post->post_title; ?>
               </option>

               <?php
            endforeach;
            ?>

         </select>

         <button class="delete_this w-20 <?php echo $hidden ? ' hidden':''; ?>">remove</button>

      </div>


      <br>

      <?php
   }


};

?>
