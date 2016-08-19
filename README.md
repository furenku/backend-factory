# backend-factory
## Wordpress Plugin
###by kernspaltung

##Backend Configuration tool for WP Developers

+ Simple Custom Post Type Creation API
+ Automatic Fields and Metaboxes
+ Custom Post Type Relationships

```
// CPT registration
$cpt = array(
   'post_type' => 'test_item_' . $i,
   'hierarchical' => 'true',
   'singular' => "Test Element " . $i,
   'plural' => "Test Elements " . $i
);

$backendFactory -> addCPT( $cpt );

$backendFactory -> register_cpts();
```
