<?php
/**
 * Admin Stuff in here :-)
 */
if (!defined('ABSPATH')) {
  exit;
}

add_action( 'load-post.php', 'hodephinitely_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'hodephinitely_post_meta_boxes_setup' );

function hodephinitely_post_meta_boxes_setup() {
  add_action( 'add_meta_boxes', 'hodephinitely_add_post_meta_boxes' );
}

add_action("admin_menu",'flight_req_stats');
function flight_req_stats(){
	add_submenu_page('edit.php?post_type=flight-requests', 'Request Statistics', 'Statistics', 'edit_posts', 'statistics', 'show_request_statistics');
}

function show_request_statistics() { 
	die();
	$ad = $GLOBALS['instajet']->get_admin();
	$ad->dashboard();
}

function hodephinitely_add_post_meta_boxes() {

  add_meta_box(
    'hodephinitely-jets',      // Unique ID
    'Requested Jets',    // Title
    'hodephinitely_jets_meta_box',   // Callback function
    'flight-requests',         // Admin page (or post type)
    'side',         // Context
    'default'         // Priority
  );
  
  add_meta_box(
    'hodephinitely-flight-status',      // Unique ID
    'Booking Status',    // Title
    'hodephinitely_flight_status_meta_box',   // Callback function
    'flight-requests',         // Admin page (or post type)
    'side',         // Context
    'core'         // Priority
  );
  
  add_meta_box(
    'hodephinitely-user-details',      // Unique ID
    'User Details',    // Title
    'hodephinitely_user_meta_box',   // Callback function
    'flight-requests',         // Admin page (or post type)
    'side',         // Context
    'core'         // Priority
  );
  
  add_meta_box(
    'hodephinitely-quote',      // Unique ID
    'Quoted Price',    // Title
    'hodephinitely_quote_meta_box',   // Callback function
    'flight-requests',         // Admin page (or post type)
    'side',         // Context
    'core'         // Priority
  );
  
  add_meta_box(
    'hodephinitely-journey-details',      // Unique ID
    'Journey Details',    // Title
    'hodephinitely_journey_meta_box',   // Callback function
    'flight-requests',         // Admin page (or post type)
    'normal',         // Context
    'high'         // Priority
  );
  
}

function hodephinitely_jets_meta_box( $object, $box ) {
	$jets = get_post_meta( $object->ID, 'requested_jets', true );
	if(!empty($jets[0])){
		foreach($jets as $jet){ 
			/*if(current_user_can('edit_others_posts')){
			?>
			<p><a target="_blank" href="<?php echo get_site_url() . "/wp-admin/post.php?post=".$jet."&action=edit"; ?>"><?php echo get_the_title($jet); ?></a></p>	
			<?php
			
			} else { */?>
			<p>
			<?php echo get_the_title($jet); ?>
			</p>	
			<?php
			//}
		}
	} else {
		echo "-";	
	}
}

function hodephinitely_flight_status_meta_box( $object, $box ) {
	$status = get_post_meta( $object->ID, 'flight_status', true );
	
	// Possible statuses
	$statuses = array(
		"Pending",
		"Quoted",
		"Booked"
	);
	
	wp_nonce_field( 'flight_status_save', 'ij_nonce' );
	?>
    <select name="ij-status">
    <?php
		foreach($statuses as $s){
		?>
    	<option<?php echo $s==$status ? " selected" : ""; ?>><?php echo $s; ?></option>
    	<?php 
		}
	?>
    </select>
    <?php
}

function hodephinitely_quote_meta_box( $object, $box ) {
	$quote = get_post_meta( $object->ID, 'flight_quote', true );
	?>
    <input type="number" name="ij-quote" placeholder="eg. 1000" value="<?php echo $quote; ?>">
    <?php
}

add_action( 'save_post', 'flight_status_save' );
function flight_status_save( $post_id )
{
	if($_POST['post_type']!="flight-requests") return;
	
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['ij_nonce'] ) || !wp_verify_nonce( $_POST['ij_nonce'], 'flight_status_save' ) ) return;
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;
	
	$body = "<p>Your flight request has been updated!</p>";
     
    // Make sure your data is set before trying to save it
    if( isset( $_POST['ij-status'] ) ){
		$status = get_post_meta($post_id, 'flight_status', true);
        update_post_meta( $post_id, 'flight_status', esc_attr( $_POST['ij-status'] ) );
		if($status!=$_POST['ij-status']){
			$body .= "<p>The status of your request has been updated to <strong>".esc_attr( $_POST['ij-status'] )."</strong>.</p>";	
		}
	}
		
	if( isset( $_POST['ij-quote'] ) ){
		$quote = get_post_meta($post_id, 'flight_quote', true);
        update_post_meta( $post_id, 'flight_quote', esc_attr( $_POST['ij-quote'] ) );
		if($quote!=$_POST['ij-quote']){
			$body .= "<p>Your request has been attributed an estimated cost of <strong>".ij_currency().number_format(esc_attr( $_POST['ij-quote'] ), 2)."</strong>.</p>";	
		}
	}
	
	$options = get_option('instajet_options');
		  
	$do_send = $options['instajet_emails_update'] != 0;
	
	if($do_send){
		$details = get_post_meta( $post_id, 'user_details', true );
		$to = $details[0]['email'];
		
		$body .= "<p>View your flight request at <a href='" . get_site_url() . "/flight-status/".$post_id."/'>" . get_site_url() . "/flight-status/".$post_id."/</a>.";
		$body .= "<p><strong>".get_bloginfo()."</strong></p>";
		
		$subject = "Update To Your Flight Request";				  
		ij_send_mail($to,$subject,$body);
	}
}

