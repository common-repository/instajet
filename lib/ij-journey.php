<?php

class IJ_Journey {

  /**  the post->id   * */
  public $id;

  /** @var theprice * */
  public $price;

  /** @var null|\WP_Post  */
  public $post;
  // aircraft!
  public $aircraft = array();
  // legs are array, stick something in
  public $legs = array();
  //longest leg distance
  public $longest_leg = 0;
  // total distance
  public $total_distance = 0;
  // has aircraft boolean
  protected $has_aircraft = false;
  // API response
  protected $response;
  public $max_passengers;
  private $aircraft_id;

  /**
   * __construct function.
   *
   * @access public
   * @param mixed $journey
   */
  public function __construct($journey) {
    /*if ($journey instanceof WP_Post) {
      $this->id = absint($journey->ID);
      $this->post = $journey;
    }
    elseif ($journey instanceof IJ_Journey) {
      $this->id = absint($journey->id);
      $this->post = $journey;
    }
    else {
      $this->id = absint($journey);
      $this->post = get_post($this->id);
    }*/
    // include the aircraft filter class. we wanna run this after we have our journey as that's when we have our distance.
    $this->includes();
  }

  /**
   * Takes in input and inserts into WP_Journey object
   * also triggers the main calculation of everything
   * 
   * @param $in
   */
  public function store_meta($in) {
	
	global $wpdb;
	
    // add the ability
    if (isset($in['destination']) && isset($in['origin'])) {

		// check if the origin-lat origin-lng dest-lat and dest-lng exist, as all need to be present
		if (!isset($in['origin-lat']) || !isset($in['origin-lng']) || !isset($in['dest-lat']) || !isset($in['dest-lng'])) {
						
			$pattern = "/(.*) \([a-zA-Z]*\)/";
			$replacement = "$1";
			
			$in['origin'] = preg_replace($pattern, $replacement, stripslashes($in['origin']));
			$in['destination'] = preg_replace($pattern, $replacement, stripslashes($in['destination']));
		  
			include_once('ij-geocode.php');
			
			$geocoder = new IJ_Geocode();
			
			$origin = $geocoder->locate($in['origin']);
			$destination = $geocoder->locate($in['destination']);
			
			if($origin['status']!="OK") {
				echo "Geocoding origin failed<br>";
				echo $in['origin'] . "<br>";
				print_r($origin);
				die();
			} else {
				$origin = $origin['results'][0]['geometry']['location'];	
			}
			if($destination['status']!="OK") {
				echo "Geocoding destination failed<br>";
				echo $in['destination'] . "<br>";
				print_r($destination);
				die();
			} else {
				$destination = $destination['results'][0]['geometry']['location'];	
			}
			
			$in['origin-lat'] = $origin['lat'];
			$in['origin-lng'] = $origin['lng'];
			$in['dest-lat'] = $destination['lat'];
			$in['dest-lng'] = $destination['lng'];
			
		}
			
		// adds input to the array
		//$table_name = $wpdb->prefix . 'journey';
				
		/*$wpdb->insert($table_name, array('post_id' => $this->id, 'destination' => $in['destination'], 'origin' => $in['origin']));
		
		$insert_id = $wpdb->insert_id;
		// create a reference on the post in postmeta to allow a two way relationship here
		add_post_meta($this->id, 'journey_id', $insert_id);
		
		$in['journey_id'] = $insert_id;*/
		
		$this->max_passengers = $in['pax'];		
		
		// then we need to store a leg here
		$this->legs[] = $this->add_leg($in);
     }
  }

  /**
   * Retrieves all the information from the $wpdb->prefix'd journey table relating to our current journey
   * @return mixed
   */
  public function get_meta() {
    global $wpdb;
    // lets us retrieve info
    $journey_table = $wpdb->prefix . 'journey';
    $airport_table = $wpdb->prefix . 'airports';
    $meta_results = $wpdb->query(
            "SELECT * FROM " . $journey_table . " WHERE post_id = " . $this->id . "
             LEFT JOIN $airport_table origin ON $journey_table.origin = origin.airport_id 
             LEFT JOIN $airport_table destination ON $journey_table.destination = destination.airport_id"
    );
    return $meta_results;
  }

