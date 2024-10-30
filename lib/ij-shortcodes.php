<?php
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Outputs the shortcodes for the WP frontend of the plugin
 */
class InstaJetShortcodes {

	/**
	 * Constructor
	 * 
	 * Hooks to init to register the new shortcodes
	 */
	public function __construct() {
		add_action('init', array($this, 'registerShortcodes'));
	}

	/**
	 * Register each shortcode
	 */
	public function registerShortcodes() {
		add_shortcode('search-form', array($this, 'searchFormContent'));
		add_shortcode('search-results', array($this, 'searchResultsContent'));
		add_shortcode('flight-status', array($this, 'flightStatusContent'));
		//add_shortcode('search-results-form', array($this, 'searchResultsForm'));
	}

	public function searchFormContent($atts) {
		$api = InstaJet::get_instance()->api;
		
		/* if args isnt empty, we look for the first parameter, if present we run action based on that */
		extract(shortcode_atts(array('parentclass' => 'ij-wrap'), $atts));
		?>
		<div class="<?php echo $parentclass; ?>">
			<div class="ig-form">
				<form method="get" id="search" action="./">
					<?php //wp_nonce_field('ij_request_flight_suggestions', 'ij_get_flights'); ?>
					<h2>Search for a Flight</h2>
					<p class="ij-form-row">
						<label for="origin">From:</label>
						<input class="validate" type="text" id="origin" name="orig" placeholder="Origin airport" value="<?php echo $_GET['orig']; ?>">
					</p>
					<p class="ij-form-row">
						<label for="destination">To:</label>
						<input class="validate" type="text" id="destination" name="dest" placeholder="Destination airport" value="<?php echo $_GET['dest']; ?>">
					</p>
					<p class="ij-form-row">
						<label for="pax">Passengers:</label>
						<input class="validate" type="number" id="pax" name="pax" placeholder="Number of passengers" value="<?php echo $_GET['pax']; ?>">
					</p>
					<p class="ij-form-row half">
						<label for="outbound-date">Departure:</label>
						<input type="text" class="datepicker" id="outbound-date" name="dep" autocomplete="off" placeholder="Departure date" value="<?php echo $_GET['dep']; ?>">
					</p>
					<p class="ij-form-row half last">					
						<label for="return-date">Return:</label>
						<input type="text" class="datepicker" id="return-date" name="ret" autocomplete="off" placeholder="Return date (optional)" value="<?php echo $_GET['ret']; ?>">
					</p>
					<button type="submit">Go</button>
				</form>
			</div>
		</div>
		<?php
	}