function hodephinitely_user_meta_box( $object, $box ) {
	$details = get_post_meta( $object->ID, 'user_details', true );
	foreach($details[0] as $label => $value){
		?>
		<p><strong><?php echo ucwords($label); ?></strong><br /><?php echo $value; ?></p>	
		<?php
	}	
}

function hodephinitely_journey_meta_box( $object, $box ) {
	$legs = get_post_meta( $object->ID, 'journey_id' );
	$pax = get_post_meta( $object->ID, 'max_passengers', true );
	$distance = get_post_meta( $object->ID, 'total_distance', true );
	$longest = get_post_meta( $object->ID, 'longest_leg', true );
	?>
    <div class="ij-journey-legs">
		<?php	
        foreach($legs as $leg){
            $l = ij_get_journey_leg($leg);
            $orig = ij_get_airport($l->start_airport_id);
            $dest = ij_get_airport($l->end_airport_id);
            $date = ij_nice_date($l->outbound_date);
            ?>
            <div class="ij-journey-leg">
                <span class="ij-outbound-date"><?php echo $date; ?></span> <span class="ij-origin"><?php echo $orig; ?></span> <span class="ij-arrow">>>></span> <span class="ij-destination"><?php echo $dest; ?></span>
            </div>
            <?php					
        }
        ?>
    </div>
    <?php
}

add_action("manage_posts_custom_column",  "requests_columns");
add_filter('manage_edit-flight-requests_columns' , 'add_request_columns');
 
function add_request_columns($columns) {
   $columns = array(
		'cb' => '<input type="checkbox" />',
		'user' => 'Added By',
		'author' => 'Assigned To',
		'flight-status' => 'Status',
		'legs' => 'Legs',
		'pax' => 'Pax',
		'start' => 'From',
		'end' => 'To',
		'date' => 'Date',
   );
   return $columns;
}
 
function requests_columns( $column ) {
	global $post;
    switch ( $column ) {
		case 'user' :
			$details = get_post_meta($post->ID, 'user_details', true);
			echo !empty($details[0]['name']) ? $details[0]['name'] . " - " : "";
			echo "<a href='".get_site_url() . "/wp-admin/post.php?post=" . $post->ID."&action=edit'><strong>View Request</strong></a>";
		break;
		
		case 'flight-status' :
			$status = get_post_meta($post->ID, 'flight_status', true);
			echo empty($status) ? "Pending" : $status;
		break;
		
		case 'legs' :
			$legs = get_post_meta($post->ID, 'journey_id' );
			echo sizeof($legs);
		break;
		
		case 'pax' :
			$pax = get_post_meta($post->ID, 'max_passengers', true);
			echo $pax;
		break;
		
		case 'start' :
			$legs = get_post_meta($post->ID, 'journey_id' );
			$leg = $legs[0];
			$l = ij_get_journey_leg($leg);
			echo ij_get_airport($l->start_airport_id);
		break;
		
		case 'end' :
			$legs = get_post_meta($post->ID, 'journey_id' );
			$leg = $legs[0];
			$l = ij_get_journey_leg($leg);
			echo ij_get_airport($l->end_airport_id);
		break;
    }
}

