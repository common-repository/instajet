<?php

/* * *
 * Custom Post Types
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Set up Custom Post Types used by the plugin
 */
class InstaJetPostTypes {

  /**
   * Constructor
   * 
   * Hooks creation of post types and taxonomies to init
   */
  public function __construct() {
    add_action('init', array(__CLASS__, 'register_custom_post_types'), 5);
    add_action('init', array(__CLASS__, 'register_custom_taxonomies'), 5);
    add_action('init', array(__CLASS__, 'set_up_custom_fields'), 5);
    add_filter('manage_edit-aircraft_columns', array($this, 'aircraft_custom_columns'));
    add_action('manage_aircraft_posts_custom_column', array($this, 'manage_aircraft_columns'));
  }

  /**
   * Register the post types 
   * 
   * @return false
   */
  public static function register_custom_post_types() {
    // if flights is already a post type
    if (post_type_exists('flight-requests'))
      return;

    do_action('instajet_register_post_types');

    register_post_type('flight-requests', array(
        'labels' => array(
            'name' => 'Flight Requests',
            'singular_name' => 'Flight Request',
            'add_new' => 'Add New',
            'add_new_item' => 'Add Flight Request',
            'edit' => 'Edit',
            'edit_item' => 'Edit Flight Request',
            'new_item' => 'New Flight Request',
            'view' => 'View',
            'view_item' => 'View Flight Request',
            'search_items' => 'Search Flight Requests',
            'not_found' => 'No Flight Requests found',
            'not_found_in_trash' => 'No Flight Requests found in Trash',
            'parent' => 'Parent Flight Request'
        ),
        'public' => true,
        'menu_position' => 5,
        'supports' => array('title', 'editor', 'author', 'revisions'),
        'taxonomies' => array(''),
        'has_archive' => true,
        'hierarchical' => false, // Hierarchical causes memory issues - WP loads all records!
		'rewrite' => array( 'slug' => 'flight-status', 'with_front' => false ),
        )
    );

    register_post_type('aircraft', array(
        'labels' => array(
            'name' => 'Aircraft',
            'singular_name' => 'Aircraft',
            'add_new' => 'Add New',
            'add_new_item' => 'Add Aircraft',
            'edit' => 'Edit',
            'edit_item' => 'Edit Aircraft',
            'new_item' => 'New Aircraft',
            'view' => 'View',
            'view_item' => 'View Aircraft',
            'search_items' => 'Search Aircraft',
            'not_found' => 'No Aircraft found',
            'not_found_in_trash' => 'No Aircraft found in Trash',
            'parent' => 'Parent Aircraft'
        ),
        'public' => true,
        'show_ui' => true,
        'menu_position' => 5,
        'supports' => array('title', 'editor', 'thumbnail'),
        'taxonomies' => array('aircrafttypes'),
        'has_archive' => false,
        'hierarchical' => false,
        )
    );
  }