  /**
   * Retrieve All Legs
   * @return mixed
   */
  public function get_legs() {
    global $wpdb;
    /*
	$table_name = $wpdb->prefix . "journeylegs";

    $this->legs = $wpdb->get_results('SELECT * FROM ' . $table_name . ' WHERE post_id = ' . $this->id, OBJECT);
	*/
    return $this->legs;
  }

  public function get_start_leg() {
    if (empty($this->legs)) {
      // return the first leg entry
      $this->get_legs();
    }

    return $this->legs[0];
  }

  public function get_end_leg() {
    if (empty($this->legs)) {
      $this->get_legs();
    }
    $count = count($this->legs);
    return $this->legs[$count - 1];
  }

  /**
   * Add a Leg Function - also calculates distance
   * @param $data
   */
  public function add_leg($data) {

    global $wpdb;

    $table_name = $wpdb->prefix . "journeylegs";

    $leg = array(
        'post_id' => $this->id,
        'journey_id' => $data['journey_id'],
        'startlat' => $data['origin-lat'],
        'startlng' => $data['origin-lng'],
        'startlocation' => $data['origin'],
        'endlat' => $data['dest-lat'],
        'endlng' => $data['dest-lng'],
        'endlocation' => $data['destination'],
        'outbound_date' => $data['outbound-date'],
        'return_date' => $data['return-date']
    );

    $leg['start_airport_id'] = $this->find_airport($data['origin-lat'], $data['origin-lng']);
    $leg['end_airport_id'] = $this->find_airport($data['dest-lat'], $data['dest-lng']);
	
    // todo sort this layout out :)
    // $wpdb->insert($table_name, $leg, array('%d', '%d', '%f', '%f', '%s', '%f', '%f', '%s', '%s', '%s'));

    return $leg;
  }

  /**
   * If a Leg Changes we will need to update
   * @param $data
   */
  public function update_leg($data) {

    global $wpdb;

    $table_name = $wpdb->prefix . "journeylegs";

    $where = array(
        'where' => 'keyvals'
    );
  }

  // public accessor to calc()
  public function get_the_journey() {
    $this->calc();
  }

  private function includes() {
    // class that figures out what aircraft are worth using
    include_once( 'ij-aircraft-filter.php');
  }

