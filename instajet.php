<?php
/**
 * Plugin Name: Instajet
 * Plugin URI: http://instajet.co.uk/
 * Description: A private jet charter booking and lead generation tool for brokers.
 * Version: 1.1.8
 * Author: Joe Birkin, Chris Cox, Jack Wall & Michael Gunner 
 * Author URI: http://instajet.co.uk/
 * License: GPLv3
 * 
 * Text Domain: instajet
 *
 * @package Instajet
 */

/**
 *  Instajet Main
 *
 */
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

define( 'EDD_SL_STORE_URL', 'http://instajet.co.uk' ); 
define( 'EDD_SL_ITEM_NAME', 'Instajet' ); 

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater if it doesn't already exist 
	include( dirname( __FILE__ ) . '/lib/EDD_SL_Plugin_Updater.php' );
}

function edd_plugin_updater() {

	$key = trim(get_option('instajet_license_key'));
	
	if(!empty($key)){

		$edd_updater = new EDD_SL_Plugin_Updater( EDD_SL_STORE_URL, __FILE__, array(
			'version' 	=> '1.1.8',
			'license' 	=> $key,
			'item_name' => EDD_SL_ITEM_NAME,
			'author' 	=> 'Joe Birkin, Chris Cox, Jack Wall & Michael Gunner',
			'url'       => home_url()
		) );
	}
}

add_action( 'admin_init', 'edd_plugin_updater', 0 );

function ij_get_custom_post_type_template($single_template) {
	 global $post;

	 if ($post->post_type == 'flight-requests') {
		  $single_template = dirname( __FILE__ ) . '/templates/flight-status.php';
	 }
	 return $single_template;
}
add_filter( 'single_template', 'ij_get_custom_post_type_template' );

// jQuery and jQuery UI
function ij_add_ui_scripts() {
	///wp_register_script( 'jquery' );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'jquery-ui-autocomplete' );
	
	wp_enqueue_style( 'instajet-ui-styles', plugins_url( "assets/css/jquery-ui.structure.min.css" , __FILE__ ) );
	//wp_enqueue_script( 'jquery-ui-import', plugins_url() . "/instajet/assets/js/jquery-ui.min.js" );
	
	wp_enqueue_script( 'shortcodes', plugins_url("assets/js/shortcodes.js", __FILE__ ), "jquery", false, true );
	// Localize the script with new data
	$tmp = array(
		'ajax_url' => admin_url( 'admin-ajax.php' ) . "?action=ij_airport_search"
	);
	wp_localize_script( 'shortcodes', 'lclstn', $tmp );
}

add_action( 'wp_enqueue_scripts', 'ij_add_ui_scripts' );

require_once(plugin_dir_path(__FILE__) . 'lib/rdplugin.php');

