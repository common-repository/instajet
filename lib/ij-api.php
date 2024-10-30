<?php

class IJ_Api {

  /**
   * Public function __construct
   */
  public function __construct() {

    // ajax method to get information on a particular aircraft
    add_action('wp_ajax_get_the_aircraft_info', array($this, 'get_aircraft_json'));
    // plugins loaded because pluggable.php needs to be present!
    add_action('query_vars', array($this, 'add_query_vars'), 0);
    add_action('parse_request', array($this, 'sniff_requests'), 0);
    add_action('init', array($this, 'add_endpoints'), 0);
  }

  /** Add public query vars
   * 	@param array $vars List of current public query vars
   * 	@return array $vars
   */
  public function add_query_vars($vars) {

    $vars[] = 'ij_get_flights';
    $vars[] = 'ij_selected_flight';
    $vars[] = 'ij_update_aircraft';
    $vars[] = 'ij_get_flight_status';
    $vars[] = 'ij_aircraft_id';
    $vars[] = 'pagename';
    $vars[] = 'post_id';
	
	$vars[] = 'orig';
	$vars[] = 'dest';
	$vars[] = 'pax';
	$vars[] = 'dep';
	$vars[] = 'ret';
	
    return $vars;
  }

  /**
   *  Add API Endpoint
   * 	This is where the magic happens - brush up on your regex skillz
   * 	@return void
   */
  public function add_endpoints() {
    add_rewrite_rule('(flight-search)$', 'index.php?pagename=flightsearchresults', 'top');
    add_rewrite_rule('(flight-booking)\/(\d*)$', 'index.php?pagename=flightbooking&post_id=$matches[2]', 'top');
    add_rewrite_rule('(flight-status)\/(\d*)$', 'index.php?pagename=flightstatus&post_id=$matches[2]', 'top');
  }

  /**
   *  Sniff Requests
   * 	This is where we hijack all API requests
   * 	@return die if API request
   */
  public function sniff_requests() {
    global $wp;
	
	if (isset($_POST['ij-method']) && $_POST['ij-method']=="book") {
     	$this->handle_flight_booking();
    }
	
	if (isset($wp->query_vars['orig']) && isset($wp->query_vars['dest']) && isset($wp->query_vars['pax'])) {
     	$this->handle_flight_request();
    }
	
    if (isset($wp->query_vars['ij_get_flights'])) {
      $this->handle_flight_request();
    }
    if (isset($wp->query_vars['ij_selected_flight'])) {
      $this->handle_select_request();
    }
    if (isset($wp->query_vars['ij_get_flight_status'])) {
      $this->handle_status_request();
    }
  }

  /**
   *  Handle Flight Search Requests
   *  This is where we create and attach a fresh WP_Journey to the superglobal for access in the template
   * 	@return void
   */
  protected function handle_flight_request() {
    global $wp;
	
    /*$flightrequest = $wp->query_vars['ij_get_flights'];
    if (wp_verify_nonce($flightrequest, 'ij_request_flight_suggestions')) {*/
	
      // create a post here, for the post_id reference
	  
	  /* no need to do this straight away, just going to spam the real results!
      
	  $post = array(
          'post_content' => 'Any notes?',
          'post_name' => 'Journey Request',
          'post_title' => 'Request', // The title of your post.
          'post_status' => 'publish',
          'post_type' => 'flight-requests',
      );

      $post_id = wp_insert_post($post, true);
	  */
	  
      // if return is numeric + not WP_Error instance
      // if (!is_wp_error($post_id)) {

        // if (absint($post_id)) {
          // then we are good to use this as a reference on our journey insert
          $factory = new IJ_Journey_factory();
		  
          // construct a journey object
          $journey = $factory->build($post_id);	 
		  
		  // get geocodes 
		  /*include_once("ij-geocode.php");
		  	
		  $ijg = new IJ_Geocode;
		  $geo['dest'] = $ijg->locate($_GET['dest']);
		  $geo['orig'] = $ijg->locate($_GET['orig']);*/
		  		  
          // then store all the data we have currently
		  // ^^ slows the whole thing down for some reason - necessary before booking?
          $journey->store_meta(
			  array(
				  'destination' => $_GET['dest'],
				  'origin' => $_GET['orig'],
				  'pax' => $_GET['pax'],
				  'outbound-date' => $_GET['dep'],
				  'origin-lat' => $geo['orig']['lat'],
				  'origin-lng' => $geo['orig']['lng'],
				  'dest-lat' => $geo['dest']['lat'],
				  'dest-lng' => $geo['dest']['lng'],
			  )
          );
		  
		  if(!empty($_GET['ret'])){
			  $journey->store_meta(
				  array(
					  'destination' => $_GET['orig'],
					  'origin' => $_GET['dest'],
					  'pax' => $_GET['pax'],
					  'outbound-date' => $_GET['ret'],
					  'origin-lat' => $geo['dest']['lat'],
					  'origin-lng' => $geo['dest']['lng'],
					  'dest-lat' => $geo['orig']['lat'],
					  'dest-lng' => $geo['orig']['lng'],
				  )
			  );
		  }
		  
		  // now work out what aircraft is best for each leg
		  $journey->get_the_journey();
		  		  
          // now we have a journey and have calculated meta information -- store in a global for access in templates?
          $_SESSION['journey'] = $journey;
        // }
      // }
    //}
  }

