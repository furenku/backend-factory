<?php
/*
Plugin Name: Backend Factory for Wordpress
Author: kernspaltung!
Description: Backend Configuration tool for Developers
*/

global $textdomain;
$textdomain = 'backend-factory';

include_once 'classes/backend_factory.php';
include_once 'classes/metabox_fields.php';
include_once 'classes/dynamic_metaboxes.php';

include_once 'cpt/cpt.php';
include_once 'metaboxes/metaboxes.php';
include_once 'translations/translations.php';



// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
   die;
}

add_action( 'init', 'backend_factory_init' );

function backend_factory_init() {

   global $cpts, $metaboxes, $translations;

   $backendFactory = new BackendFactory();

   $dynamic_metaboxes = new DynamicMetaboxes( $backendFactory );


   foreach( $cpts as $cpt ) {
      $backendFactory -> add_cpt( $cpt );
   }

   foreach( $metaboxes as $metabox ) {
      $dynamic_metaboxes -> add_metabox( $metabox );
   }
   foreach( $translations as $translationKey => $translationArray ) {
     $backendFactory -> add_translation( $translationKey, $translationArray );
   }


   $backendFactory -> register_cpts();



   if ( !session_id() ) {
      session_start();
   }

   // $backendFactory -> register_metaboxes();
}






add_action("admin_enqueue_scripts", "load_assets");

function load_assets() {
   wp_enqueue_style( 'backend-factory', plugin_dir_url(__FILE__) . "assets/css/backend-factory.css");
}


add_action("admin_notices","wp_errors");

function wp_errors() {
   if ( array_key_exists( 'backend-factory-errors', $_SESSION ) ) { ?>

     <div class="error">
         <p><?php echo $_SESSION['backend-factory-errors']; ?></p>
     </div><?php

     unset( $_SESSION['backend-factory-errors'] );

   }
}




?>