if (!class_exists('InstaJet')) :

  // Not final to enable extensions to the plugin
  class InstaJet extends RDPlugin {

    // the default instajet option array
    protected $data = array();
    // the current insta Session
    public $session = null;
    // the current insta dbQuery - maybe results cached?
    public $query = null;
    // the current trip params and info
    public $trip = null;
    // api obj
    public $api = null;

    /*     * *
     * The Constructor
     * 
     * @access public
     * @return object InstaJet
     */

    public function __construct() {

      $this->plugin_name = 'Instajet';
      $this->plugin_store = 'http://instajet.co.uk';
      $this->common_includes = array(
          'admin/ij-helper-functions.php' => false,
          'ij-api.php' => false,
          'ij_journey_factory.php' => false,
          'ij-journey.php' => false,
          'ij-widgets.php' => false,
          'ij-shortcodes.php' => false,
          'ij-query.php' => false,
          'ij-posttypes.php' => false,
          'ij_install.php' => false,
          'ij-database.php' => false,
      );
      $this->admin_includes = array(
          'admin/ij-admin.php' => false,
      );
	  
      parent::__construct($this->plugin_name);
	  
      if (is_admin()) {
        $this->admin = new InstaJetAdmin($this);
      }
	  
      do_action('instajet_loaded');      
    }

    /**
     * Activation hook
     */
    public static function activate() {
      parent::activate();
	  IJ_Database::install();
    }

    /**
     * Init instaJet when WordPress inits
     */
    public function init() {
      // fire off an action to hook into with extensions etc
      do_action('before_instajet_init');
	  
	  // add tables - should probably move this but wasn't working in activate()
	  IJ_Database::install();
      
	  $this->api = new IJ_Api();
      do_action('instajet_init');
    }

    // TODO : move these into init to be fired once on __construct
    public function admin_init() {
      register_setting('instajet_options', $this->slug, array($this, 'validate'));
	  register_setting('instajet_admin_email', $this->slug, array($this, 'validate'));
    }

    /**
     * Add our custom page templates
     * 
     * @param string $template
     * @return string
     */
    public function template_loader($template) {
      $find = array('instajet.php');
	  
      $file = '';

      if (is_single() && get_post_type() == 'flight-requests') {
        $file = 'single-journey.php';
        $find[] = $file;
        $find[] = '/templates/' . $file;
        $find[] = $this->plugin_path . 'templates/' . $file;
      }

      if (is_page(ij_get_page_id('flightsearchresults'))) {
        $file = 'ij-results-template.php';
      }

      if (is_page(ij_get_page_id('flightbooking'))) {
        $file = 'ij-flightbooking-template.php';
      }

      if (is_page(ij_get_page_id('flightstatus'))) {
        $file = 'ij-flightstatus-template.php';
      }

      if ($file) {
        // this will check the active theme dir first, before falling back to our preset version
        // http://codex.wordpress.org/Function_Reference/locate_template
        $template = locate_template($find);
        if (!$template) {
          $template = $this->plugin_path . 'templates/' . $file;
        }
      }
      return $template;
    }
	
	public function get_admin(){
		return !empty($this->admin) ? $this->admin : false;
	}	

    public function frontend_scripts() {
		$options = get_option('instajet_options');
		$theme = !empty($options['theme']) ? $options['theme'] : "";
		
		if($theme==""){
			wp_enqueue_style('instajet_theme', $this->plugin_url . 'assets/css/theme.css');
		} else if($theme=="bootstrap"){
			wp_enqueue_style('instajet_theme', $this->plugin_url . 'assets/css/bootstrap.css');	
		}
		
		// always include googlemaps
		wp_register_script('googlemaps', 'http://maps.googleapis.com/maps/api/js?libraries=places,geometry&sensor=true', false, '1');
		//wp_register_script('geocomplete', $this->plugin_url . 'assets/js/jquery.geocomplete.js', true, '1', true);

		//wp_register_script('validate', $this->plugin_url . 'assets/js/jquery.validate.min.js', false, '1');
		//wp_register_script('validate-additional', $this->plugin_url . 'assets/js/additional-methods.min.js', false, '1');

		wp_enqueue_script('googlemaps');
		wp_enqueue_script('geocomplete');

		//wp_enqueue_script('validate');
		//wp_enqueue_script('validate-additional');
    }
    
    /**
     * Deactivation hook
     * 
     */
    public static function deactivate() {
      parent::deactivate();
      # self::uninstall(); // FOR TESTING ONLY
    }
    
    /**
     * Uninstall hook. Should remove all plugin data from the DB.
     * 
     * @global type $wpdb
     */
    public static function uninstall() {
      parent::uninstall();
      global $wpdb;
      //$wpdb->query('DROP TABLE ' . $wpdb->prefix . 'journey');
      $wpdb->query('DROP TABLE ' . $wpdb->prefix . 'legs');
      $wpdb->query('DROP TABLE ' . $wpdb->prefix . 'airports');
      delete_option('instajet_db_version');
    }
    
  }

  endif;

// Autocomplete airports and sort by string similarity
// Source: http://openflights.org/data.html
function ij_airport_search() {
	global $wpdb;
	$term = "%" . strtolower( $_GET['term'] ) . "%";
	$query = "SELECT * FROM ".$wpdb->prefix."airports WHERE name LIKE %s OR municipality LIKE %s OR iso_country LIKE %s OR iata_code LIKE %s";
	$query = $wpdb->prepare($query,$term,$term,$term,$term);
	$results = $wpdb->get_results($query) or die(mysql_error());
	$return = array();
	
	foreach($results as $data){
		$data = (array)$data;
		$label = str_replace("\\","","$data[name], $data[municipality]");
		$label .= $data['iso_country']!="NA" ? " ($data[iso_country])" : "";
		$label .= $data['iata_code']!="" ? " ($data[iata_code])" : "";
		$return[$data['id']] = array("label"=>$label,"value"=>$label);
	}
	echo json_encode($return);
	exit();
}

