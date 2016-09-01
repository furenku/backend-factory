<?php
/*
Plugin Name: Backend Factory for Wordpress
Author: kernspaltung!
Description: Backend Configuration tool for Developers
*/

global $cpts, $metaboxes;

include 'metaboxes/dynamic_metaboxes.php';

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
   die;
}


class BackendFactory {

   var $cpts;

   function init() {
      $this->cpts = array();
   }


   // CPTs (Custom Post Types):

   function add_cpt( $cpt = NULL ) {
      if( $cpt ) {
         array_push( $this -> cpts, $cpt );
      }
   }

   function register_cpts() {

      foreach( $this -> cpts as $cpt ) {

         $slug = $cpt['post_type'];
         $singular = $cpt['singular'];
         $plural = $cpt['plural'];

         $labels = array(
            'name'               => _x( $plural, 'post type general name', 'backend-factory' ),
            'singular_name'      => _x( $singular, 'post type singular name', 'backend-factory' ),
            'menu_name'          => _x( $plural, 'admin menu', 'backend-factory' ),
            'name_admin_bar'     => _x( $singular, 'add new on admin bar', 'backend-factory' ),
            'add_new'            => _x( 'Add New', $singular, 'backend-factory' ),
            'add_new_item'       => __( 'Add New ' . $singular, 'backend-factory' ),
            'new_item'           => __( 'New ' . $singular, 'backend-factory' ),
            'edit_item'          => __( 'Edit ' . $singular, 'backend-factory' ),
            'view_item'          => __( 'View ' . $singular, 'backend-factory' ),
            'all_items'          => __( 'All ' . $plural, 'backend-factory' ),
            'search_items'       => __( 'Search ' . $plural, 'backend-factory' ),
            'parent_item_colon'  => __( 'Parent ' . $plural . ':', 'backend-factory' ),
            'not_found'          => __( 'No ' . $plural . ' found.', 'backend-factory' ),
            'not_found_in_trash' => __( 'No ' . $plural . ' found in Trash.', 'backend-factory' )
         );

         $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'backend-factory' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => $slug ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => $cpt['hierarchical'],
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
         );

         register_post_type( $slug, $args );

      }

   }

}



class DynamicMetaboxes {

   var $metaboxes;

   public function init() {
      $this->metaboxes = array();
      add_action("add_meta_boxes", array( $this, 'register_metaboxes' ) );
      // echo "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum praesentium odit sunt adipisci sequi, eaque tenetur quae libero!";
   }
   public function add_metabox( $metabox = NULL ) {
      if( $metabox ) {
         array_push( $this -> metaboxes, $metabox );
      }
   }

   public function register_metaboxes()
   {
      foreach($this -> metaboxes as $metabox) :
         add_meta_box(
         $metabox['post_type']."-meta-box",
         $metabox['title'],
         "standard_metabox_html",
         $metabox['post_type'],
         "side",
         "default",
         array('metabox'=>$metabox)
      );
   endforeach;
}


// public function testfunc() {
//    echo '<h1>Init metaboxes</h1>';
// }
}

add_action( 'init', 'backend_factory_init' );

function backend_factory_init() {

   $backendFactory = new BackendFactory;

   $backendFactory->init();

   $dynamic_metaboxes = new DynamicMetaboxes;

   $dynamic_metaboxes -> init();


   include_once 'tests/cpt-eventos-lugares.php';
   include_once 'tests/metaboxes-evento.php';

   foreach( $cpts as $cpt ) {
      $backendFactory -> add_cpt( $cpt );
   }
   // add_action("add_meta_boxes", "add_dynamic_metaboxes");
   foreach( $metaboxes as $metabox ) {
      $dynamic_metaboxes -> add_metabox( $metabox );
   }
   // var_dump($backendFactory->metaboxes);

   $backendFactory -> register_cpts();


   // include_once 'tests/content-clean-products.php';
   // include_once 'tests/content-woocommerce-automatic-product.php';

   // add_action("add_metaboxes", array( $backendFactory, 'register_metaboxes' ), 1 );
   // add_action("add_metaboxes", array( $backendFactory, 'testfunc' ) );


   if ( !session_id() ) {
      session_start();
   }

   // $backendFactory -> register_metaboxes();
   // $backendFactory -> init_metaboxes();

   // add_action( 'admin_init', array( $backendFactory, 'init_metaboxes' ) );
}





class Calendar {
   function __construct() {
      add_action("wp_enqueue_scripts", array( $this, "load_assets") );
      date_default_timezone_set('America/Mexico_City');

   }
   public static function load_assets() {
      wp_enqueue_style("calendar", plugin_dir_url( __FILE__ ) . "/assets/stylesheets/calendar.css" );
   }
   public static function do_calendar() {

      $view = "month";


      $date = strtotime( "today" ); //$month . "/" . $day . "/" . $year );
      $monthName = strftime( "%B", $date );
      $dayName     = strftime( "%A", $date );
      $day     = strftime( "%e", $date );
      $month   = strftime( "%m", $date );
      $year    = strftime( "%G", $date );

      ?>
      <nav id="calendar " class="calendar w_100 h_100">

         <header>

<?php if( $view == "month" ) {

   ?>
   <small>
      <?php echo $dayName . ", " . $day . " de " . $monthName . ", " . $year; ?>
   </small>

   <?php } ?>
         </header>

         <section id="calendar-view" class="calendar-view">
            <header>
               
            </header>
            <ul>
               <?php
               // function get_weekdays($m,$y) {


               $days_in_month = cal_days_in_month( CAL_GREGORIAN, $month, $year );

               $date = strtotime( $month.'/1/'.$year);

               $num_week_day1 = $date = strftime("%u", $date );

               for ($i=1; $i <= $num_week_day1 - 1; $i++) {
                  ?>
                  <div class="weekday empty button disabled" style="">
                  </div>
                  <?php
               }


               for ($i=1; $i <= $days_in_month; $i++) {
                  $date = strtotime( $date );
                  $name_week_day = $date = strftime("%A", $date );
                  $date = strtolower($date);
                  ?>
                  <div class="weekday empty button enabled <?php echo $i==$day ? ' today ' : ''; ?>" style="">
                     <?php echo $i; ?>
                  </div>
                  <?php
               }


               ?>
            </ul>
         </section>

      </nav>
      <?php
   }

}

$calendar = new Calendar();
add_shortcode( 'calendar', array( $calendar,'do_calendar'));



?>