function my_admin_notice() {
	if(current_user_can('edit_others_posts')){
		return;
		?>
		<div class="updated">
			<p><?php print_r(ij_ij_last_login()); ?> You have <strong>(x) flight requests</strong> since you last logged in. <a href="<?php echo admin_url('edit.php?post_type=flight-requests'); ?>'">Go to Flight Requests &rarr;</a></p>
		</div>
		<?php
	}
}

add_action( 'admin_notices', 'my_admin_notice' );

class InstajetAdmin extends RDPlugin_Admin {

  /**
   * Constructor
   * 
   * Calls the parent constructor for setup
   * 
   * @param object $plugin
   */
  public function __construct($plugin) {
	  	
    $this->notices = array(
        /*array(
            'class' => 'updated',
            'message' => 'You have <strong>(x) flight requests</strong> since you last logged in. <a href="' . admin_url('edit.php?post_type=flight-requests') . '">Go to Flight Requests &rarr;</a>',
            'capability' => 'edit_others_posts'
        ),*/
        /*array(
            'class' => 'error',
            'message' => 'Instajet has encountered a problem. Your theme is not compatible with Instajet. <a href="' . admin_url('edit.php?post_type=flight-requests') . '">Go to Theme Options →</a>',
            'capability' => 'manage_options'
        )*/
    );
    $this->sections = array(
        array(
            'slug' => 'general_settings',
            'title_i18n' => __('Instajet Settings', 'instajet'),
            'description_i18n' => __('Manage the appearance and behaviour of Instajet on your site.', 'instajet'),
            'callback' => array($this, 'section_description'),
            'fields' => array(
				array(
					'slug' => 'instajet_license_key',
					'label_i18n' => 'API Key',
					'type' => 'license',
					'description' => __('You can run the plugin without an API key, but your request rate will be limited', 'instajet'),
					'default' => '',
				),
				array(
					'slug' => 'theme',
					'label_i18n' => 'Theme',
					'type' => 'select',
					'options' => array(
						'Instajet' => '',
						'Bootstrap' => 'bootstrap',
						'Skeleton Theme' => 'skeleton',
					),
					'description' => __('Choose "skeleton" to remove all styling', 'instajet'),
					'default' => '',
				),
				array(
					'slug' => 'instajet_admin_email',
					'label_i18n' => __('Email "From" Address', 'instajet'),
					'type' => 'text',
					'default' => get_option('admin_email'),
				),	
				array(
					'slug' => 'instajet_emails_new_cust',
					'label_i18n' => __('New Booking (Customer)', 'instajet'),
					'type' => 'select',
					'options' => array(
						'Send Email' => '1',
						'Don\'t Send Email' => '0'
					),
					'description' => __('Sends a confirmation to customers when a request is submitted', 'instajet'),
					'default' => 1,
				),	
				array(
					'slug' => 'instajet_emails_new_admin',
					'label_i18n' => __('New Booking (Admin)', 'instajet'),
					'type' => 'select',
					'options' => array(
						'Send Email' => '1',
						'Don\'t Send Email' => '0'
					),
					'description' => __('Sends a notification to the admin email when a request is submitted', 'instajet'),
					'default' => 1,
				),	
				array(
					'slug' => 'instajet_emails_update',
					'label_i18n' => __('Status Update', 'instajet'),
					'type' => 'select',
					'options' => array(
						'Send Email' => '1',
						'Don\'t Send Email' => '0'
					),
					'description' => __('Sends a notification to the customer when a request is updated', 'instajet'),
					'default' => 1,
				),
				array(
					'slug' => 'instajet_currency',
					'label_i18n' => __('Instajet Currency', 'instajet'),
					'type' => 'select',
					'options' => array(
						'Dollar' => '2',
						'Pound' => '1',
						'Euro' => '0'
					),
					'description' => __('Change the currency symbol displayed on your site', 'instajet'),
					'default' => 1,
				),	
				array(
					'slug' => 'instajet_dashboard',
					'label_i18n' => __('Dashboard Widget', 'instajet'),
					'type' => 'select',
					'options' => array(
						'Show' => 1,
						'Hide' => 0,
					),
					'description' => __('Show or hide the Instajet widget on the dashboard (always available on the <a href="'.get_admin_url( null, 'edit.php?post_type=flight-requests&page=statistics' ).'">Statistics</a> page)', 'instajet'),
					'default' => 1,
				),							
				/*array(
					'slug' => 'api-cache',
					'label_i18n' => __('Cache API Requests (seconds)', 'instajet'),
					'type' => 'text'
				),*/
            )
        ),
    );
    $this->help = array(
    );

    parent::__construct($plugin);
    add_action('admin_notices', array($this, 'dashboard'), 20);
    // add_action('init', array($this, 'includes'));
    // add_action('current_screen', array($this, 'conditional_includes'));
    // add_action('admin_init', array($this, 'ij_settings_update'));
    add_action('load-' . $this->hook, array($this, 'contextual_help'));
  }