  /** Handle Flight Select Request
   *  This is where we get the contact details of someone who has selected a flight.
   * 	@return void
   */
  protected function handle_select_request() {
    global $wp;

    $selected_flight = $wp->query_vars['ij_selected_flight'];
// todo: debug nonce
// if( wp_verify_nonce( $selected_flight, 'ij_selected_flight' ) ) {
    // the current post id
    $post_id = $wp->query_vars['post_id'];
    // if return is numeric + not WP_Error instance

    if (absint($post_id)) {
      // then we are good to use this as a reference on our journey insert
      $factory = new IJ_Journey_factory();
      // construct a journey object
      $journey = $factory->build($post_id);

      if (absint($wp->query_vars['ij_aircraft_id'])) {
        $journey->addSelectedAircraft($wp->query_vars['ij_aircraft_id']);
      }
      // now we have a journey and have calculated meta information -- store in a global for access in templates?
      $_SESSION['journey'] = $journey;
    }
//        }
  }

  /** Handle Status Requests
   *  This method has two functions - if there are postvars around - it needs to store the POST data,
   *  otherwise - use the factory to retrieve
   * 	@return void
   */
  protected function handle_status_request() {

    global $wp;

    $new_status_request = isset($wp->query_vars['ij_get_flight_status']) ? true : false;

    // if new_status_request then we need to add additional post data that is present here...
    if ($new_status_request) {
//            if( wp_verify_nonce( $new_status_request, 'ij_status_request' ) ) {
      // the current post id
      $post_id = $wp->query_vars['post_id'];
      // if return is numeric + not WP_Error instance

      if (absint($post_id)) {
        // then we are good to use this as a reference on our journey insert
        $factory = new IJ_Journey_factory();
        // construct a journey object
        $journey = $factory->build($post_id);

        $journey->saveUser($_GET);

//                    $_SESSION['journey'] = $journey;
      }
//            }
    }
    else {
      // the current post id
      $post_id = $wp->query_vars['post_id'];
      // if return is numeric + not WP_Error instance
      if (absint($post_id)) {
        // then we are good to use this as a reference on our journey insert
        $factory = new IJ_Journey_factory();
        // construct a journey object
        $journey = $factory->build($post_id);
      }
    }
    // check for the presence of a journey
    if (isset($journey)) {
      // now we have a journey and have calculated meta information -- store in a global for access in templates?
      $_SESSION['journey'] = $journey;
    }
    else {

      global $wp_query;

      // trigger a 404 as we need a journey!
      $wp_query->set_404();
      status_header(404);
      nocache_headers();
    }
  }

  /**
   * Add Journey Leg
   *
   */
  protected function add_journey_leg() {
    global $wp;
  }

  /** Response Handler
   * 	This sends a JSON response to the browser
   */
  protected function send_response($msg, $pugs = '') {
    $response['message'] = $msg;
    if ($pugs)
      $response['pugs'] = $pugs;
    header('content-type: application/json; charset=utf-8');
    echo json_encode($response) . "\n";
    exit;
  }

  // Adding the id var so that WP recognizes it
  public function instajet_query_vars($vars) {
    array_push($vars, 'id');
    return $vars;
  }

  /**
   * Instajet Flush Rules Function - will need to flush if they change the url structure for search results
   */
  public function instajet_flush_rules() {
    $rules = get_option('rewrite_rules');

    if (!isset($rules['(flight-request)/(\d*)$'])) {
      global $wp_rewrite;
      $wp_rewrite->flush_rules();
    }
  }

  /**
   * Handles the updating of aircraft and redirects back with a $_GET success variable
   * returns @Redirect
   */
  public function update_aircraft_information() {
    // need pluggable explicitly for the time being
    // verify nonce
    if (wp_verify_nonce($_POST['ij_update_aircraft'], 'ij_update_aircraft_action')) {
      // now treat each variable as if it required updating anyways, update, and redirect back with a $_GET var to show updated message
      $id = $_POST['postid'];
      update_post_meta($id, 'ij_max_range', $_POST['flight_distance']);
      update_post_meta($id, 'ij_cruise_speed', $_POST['cruise_speed']);
      update_post_meta($id, 'ij_bcpf', $_POST['base_cost']);
      update_post_meta($id, 'ij_cph', $_POST['cost_per_hour']);
      update_post_meta($id, 'ij_pax', $_POST['ij_pax']);
      $_GET['updated'] = true;
    }
  }