  /**
   * Register the taxonomies for the plugin
   */
  public static function register_custom_taxonomies() {
    if (taxonomy_exists('aircrafttypes'))
      return;

    $labels = array(
        'name' => _x('aircrafttypes', 'instajet'),
        'singular_name' => _x('Aircraft Type', 'instajet'),
        'search_items' => _x('Search Aircraft Types', 'instajet'),
        'popular_items' => _x('Popular Aircraft Types', 'instajet'),
        'all_items' => _x('All Aircraft Types', 'instajet'),
        'parent_item' => _x('Parent Aircraft Type', 'instajet'),
        'parent_item_colon' => _x('Parent Aircraft Type:', 'instajet'),
        'edit_item' => _x('Edit Aircraft Type', 'instajet'),
        'update_item' => _x('Update Aircraft Type', 'instajet'),
        'add_new_item' => _x('Add New Aircraft Type', 'instajet'),
        'new_item_name' => _x('New Aircraft Type', 'instajet'),
        'separate_items_with_commas' => _x('Separate Aircraft Types with commas', 'instajet'),
        'add_or_remove_items' => _x('Add or remove Aircraft Types', 'instajet'),
        'choose_from_most_used' => _x('Choose from the most used Aircraft Types', 'instajet'),
        'menu_name' => _x('Aircraft Types', 'aircrafttypes'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => false,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => false,
        'hierarchical' => true,
        'rewrite' => true,
        'query_var' => true,
    );

    register_taxonomy('aircrafttypes', array('aircraft'), $args);

    /**
     * Seed the Database with Predefined taxonomy
     */
    $aircraft = array(
        array('VeryLightJet', 'Very Light Jet', 'Cessna Citation Mustang', 330, 5, 2161, 1500),
        array('LightJet', 'Light Jet', 'Cessna Citation CJ2', 389, 6, 2408, 2100),
        array('SuperLightJet', 'Super Light Jet', 'Learjet 45', 445, 8, 3167, 3000),
        array('MidSizeJet', 'Mid Size Jet', 'Hawker 800XP', 447, 8, 4893, 3100),
        array('SuperMidSize', 'Super Mid Size', 'Cessna Citation Sovereign', 458, 9, 5273, 3700),
        array('HeavyJet', 'Heavy Jet', 'Challenger 600', 459, 19, 6236, 4200),
        array('UltraLongRangeJet', 'Ultra Long Range Jet', 'Global XRS', 488, 19, 9360, 5800),
        array('Airliner', 'Airliner', 'Boeing BBJ', 480, 63, 11480, 16000)
    );
	
	$img_path = ABSPATH . "/wp-content/plugins/instajet/assets/img/aircraft/";

    foreach ($aircraft as $actax) {
      // first item is the tax term
	  $term = $actax[1];
      if (!term_exists($term, 'aircrafttypes')) {
		
		$jet = $actax[2];
		$slug = strtolower(str_replace(' ', '-', $term));
		
        wp_insert_term($term, 'aircrafttypes', array('description' => $term, 'slug' => $slug));

        $post = array(
            'post_content' => '',
            'post_name' => $slug,
            'post_title' => $jet,
            'post_type' => 'aircraft',
            'post_status' => 'publish'
        );
        // insert wp_insert_post
        $post_id = wp_insert_post($post);

        wp_set_object_terms($post_id, $term, 'aircrafttypes');
		
		$image_url = $img_path . $jet . ".jpg";
		
		if(file_exists($image_url)){
			add_jet_image( $image_url, $post_id  );
		} else {
			error_log("Failed to set image for $jet");	
		}

        // insert post meta about aircraft
        add_post_meta($post_id, '_smartmeta_ij_cruise_speed', $actax[3]);
        add_post_meta($post_id, '_smartmeta_ij_pax', $actax[4]);
        add_post_meta($post_id, '_smartmeta_ij_max_range', $actax[5]);
        add_post_meta($post_id, '_smartmeta_ij_cph', $actax[6]);
        // base cost per hour is a dummy value
        add_post_meta($post_id, '_smartmeta_ij_bcpf', 1000);
      }
    }
  }

  /**
   * Register custom fields for the aircraft post type
   */
  public static function set_up_custom_fields() {
    add_smart_meta_box('aircraft-meta', array(
        'title' => __('Aircraft Details', 'instajet'),
        'pages' => array('aircraft'),
        'context' => 'normal',
        'priority' => 'low',
        'fields' => array(
            array(
                'name' => __('Cruise Speed (knots)', 'instajet'),
                'id' => 'ij_cruise_speed',
                'type' => 'text',
                'default' => ''
            ),
            array(
                'name' => __('Max Capacity (PAX)', 'instajet'),
                'id' => 'ij_pax',
                'type' => 'text',
                'default' => ''
            ),
            array(
                'name' => __('Max Range', 'instajet'),
                'id' => 'ij_max_range',
                'type' => 'text',
                'default' => ''
            ),
            array(
                'name' => __('Cost per Hour', 'instajet'),
                'id' => 'ij_cph',
                'type' => 'text',
                'default' => ''
            ),
            array(
                'name' => __('Base Cost per Flight', 'instajet'),
                'id' => 'ij_bcpf',
                'type' => 'text',
                'default' => ''
            ),
        )
            )
    );
  }

  /**
   * Explicitly set columns for the Aircraft admin UI
   * 
   * @param array $columns
   */
  public function aircraft_custom_columns($columns) {
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => __('Aircraft', 'instajet'),
        'aircrafttype' => __('Aircraft Type', 'instajet'),
        'pax' => __('PAX', 'instajet'),
        'range' => __('Max Range', 'instajet'),
        'cruise_speed' => __('Cruise Speed', 'instajet')
    );
    return $columns;
  }

  /**
   * Output the values for the custom columns
   * 
   * @global obj $post
   * @param string $column
   */
  public function manage_aircraft_columns($column) {
    global $post;
    switch ($column) {
      case 'aircrafttype':
        $terms = get_the_terms($post->ID, 'aircrafttypes');
        if (!empty($terms)) {
          $out = array();
          foreach ($terms as $term) {
            $out[] = sprintf('<a href="%s">%s</a>', esc_url(add_query_arg(array('post_type' => $post->post_type, 'aircrafttypes' => $term->slug), 'edit.php')), esc_html(sanitize_term_field('name', $term->name, $term->term_id, 'aircrafttypes', 'display'))
            );
          }
          echo join(', ', $out);
        }
        else {
          _e('No Aircraft Types found', 'instajet');
        }
        break;
      case 'pax':
        echo get_post_meta($post->ID, '_smartmeta_ij_pax', true);
        break;
      case 'range':
        $output = get_post_meta($post->ID, '_smartmeta_ij_max_range', true);
        printf(__('%s km', 'instajet'), $output);
        break;
      case 'cruise_speed':
        $output = get_post_meta($post->ID, '_smartmeta_ij_cruise_speed', true);
        printf(__('%s knots', 'instajet'), $output);
        break;
    }
  }

}

function add_jet_image( $image_url, $post_id  ){
	$upload_dir = wp_upload_dir();
	$image_data = file_get_contents($image_url);
	$filename = basename($image_url);
	if(wp_mkdir_p($upload_dir['path']))     $file = $upload_dir['path'] . '/' . $filename;
	else                                    $file = $upload_dir['basedir'] . '/' . $filename;
	file_put_contents($file, $image_data);

	$wp_filetype = wp_check_filetype($filename, null );
	$attachment = array(
		'post_mime_type' => $wp_filetype['type'],
		'post_title' => sanitize_file_name($filename),
		'post_content' => '',
		'post_status' => 'inherit'
	);
	$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
	require_once(ABSPATH . 'wp-admin/includes/image.php');
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
	$res1 = wp_update_attachment_metadata( $attach_id, $attach_data );
	$res2 = set_post_thumbnail( $post_id, $attach_id );
}

// call to construct like a chief
new InstaJetPostTypes();