  /**
   * Add links to admin menu
   */
  public function admin_menu() {
    $this->hook = add_options_page(__('Instajet Settings', 'instajet'), 'Instajet', 'manage_options', 'instajet_options', array($this, 'settings_page'));
  }

  // would this all be better somewhere else??
  /*public function ij_settings_update() {
	// not in use

    add_settings_section('section-one', 'Appearance Settings', array($this, 'section_one_callback'), 'instajet');
    add_settings_section('section-two', 'General Settings', array($this, 'section_two_callback'), 'instajet');

    register_setting('ij-customisation', 'ij-setting-primary-colour');
    add_settings_field('primary-colour', 'Primary Colour', array($this, 'primary_colour_callback'), 'instajet', 'section-one');

    register_setting('ij-customisation', 'ij-setting-theme-shade');
    add_settings_field('theme-shade', 'Theme Shade', array($this, 'theme_shade_callback'), 'instajet', 'section-one');

    register_setting('ij-customisation', 'ij-setting-plugin-currency');
    add_settings_field('plugin-currency', 'Currency', array($this, 'plugin_currency_callback'), 'instajet', 'section-two');
  }

  public function section_one_callback() {
    echo '<p>Adjust the appearance of Instajet on your site.</p>';
  }

  public function section_two_callback() {
    echo '<p>Adjust general Instajet features.</p>';
  }

  public function primary_colour_callback() {
    $setting = esc_attr(get_option('ij-setting-primary-colour'));
    echo "<input type='text' name='ij-setting-primary-colour' value='$setting' />";
  }

  public function plugin_currency_callback() {
    $setting = esc_attr(get_option('ij-setting-plugin-currency'));
    // don't echo or return an include as them dodgy 1's turn up everywhere mike
    // - learn how include works, what it returns etc
    include( 'ij-admin-currency-list.php' );
  }

  public function includes() {
    // admin logic
    include_once( 'ij-helper-functions.php' );

    // classes we need if not ajax req.
    if (!ij_is_ajax()) {
      // include('ij-admin-menus.php');
    }
  }

  public function conditional_includes() {
    $screen = get_current_screen();
    switch ($screen->id) {
      case 'dashboard' :
		//include('ij-admin-dashboard.php');
        break;
    }
  }*/