add_action( 'wp_ajax_ij_airport_search', 'ij_airport_search' );
add_action( 'wp_ajax_nopriv_ij_airport_search', 'ij_airport_search' );

// Global for backwards compatibility.
$GLOBALS['instajet'] = InstaJet::get_instance();

add_action( 'init', 'instajet_rewrites_init' );

function instajet_rewrites_init(){
    add_rewrite_rule(
        'flight-status/([0-9]+)?$',
        'index.php?post_type=flight-requests&p=$matches[1]',
        'top' );
}

add_filter('post_type_link', 'instajet_post_type_link', 1, 3);
function instajet_post_type_link( $link, $post = 0 ){
    if ( $post->post_type == 'flight-requests' ){
        return home_url( 'flight-status/' . $post->ID );
    } else {
        return $link;
    }
}

function ij_flight_exists( $id ) {
  return is_string( get_post_status( $id ) );
}

function ij_db_date($date){
	$tmp = explode("/",$date);
	return $tmp[2] . "-" . $tmp[1] . "-" . $tmp[0] . " 00:00:00";
}

function ij_nice_date($date){
	return date("d/m/Y",strtotime($date));
}

function ij_get_journey_leg($id){
	if(empty($id)) return false;
	
	global $wpdb;
	$table_name = $wpdb->prefix . "legs";
	
	$sql = 'SELECT * FROM ' . $table_name . ' WHERE id = "' . $id . '"';
	
    $result = $wpdb->get_results($sql, OBJECT);
	
	return $result[0];
}

function ij_get_airport($id,$title=true){
	global $wpdb;
	$table_name = $wpdb->prefix . "airports";
	$array = array();
	
	if(empty($id)){
		$airport = "No airport ID supplied";
	} else {
	
		$sql = 'SELECT * FROM ' . $table_name . ' WHERE id = ' . $id;
		
		$result = $wpdb->get_results($sql, OBJECT);
		
		if(sizeof($result)>0){
		
			$array = $result[0];
			$airport = $result[0]->name . ", " . $result[0]->municipality;
			$airport .= ($result[0]->iso_country != "NA") ? ", " . $result[0]->iso_country : "";
			$airport .= !empty($result[0]->iatafaa_code) ? " (" . $result[0]->iata_code . ")" : "";
		
		} else {
			$airport = "Airport not found: $id";
		}
	}
	
	return $title ? $airport : $array;	
}

add_action('init', 'ij_start_session', 1);
add_action('wp_logout', 'ij_end_session');
add_action('wp_login', 'ij_end_session');

function ij_start_session() {
    if(!session_id()) {
        session_start();
    }
}

function ij_end_session() {
    session_destroy ();
}

add_action( 'wp_login', 'ij_add_login_time' );
function ij_add_login_time( $user_login ) {
    global $wpdb;
    $user_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->users WHERE user_login = %s", $user_login ) );
	$ij_last_login = get_user_meta( $user_id, 'ij_current_login', true );
	if(empty($ij_last_login)){
		$ij_last_login = current_time('mysql');
	}
	update_user_meta( $user_id, 'ij_ij_last_login', $ij_last_login );
    update_user_meta( $user_id, 'ij_current_login', current_time('mysql') );	
}

function ij_last_login($user_id = ""){
	if(empty($user_id)){
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
	}
	return $ij_last_login = get_user_meta($user_id, 'ij_ij_last_login', true);	
}

