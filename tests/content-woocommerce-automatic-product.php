<?php


// sleep(5);
//
global $wpdb;
$wpdb->query( 'SET autocommit = 0;' );

create_products();

$wpdb->query( 'COMMIT;' );

function create_products() {

   $lorem2 = "<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magni odit pariatur nisi?</p><p>Eum, odit. Ipsam nemo reiciendis minima maxime mollitia expedita, dignissimos incidunt impedit.</p>";

   $taxonomies = array(
      array( "name" => "Rifa" , "taxonomy" => "product_cat", "description" => $lorem2 ),
   );

   for ($i=1; $i <= 20; $i++) :

      $title = str_pad($i, 3, '0', STR_PAD_LEFT);
      $user_id = 1;
      $price = 200;
      $sku = "CLCT_" . $title;

      $product = array(
         'author' => $user_id,
         'content' => 'Info del Boleto',
         'status' => "publish",
         'title' => $title,
         'parent' => '',
         'type' => "product",
         'price' => $price,
         'sku' => $sku,
      );

      create_product( $product, $taxonomies );
      sleep(0.01);

   endfor;


}






function create_product( $product, $taxonomies, $product_type = 'simple' ) {

   $post = array(

      'post_author' => $product['author'],
      'post_content' => $product['content'],
      'post_status' => $product['status'],
      'post_title' => $product['title'],
      'post_parent' => $product['parent'],
      'post_type' => $product['type'],

   );

   //Create post
   $post_id = NULL;

   if( ! get_page_by_title( $post['post_title'], OBJECT, $post['post_type'] ) )
      $post_id = wp_insert_post( $post );

   // var_dump($post_id);

   if($post_id){
      $attach_id = get_post_meta($product['parent'], "_thumbnail_id", true);
      add_post_meta($post_id, '_thumbnail_id', $attach_id);

      update_post_meta( $post_id, '_visibility', 'visible' );
      update_post_meta( $post_id, '_stock_status', 'instock');
      // update_post_meta( $post_id, 'total_sales', '0');
      update_post_meta( $post_id, '_downloadable', 'no');
      update_post_meta( $post_id, '_virtual', 'no');
      update_post_meta( $post_id, '_regular_price', "1" );
      update_post_meta( $post_id, '_sale_price', "1" );
      update_post_meta( $post_id, '_purchase_note', "" );
      update_post_meta( $post_id, '_featured', "no" );
      update_post_meta( $post_id, '_weight', "" );
      update_post_meta( $post_id, '_length', "" );
      update_post_meta( $post_id, '_width', "" );
      update_post_meta( $post_id, '_height', "" );
      update_post_meta( $post_id, '_sku', $product['sku']  );
      update_post_meta( $post_id, '_product_attributes', array());
      update_post_meta( $post_id, '_sale_price_dates_from', "" );
      update_post_meta( $post_id, '_sale_price_dates_to', "" );
      update_post_meta( $post_id, '_price', $product['price'] );
      update_post_meta( $post_id, '_sold_individually', "" );
      update_post_meta( $post_id, '_manage_stock', "yes" );
      update_post_meta( $post_id, '_backorders', "no" );
      update_post_meta( $post_id, '_stock', "1" );

      $term_ids = array();

      foreach( $taxonomies as $taxonomy ) :
         $term = $taxonomy['name'];
         $taxonomy_name = $taxonomy["taxonomy"];
         $term_args = array(
            'alias_of' => '',
            'description' => "Lorem ipsum dolor sit.",
            'parent' => 0,
            'slug' => '',
         );

         if( ! term_exists( $term, $taxonomy_name )) {
            $term_obj = wp_insert_term( $term, $taxonomy_name, $term_args );
            $term_id = $term_obj['term_id'];

         } else {
            $term_id = get_term_by( "name", $term, $taxonomy_name )->term_id;
         }


         if( array_key_exists( $taxonomy_name, $term_ids ) ):
            array_push( $term_ids[ $taxonomy_name ], $term_id );
         else:
            $term_ids[ $taxonomy_name ] = array( $term_id );
         endif;


      endforeach;

      foreach ($term_ids as $taxonomy_name => $id_array ) {
// var_dump($taxonomy_name);
// var_dump($id_array);
         wp_set_object_terms( $post_id, $id_array, $taxonomy_name );

      }





      // // file paths will be stored in an array keyed off md5(file path)
      // $downdloadArray =array('name'=>"Test", 'file' => $uploadDIR['baseurl']."/video/".$video);
      //
      // $file_path =md5($uploadDIR['baseurl']."/video/".$video);
      //
      //
      // $_file_paths[  $file_path  ] = $downdloadArray;
      // // grant permission to any newly added files on any existing orders for this product
      // // do_action( 'woocommerce_process_product_file_download_paths', $post_id, 0, $downdloadArray );
      // update_post_meta( $post_id, '_downloadable_files', $_file_paths);
      // update_post_meta( $post_id, '_download_limit', '');
      // update_post_meta( $post_id, '_download_expiry', '');
      // update_post_meta( $post_id, '_download_type', '');
      // update_post_meta( $post_id, '_product_image_gallery', '');

   }


   // wp_set_object_terms( $post_id, $product_type, 'product_type');



}