  /**
   * Output JS and CSS for admin pages
   */
  public function admin_scripts() {
    wp_enqueue_script('chartjs', $this->plugin->plugin_url . 'assets/js/Chart.min.js', array('jquery'), '1.0.0', false);
    wp_enqueue_script('select', $this->plugin->plugin_url . 'assets/js/select2.min.js', array('jquery'), '1.0.0', false);
    wp_enqueue_style('select', $this->plugin->plugin_url . 'assets/css/select2.css');
    //wp_enqueue_style('instajet_font', $this->plugin->plugin_url . 'assets/css/instajet_font.css');
    wp_enqueue_style('instajet_admin', $this->plugin->plugin_url . 'assets/css/ij-admin.css');
	wp_enqueue_style("fontawesome", 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
  }

  /**
   * Outputs the Statistics dashboard widget
   * 
   * Hooks to welcome_notice and closes the Welcome div
   */
  public function dashboard() {
    global $pagenow;
	//return;
    if ($pagenow !== 'index.php' && $pagenow !=='edit.php') return;
	
	if($pagenow =='edit.php' && $_GET['page']!=="statistics") return;
	
	$options = get_option('instajet_options');
	//print_r($options);
	if($options['instajet_dashboard']!=0 && empty($options['instajet_dashboard'])){
		$options['instajet_dashboard'] = 1;
		update_option( 'instajet_options', $options );
	}
	
	if($options['instajet_dashboard']==0 && $pagenow !=='edit.php') return;
    
	if($_GET['page']=="statistics"){
	?>
    <div class="wrap">
    <?php
	}
	?>    
    <div id="statistics" class="notice">
      <h1 class="icon-stats">Statistics</h1>
      <div class="tabContent" id="content1">
        <div class="panel small">
          <div class="panelinner">
            <h3>Flight Requests</h3>
            <h4><?php echo $requests['total'] = ij_total_flight_requests(); ?></h4>
            <h5><?php echo $requests['recent'] = ij_total_flight_requests(date('Y-m-d 00:00:00', strtotime('-1 month'))); ?></h5>	
            <p>last 30 days</p>		
          </div>	
        </div><div class="panel small">
          <div class="panelinner">
            <h3>Flight Bookings</h3>
            <h4><?php echo ij_total_flight_requests(null,null,"Booked"); ?></h4>
            <h5><?php echo ij_total_flight_requests(date('Y-m-d 00:00:00', strtotime('-1 month')),null,"Booked"); ?></h5>		
            <p>last 30 days</p>					
          </div>
        </div><div class="panel small">
          <div class="panelinner">
            <h3>Total Quoted</h3>
            <h4><?php echo ij_currency() . number_format($quoted['total'] = ij_total_quoted()); ?></h4>
            <h5><?php echo ij_currency() . number_format($quoted['recent'] = ij_total_quoted(date('Y-m-d 00:00:00', strtotime('-1 month')))); ?><!-- <span class="up">7%</span>--></h5>
            <p>last 30 days</p>	
          </div>
        </div><div class="panel small">
          <div class="panelinner">
            <h3>Average Quote</h3>
            <h4><?php echo ij_currency() . number_format(ij_average_quoted()); ?></h4>
            <h5><?php echo ij_currency() . number_format(ij_average_quoted(date('Y-m-d 00:00:00', strtotime('-1 month')))); ?><!--<span class="down">24%</span>--></h5>
            <p>last 30 days</p>	
          </div>
        </div>
      </div>
    </div>
    <div class="notice" style="padding-bottom:20px;">
          <?php $this->recent_searches(); ?>
    </div>
    <?php
	if($_GET['page']=="statistics"){
	?>
    </div>
    <?php
	} 
  }

  /**
   * Recent Searches Widget
   */
  public function recent_searches() {
    ?>
    <h1 class="icon-bars">Recent Requests vs Bookings</h1>

    <!--<select name="search_status">
      <option selected="selected" value="Quote Requested">Quote Requested</option>
    </select>-->

    <?php $this->do_chart(); ?>

    <table class="wp-list-table widefat fixed posts" cellspacing="0">
      <thead>
        <tr>
          <th scope="col" style="width:3em;"></th>
          <th scope="col" id="origin">From</th>
          <th scope="col" id="destination">To</th>
          <th scope="col" id="legs" style="width:3em;">Legs</th>
          <th scope="col" id="pax" style="width:3em;">PAX</th>
          <th scope="col" id="flight_date">Flight Date</th>
          <th scope="col" id="search_date">Date of Search</a>
          </th>
        </tr>
      </thead>
      
      	<?php
		$args = array(
			'post_type' => 'flight-requests',
			'posts_per_page' => $_GET['page']=="statistics" ? 20 : 5,
		);
		
		if(!current_user_can("manage_options")){
			$current_user = wp_get_current_user();
			$user_id = $current_user->ID;
			$args['author'] = $user_id;
		}
		
		$the_query = new WP_Query($args);
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$flight = get_the_id();
			$legs = get_post_meta( $flight, 'journey_id' );
			?>
			<tr valign="top">
                <td><a href="<?php echo get_site_url(); ?>/wp-admin/post.php?post=<?php echo $flight; ?>&action=edit">#<?php echo $flight; ?></a></td>
				<td><?php
                $leg = $legs[0];
				$l = ij_get_journey_leg($leg);
				echo $orig = ij_get_airport($l->start_airport_id);
				$date = $l->outbound_date;
				?></td>
				<td><?php
				echo $dest = ij_get_airport($l->end_airport_id);
				?></td>
                <td><?php echo sizeof($legs); ?></td>
                <td><?php echo get_post_meta($flight, 'max_passengers', true); ?></td>
				<td><?php echo date('F j, Y',strtotime($date)); ?></td>
				<td><?php echo the_time('F j, Y - H:i'); ?></td>
			</tr>
			<?php 
			}
		}
		wp_reset_postdata();
		?>
    </table>