	public function searchResultsContent($atts) {

		/* if args isnt empty, we look for the first parameter, if present we run action based on that */
		extract(shortcode_atts(array('parentclass' => 'ij-wrap'), $atts));
		?>
		<div class="<?php echo $parentclass; ?>" id="ij-results">
        	<?php 
			// there is an aircraft capable
			if(sizeof($_SESSION['journey']->aircraft)>0){
			?>
			<h2>Flight Estimates:</h2>
            <?php
			foreach($_SESSION['journey']->aircraft as $category => $data){
			//print_r($data);
			
			$term = get_term_by("name", $category, "aircrafttypes");
			$desc = $term->description
			?>
			<div class="pricewrap">
            	<?php
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($data->aircraft[0]->id), 'medium' );
				$url = $thumb['0']; 
				?>
				<div class="price-guide">
					<div class="aircraft_meta">
                    	<div class="aircraft-image" style="background-image:url('<?php echo $url; ?>');"></div>
						<h3><?php echo $category; ?></h3>
                        <p class="pricerevealed">From: <?php echo ij_currency();?><?php echo number_format($data->min->price); ?> <i class="fi-clock"></i><?php echo $data->min->duration; ?> <small>(total flight time)</small></p>
						<p class="jet_description"><?php echo $desc; ?></strong></p>
						<a href="#" class="ij-aircraft-explore" data-text-swap="Hide Aircraft">Explore Aircraft</a>
					</div>

					<div class="aircraft_selection">

						<h4>Aircraft in this category</h4>

						<ul class="aircraft_list">
                        	<?php 
							foreach($data->aircraft as $aircraft){ 
							?>
							<li>
								<div class="details">
									<h5><a><?php echo $aircraft->name; ?></a> <small>(<?php echo $aircraft->duration; ?>)</small></h5>
									<dl data-ref="<?php echo $aircraft->id; ?>"> 	
										<dt>Seats:</dt>
										<dd><?php echo $aircraft->seats; ?></dd>
										<dt>Speed:</dt>
										<dd><?php echo $aircraft->speed; ?> kts</dd>
										<dt>Range:</dt>
										<dd><?php echo $aircraft->range; ?> nm</dd>
                                        <dt>Approximate cost:</dt>
										<dd>
                                        	<span id="euroAircraftPrice_02" style="display:none">€ </span>
											<span id="poundAircraftPrice_02" style="display:inline">£<?php echo $aircraft->price; ?> </span>
											<span id="dollarAircraftPrice_02" style="display:none">$ </span>
										</dd>
                                        <dd>
                                        <a><label>Add to quote <input data-class="<?php echo $category; ?>" data-jet="<?php echo $aircraft->name; ?>" data-id="<?php echo $aircraft->id; ?>" type="checkbox" /></label></a>
                                        </dd>
									</dl>
								</div>
							</li>
							<?php
							}
							?>
						</ul>
					</div>
				</div>	
			</div>
            <?php
			}
			?>
			<div class="book">
				<button class="ij-book-jet">Book Flight</button>
			</div>
            <?php
			} else {
				// there is not an aircraft capable
				
				
			}
			?>
		</div>
		<?php
	}
	
	public function flightStatusContent($atts) {
		?>
        <div id="ij-flight-status" class="ij-wrap">
        <?php				
		$flight = get_the_ID();
		
		if(ij_flight_exists($flight)){
			
			$jets = get_post_meta( $flight, 'requested_jets', true );		
			$legs = get_post_meta( $flight, 'journey_id' );
			$pax = get_post_meta( $flight, 'max_passengers', true );
			$distance = get_post_meta( $flight, 'total_distance', true );
			$longest = get_post_meta( $flight, 'longest_leg', true );
			$status = get_post_meta( $flight, 'flight_status', true );
			
			?>
			<div class="ij-current-status">
				<span class="<?php echo sanitize_title($status); ?>"><?php echo $status; ?></span>
                <span class="ij-submitted">Submitted on <?php echo get_post_time("l, F j, Y", false, $flight); ?></span>
			</div>
			<?php	
			if(sizeof($legs)>0){
			?>
            <h2>Journey Details</h2>
			<div class="ij-journey-legs">
				<?php	
				foreach($legs as $leg){
					$l = ij_get_journey_leg($leg);
					$orig = ij_get_airport($l->start_airport_id);
					$dest = ij_get_airport($l->end_airport_id);
					$date = ij_nice_date($l->outbound_date);
					?>
                    <div class="ij-journey-leg">
                    	<span class="ij-outbound-date"><?php echo $date; ?></span> <span class="ij-origin"><?php echo $orig; ?></span> to <span class="ij-destination"><?php echo $dest; ?></span>
                    </div>
                    <?php					
				}
				?>
			</div>
			<?php
			}
				
			if(!empty($jets[0])){
			?>
			<h2>Requested Jets</h2>
			<ul id="ij-requested-jets">
				<?php
				foreach($jets as $jet){ 
					/*if(current_user_can('edit_others_posts')){
					?>
					<p><a target="_blank" href="<?php echo get_site_url() . "/wp-admin/post.php?post=".$jet."&action=edit"; ?>"><?php echo get_the_title($jet); ?></a></p>	
					<?php
					
					} else { */?>
					<li>
					<?php echo get_the_title($jet); ?>
					</li>	
					<?php
					//}
				}
				?>
			</ul>    
			<?php
			}
			?>
			</div>
			<?php
		} else {
			?>
            <div class="ij-no-flight">
				Sorry - it doesn't look like that flight reference exists!	
            </div>
            <?php
		}
	}

}

return new InstaJetShortcodes();