  /**
   * The Main Calculating Function, recursively called until all legs
   * are accounted for and we can send detailed trip stats
   */
  private function calc() {

    // better to get the longest length first
    foreach ($this->legs as $leg) {
      // check for the presence of a distance
      if (empty($leg['distance'])) {
        // if no distance, then we need to haversine from point to point
        $leg['distance'] = $this->haversine($leg['startlat'], $leg['startlng'], $leg['endlat'], $leg['endlng']);
      }
      // check against current longest leg
      $this->longest_leg = ($leg['distance'] > $this->longest_leg) ? $leg['distance'] : $this->longest_leg;

      // add total distance up accumulatively
      $this->total_distance = $this->total_distance + $leg['distance'];
    }

    global $wpdb;
    // first we need to get all the aircraft out of the database...
    // depending on performance obviously^^
	// ^^ updated query so that canJourney is irrelevant
    $querystr = "SELECT p.* FROM $wpdb->posts p
				 INNER JOIN $wpdb->postmeta pm1 ON pm1.post_id = p.ID
				 INNER JOIN $wpdb->postmeta pm2 ON pm2.post_id = p.ID
				 INNER JOIN $wpdb->term_relationships tr ON tr.object_id = p.ID
				 INNER JOIN $wpdb->term_taxonomy t ON t.term_taxonomy_id = tr.term_taxonomy_id
				 WHERE p.post_type = 'aircraft' 
				 AND pm1.meta_key = '_smartmeta_ij_max_range'
				 AND pm1.meta_value >= ".$this->longest_leg."
				 AND pm2.meta_key = '_smartmeta_ij_pax'
				 AND pm2.meta_value >= ".$this->max_passengers."
				 AND t.taxonomy = 'aircrafttypes'
				 AND p.post_status = 'publish'
				 GROUP BY p.ID";

    $aircraft = $wpdb->get_results($querystr, OBJECT);
		
    // loop through, and turn each into a filterable object
    foreach ($aircraft as $ac) {
      if (!empty($ac)) {
        // fill in the post;data
        setup_postdata($ac);
        // pass in our $ac object to retrive postmeta
        $aircraft = new IJ_Aircraft($ac);
        // if the aircraft can visit these guys
        //if ($aircraft->canJourney($this->max_passengers, $this->longest_leg)) {
          // then calc the cost

		  // get category
		  $aircraft_type = get_the_terms($aircraft->id, "aircrafttypes");
		  $aircraft_type_final = $aircraft_type[0]->name;
		 		  				  
          $this->aircraft[$aircraft_type_final][] = $aircraft;
		  
          // set has_aircraft to do checks on whether things have things
          $this->has_aircraft = (!empty($this->aircraft)) ? true : false;
        //}
      }
    }

    // after we've done this, we need to sort the available aircraft or something?
    $this->sortAircraft();
			
	$api_data = $this->callApi();
	if($api_data->message!="200 OK") die($api_data->message);
	
	//print_r($api_data);
	
	// Overwrite old array with new data (NOT WP class though - bear in mind)
	$this->aircraft = $api_data->data;
	
	/*foreach($api_data->data as $group => $aircraft_list){
		print_r($aircraft_list);
	}*/
	
  }

  /**
   * Public function hasAircraft to do something like if $journey->hasAircraft()
   */
  public function hasAircraft() {
    return $this->has_aircraft;
  }
  
  /**
  * Sends data to API and gets response
  */
  private function callApi(){
	   
	  $fields = array(
		  'data' => array(
			  'aircraft' => $this->aircraft,
			  'journey' => array("distance"=>$this->total_distance),
		  ),
		  'method' => 'get_quotes'
	  );
	  	  
	  return ij_get_api_json($fields);
  }

  /**
   * Puts the aircraft into distance order in their original state
   * called here because we only want to categorise aircraft that are valid for this trip...
   * Safe
   * @return void
   */
  public function sortAircraft() {

    /*$tempSorting = array();

    foreach ($this->aircraft as $aircraft) {
      $tempSorting[$aircraft->term->description][] = $aircraft;
    }

    $this->aircraft = $tempSorting;*/
	
  }