function ij_total_flight_requests($start='', $end='', $status=''){
	$args = array(
		'posts_per_page' => -1,
		'post_type' => 'flight-requests',
	);
	
	if(!empty($start)){
		$args['date_query'] = array(
			array(
				'after'     => $start,
				'before'    => empty($end) ? date("Y-m-d H:i:s") : $end
			),
			'inclusive' => true,
		);
	}
	
	if(!empty($status)){
		$args['meta_query'] = array(
			array(
				'key' => 'flight_status',
				'value' => 'Booked',
				'compare' => '='
			)
		);
	}
	
	if(!current_user_can("manage_options")){
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		$args['author'] = $user_id;
	}
	
	//print_r($args);
	
	$query = new WP_Query($args);
	return $query->found_posts;
}

function ij_total_quoted($start='', $end='', $user_id = null){
	global $wpdb;
	$author = "AND p.post_author IS NOT NULL";
	$date = "";
	
	if(!current_user_can("manage_options")){
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
	}
	
	if(!empty($user_id)){
		$author = "AND p.post_author = $user_id";
	}
	
	if(!empty($start)){
		$date = "AND p.post_date > '".$start."'";
		
		if(!empty($end)){
			$date = " AND p.post_date < '".$end."'";
		}
	}
	
	$meta_key = 'flight_quote';
	$sum = $wpdb->get_col($wpdb->prepare("SELECT pm.meta_value FROM $wpdb->postmeta pm
										  INNER JOIN $wpdb->posts p ON p.ID = pm.post_id
										  WHERE pm.meta_key = %s
										  $author
										  $date
										  AND p.post_type = 'flight-requests'", $meta_key));
	return array_sum( $sum );
}

function ij_average_quoted($start='', $end='', $user_id = null){
	global $wpdb;
	$author = "AND p.post_author IS NOT NULL";
	$date = "";
	
	if(!current_user_can("manage_options")){
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
	}
	
	if(!empty($user_id)){
		$author = "AND p.post_author = $user_id";
	}
	
	if(!empty($start)){
		$date = "AND p.post_date > '".$start."'";
		
		if(!empty($end)){
			$date = " AND p.post_date < '".$end."'";
		}
	}
	
	$sum = $wpdb->get_col("SELECT pm.meta_value FROM $wpdb->postmeta pm
						  INNER JOIN $wpdb->posts p ON p.ID = pm.post_id
						  WHERE pm.meta_key = 'flight_quote'
						  $author
						  $date
						  AND p.post_type = 'flight-requests'");
	//return array_sum( $sum );
	
	$count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->postmeta pm
							INNER JOIN $wpdb->posts p ON p.ID = pm.post_id
							WHERE pm.meta_key = 'flight_quote'
							AND pm.meta_value > 0
							$author
							$date
							AND p.post_type = 'flight-requests'");
										  
	return $count > 1 ? array_sum($sum)/$count : 0;
}

function ij_api_url(){
	return "http://instajet.co.uk/api/flight-search/?";	
}


function ij_currency(){
	$options = get_option('instajet_options');
	$which_currency = $options['instajet_currency'];

	if($which_currency == 2){
		$ij_currency = "$";

	} else if ($which_currency == 1){
		$ij_currency = "£";

	} elseif ($which_currency == 0){
		$ij_currency = "€";

	} else {
		$ij_currency = "£";
	}

	return $ij_currency;	
}

function ij_get_api_json($fields){
	$key = get_option('instajet_license_key');
	
	$fields['data']['apikey'] = $key;
	$fields['data']['domain'] = home_url();
	
	$fields_string = http_build_query($fields);
	
	//open connection
	$ch = curl_init();
	
	$url = ij_api_url();
	  
	//set the url, number of POST vars, POST data
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	
	//execute post
	$result = curl_exec($ch);
	
	//close connection
	curl_close($ch);
	
	return json_decode($result); 	
}

function ij_send_mail($to,$subject,$body){
	
	$body = "<span style='font-family:sans-serif;font-size:13px;'>" . $body . "</span>";
	
	$options = get_option('instajet_options');
	$from = $options['instajet_admin_email'];
	if(empty($from)) $from = get_option( 'admin_email' );
	
	$headers[] = 'From: '.get_bloginfo('name').' <'.$from.'>';
	$headers[] = 'Content-Type: text/html; charset=UTF-8';	
		
	wp_mail( $to, $subject, $body, $headers );		
}