  /**
   * Ajax handler for getting the right information for each aircraft type and piping them into place
   * return @Response [JSON]
   */
  public function get_aircraft_json() {

    $postid = intval($_POST['id']);
    $meta = get_post_meta($postid);
    // the response array
    $response = array();

    // unset() one level of nesting essentially
    foreach ($meta as $k => $v) {
      $response[$k] = $v[0];
    }
    // pin postid back in place for save
    $response['postid'] = $postid;

    $response = (!empty($meta) ) ? json_encode($response) : json_encode(array('status' => 'false'));

    echo $response;

    die();
  }
  
  /**
   *  Handle Flight Booking
   * 	@return void
   */
  protected function handle_flight_booking() {
	  global $wpdb;

	  /*
	  print_r($_SESSION['journey']);
	  print_r($_POST);
	  exit;
	  */
	  
	  $post = array(
          'post_content' => '',
          'post_title' => 'Request', // The title of your post.
          'post_status' => 'publish',
          'post_type' => 'flight-requests',
      );

      $post_id = wp_insert_post($post, true);
	  
      // if return is numeric + not WP_Error instance
      if (!is_wp_error($post_id)) {
		  
		  // adds input to the array
		  $table_name = $wpdb->prefix . 'legs';
		  
		  foreach($_SESSION['journey']->legs as $leg){
			  $wpdb->insert($table_name, 
			  	array( 
				  'post_id' => $post_id, 
				  'start_airport_id' => $leg['start_airport_id'], 
				  'end_airport_id' => $leg['end_airport_id'], 
				  'outbound_date' => ij_db_date($leg['outbound_date']),
				));		  
			  $insert_id = $wpdb->insert_id;	
			  	  
			  // create a reference on the post in postmeta to allow a two way relationship here
			  add_post_meta($post_id, 'journey_id', $insert_id);
		  }
		  			  			  
		  add_post_meta($post_id, "max_passengers", $_SESSION['journey']->max_passengers);
		  add_post_meta($post_id, "total_distance", $_SESSION['journey']->total_distance);
		  add_post_meta($post_id, "longest_leg", $_SESSION['journey']->longest_leg);
		  add_post_meta($post_id, "requested_jets", $_POST['jets']);
		  add_post_meta($post_id, "user_details", array($_POST['user_details']));
		  add_post_meta($post_id, "flight_status", "Pending");
		  
		  $legs = get_post_meta( $post_id, 'journey_id' );
		  
		  $summary = "<p><strong>Flight Summary</strong></p>";
		  $summary .= "<p>" . $_SESSION['journey']->max_passengers . " passenger " . ($_SESSION['journey']->max_passengers==1 ? "" : "s") . " flying the following routes:</p>";
		  $summary .= "<p>";
		  
		  foreach($legs as $leg){
            $l = ij_get_journey_leg($leg);
            $orig = ij_get_airport($l->start_airport_id);
            $dest = ij_get_airport($l->end_airport_id);
            $date = ij_nice_date($l->outbound_date);
			$summary .= "$date - $orig >>> $dest" . "<br>";
		  }	
		  
		  $summary .= "</p>";  
		  		  
		  $options = get_option('instajet_options');
		  
		  $do_send = $options['instajet_emails_new_cust'] != 0;
		  
		  if($do_send){
			  $body .= "<p>Thanks for requesting a quote for your flight.</p>";
			  $body .=  $summary;
			  $body .= "<p>We'll update you about your quote via email at ".$_POST['user_details']['email']." or by telephone on ".$_POST['user_details']['telephone'].".</p>";
			  $body .= "<p>View your flight request at <a href='" . get_site_url() . "/flight-status/".$post_id."/'>" . get_site_url() . "/flight-status/".$post_id."/</a>.";
			  $body .= "<p><strong>".get_bloginfo()."</strong></p>";
			  $to = $_POST['user_details']['email'];
			  $subject = "Your Flight Request";		  
			  ij_send_mail($to,$subject,$body);
		  }
		  
		  $do_send = $options['instajet_emails_new_admin'] != 0;
		 		  
		  if($do_send){
			  $body = "<p>A customer has requested a quote.</p>";
			  $body .=  $summary;
			  $body .= "<p>You can update this customer via email at ".$_POST['user_details']['email']." or by telephone on ".$_POST['user_details']['telephone'].".</p>";
			  $body .= "<p>View the flight request in your <a href='" . get_site_url() . "/wp-admin/post.php?post=".$post_id."&action=edit'>WordPress Admin</a>.";
			  $body .= "<p><strong>Instajet</strong></p>";
			  $to = $option['instajet_admin_email'];
			  if(empty($to)) $to = get_option( 'admin_email' );
			  $subject = "New Flight Request";		  
			  ij_send_mail($to,$subject,$body);
		  }
		  
	  }
	  
	  wp_redirect(get_site_url() . "/flight-status/" . $post_id . "/");
	  exit;
  }

}