  /**
   * Uses airports table to work out the nearest airport, which is used for our distance
   * returns @airport_id
   */
  private function find_airport($lat, $lng) {
    global $wpdb;

    $table_name = $wpdb->prefix . "airports";

    $airport_id = $wpdb->get_results(
            // this simply gets the closest airport so can be unaware of what the plugins km/m setting is
            "SELECT id, ( 3959 * acos( cos( radians( $lat ) ) * cos( radians( latitude_deg ) )
            * cos( radians( longitude_deg ) - radians( $lng ) ) + sin( radians( $lat ) ) * sin( radians( latitude_deg )) ) ) AS distance
            FROM $table_name
            HAVING distance < 100
            ORDER BY distance
            LIMIT 1"
    );

    return $airport_id[0]->id;
  }

  /**
   * Haversine function
   * @param $latA
   * @param $lngA
   * @param $latB
   * @param $lngB
   * @param $earthradius
   * @return float (Distance between two points in kilometres)
   */
  private function haversine($latFrom, $lngFrom, $latTo, $lngTo, $earthRadius = 6371) {
    // convert from degrees to radians
    $latFrom = deg2rad($latFrom);
    $lonFrom = deg2rad($lngFrom);
    $latTo = deg2rad($latTo);
    $lonTo = deg2rad($lngTo);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    return $angle * $earthRadius;
  }

  /**
   * _get magic method
   * @param $key
   * @return string
   */
  // allows us to query certain postmeta individually - like the aircraft types being used
  public function __get($key) {
    $value = null;

    // Get values or default if not set
    if (in_array($key, array('aircraft'))) {
      $value = ( $value = get_post_meta($this->id, '_' . $key, true) ) ? $value : 'no';
    }

    return $value;
  }

  /**
   * Wrapper for get_permalink
   * @return string
   */
  public function get_permalink() {
    return get_permalink($this->id);
  }

  /**
   * Returns whether or not the journey post exists.
   *
   * @access public
   * @return bool
   */
  public function exists() {
    return empty($this->post) ? false : true;
  }

  /**
   * Get the price
   * Only triggers if we have aircraft, otherwise needs to get aircraft then get price
   * @access public
   * @return bool
   */
  public function get_price() {
    if ($this->has_aircraft()) {
      // then get the most likely price out
    }

    return apply_filters('instajet_get_price', $this->price);
  }

  /**
   * Helper function to access private has Aircraft var
   * @return bool
   */
  public function has_aircraft() {
    return $this->has_aircraft;
  }

  /**
   * Adds the selected aircraft to the journey to ensure the quote
   * Aircraft $id
   */
  public function addSelectedAircraft($aircraft_id) {
    // update
    global $wpdb;

    $update = array(
        'aircraft_id' => $aircraft_id
    );
    $where = array(
        'post_id' => $this->id
    );
    $wpdb->update($wpdb->prefix . 'journey', $update, $where);

    $this->aircraft_id = $aircraft_id;
  }

  /**
   * Retrieve the selected Aircraft for views etc, looks at whether the current journey has an aircraft_id,
   * if not, gets that shit from the database. Shits so cash.
   */
  public function getSelectedAircraft() {
    global $wpdb;

    if (isset($this->aircraft_id) && $this->aircraft_id) {
      $table_name = $wpdb->prefix . 'posts';
    }
    else {
      $table_name = $wpdb->prefix . 'journey';
      $aircraft_id = $wpdb->get_var("SELECT aircraft_id FROM $table_name WHERE post_id = $this->id LIMIT 1");

      if (!is_null($aircraft_id)) {
        $this->aircraft_id = $aircraft_id;
      }
      $table_name = $wpdb->prefix . 'posts';
    }
    $aircraft = $wpdb->get_results("SELECT * FROM $table_name WHERE id = $this->aircraft_id AND post_type = 'aircraft'");
    return $aircraft[0];
  }

  /**
   * The Leg Loop - used in a couple of places to render out the legs of this journey
   * @return leg Loop
   */
  public function leg_loop() {
    $html = '';
    if (!empty($this->legs)) {
      $html .= '<ul>';
      foreach ($this->legs as $leg) {
//                var_dump($leg);die();
        $string = '<li>' . $leg->startlocation . '<br />' . $leg->endlocation . '<br />' . $leg->return_date . '<br />' . $leg->outbound_date . '<br />' . '</li>';
        $html .= $string;
      }
      $html .= '</ul>';
    }
    echo $html;
  }

  /**
   * ij_get_status get the current status of this journey - if none is found- then we default to 'under-review'
   *
   */
  public function getStatus() {

    /*global $wpdb;

    $table_name = $wpdb->prefix . 'journey';

    $status = $wpdb->get_var("SELECT journey_status FROM $table_name WHERE post_id = $this->id LIMIT 1");

    if (!strlen($status) > 0 || is_null($status) || empty($status)) {
      $status = 'under-review';
    }

    return $status;*/
  }

}