    <?php
  }

  /**
   * Outputs the chart.js charts on the dashboard
   * 
   * @param string $id
   */
  public function do_chart($id = null, $user_id = -1) {
	global $start_date;
	global $end_date;
    $months = $requests = $bookings = array();
	
	$args = array(
        'post_type' => 'flight-requests',
        'posts_per_page' => -1,
    );
	
	if(!current_user_can("manage_options")){
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		$args['author'] = $user_id;
	}
	
	$args2 = $args;
	$args2['meta_query'] = array(
		array(
			'key' => 'flight-status',
			'value' => 'Booked',
			'compare' => '='
		)
	);
	
    for ($i = 9; $i > 1; $i--) {
		$j = $i - 1;
		$start_date = date('Y-m-1', strtotime("-$j month"));
		$end_date = date('Y-m-t', strtotime("-$j month"));
		
		$args['date_query'] = $args2['date_query'] = array('before' => $end_date, 'after' => $start_date, 'inclusive' => true);
		
		$flights = new WP_Query($args);
		array_push($requests,$flights->found_posts);
		
		$flights = new WP_Query($args2);
		array_push($bookings,$flights->found_posts);

      	array_push($months, date('M', strtotime("-$j month")));
    }
	
	$start_date = date('Y-m-1');
	$end_date = date('Y-m-t');
	
	$args['date_query'] = $args2['date_query'] = array('before' => $end_date, 'after' => $start_date, 'inclusive' => true);
	
	$flights = new WP_Query($args);
	array_push($requests,$flights->found_posts);
	
	$flights = new WP_Query($args2);
	array_push($bookings,$flights->found_posts);
	
	array_push($months,date('M'));

    $selector = '#menu-dashboard a';
    ?>
    <canvas id="fr-graph" width="2000" height="200"></canvas>

    <script>	
		var baseColor = "rgb(40,60,80)";
		//console.log(baseColor);
		var fillColor = baseColor.replace(')', ', 0.6)').replace('rgb', 'rgba');
		var strokeColor = baseColor.replace(')', ', 0.8)').replace('rgb', 'rgba');
		var pointColor = baseColor.replace(')', ', 0.8)').replace('rgb', 'rgba');
		var fillColor2 = baseColor.replace(')', ', 0.1)').replace('rgb', 'rgba');
		var strokeColor2 = baseColor.replace(')', ', 0.3)').replace('rgb', 'rgba');
		var pointColor2 = baseColor.replace(')', ', 0.6)').replace('rgb', 'rgba');
		var lineChartData = {
		  labels: ["<?php echo implode('","',$months); ?>"],
		  datasets: [
			{
			  fillColor: fillColor2,
			  strokeColor: strokeColor2,
			  pointColor: pointColor2,
			  pointStrokeColor: "#fff",
			  data: [<?php echo implode(",",$requests); ?>]
			},
			{
			  fillColor: fillColor,
			  strokeColor: strokeColor,
			  pointColor: pointColor,
			  pointStrokeColor: "#fff",
			  data: [<?php echo implode(",",$bookings); ?>]
			}
		  ]
		}
		lineChartData.datasets[1].fillColor = fillColor;
		//console.log(lineChartData);
		
		setTimeout(function(){
			// work out proper canvas size (to stop rendering a blurred graph)
			var canvas = jQuery("canvas#fr-graph");
			canvas.attr({"width":canvas.outerWidth()});
			//console.log(canvas.outerWidth());
			if(canvas.length>0){
				var myLine = new Chart(canvas[0].getContext("2d")).Line(lineChartData, {responsive: true});
			} else {
				console.log("Canvas not found");	
			}
		}, 200);
		
    </script>
    <?php
  }

  /**
   * Hook to the WP Theme Customizer
   * @param obj $wp_customize
   */
  public function customize_register($wp_customize) {
    $wp_customize->add_section('instajet-customize', array(
        'title' => 'Instajet',
        'priority' => 30
    ));
  }

}

