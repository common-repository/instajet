<?php
/**
 * Created by IntelliJ IDEA.
 * User: jck
 * Date: 24/03/2014
 * Time: 14:09
 */
?>

<style>

/* 44749d */

.wrap * {
	box-sizing:border-box;
}

html {
	background: #f5f6f7;
}

.right {
	float:right;
}

.left {
	float:left;
}

.panelwrap {
	width:100%;
	display:inline-block;
}

.panel {
	display: inline-block;
	vertical-align: top;
	margin-bottom:20px;
	padding-right:6px;
	width:20%;
}

.small {
	width:16.6666%;
}

.medium {
	width:39.33334%;
}

.large {
	width:50%;
}

.panel:last-child {
	padding-right:0;
}

.panelinner {
	background:#fff;
	border:1px solid #e3e8ed;
	border-radius:6px;
}

.panel h1 {
	padding:16px 20px;
	display: inline-block;
}

.panel h3 {
	color:#777;
	font-size: 1.3em;
	margin-top:0;

	-webkit-border-top-left-radius: 3px;
	-webkit-border-top-right-radius: 3px;
	-moz-border-radius-topleft: 3px;
	-moz-border-radius-topright: 3px;
	border-top-left-radius: 3px;
	border-top-right-radius: 3px;

	font-weight:300;	
	padding:0.8em 20px;
}

.panel h4 {
	font-weight: 700;
	font-size: 2em;
	margin: 0 0 0.8em;
	padding-left: 20px;	
}

.panel h5 {
	font-weight: 700;
	font-size: 1.6em;
	margin: 0 0 0.8em;
	padding-left: 20px;	
	position: relative;
}

.panel h5 span {
	color:#4FD344;
	font-size:0.6em;
	position: absolute;
	bottom: -4px;
	padding-left: 9px;
}

.panel h5 span:before {
	content:'';
	width:0;
	height:0;
	position: absolute;
	top:-10px;
	left:11px;
}

.panel h5 span.up:before {
	border:5px solid transparent;
	border-bottom:5px solid #4FD344;
}

.panel h5 span.down:before {
	top:-5px;
	border:5px solid transparent;
	border-top:5px solid #EC4055;
}

.panel h5 span.down {
	color:#EC4055;
}

.panel p {
	padding: 0 20px;	
	font-style:italic;
	margin-top:-0.8em;	
	color:#aaa;
}

.dark {
	background: #2B303A;
}

.dark h1 {
	color:#fff;
}

.dark p {
	color:#fff;
	opacity:0.5;
}

.panel select {
	display: inline-block;
	vertical-align: 4px;
	height:40px;
	padding:0 10px;
	border-radius:3px;
}

.dark select {
	background: rgba(255,255,255,0.25);
	color:#fff;
}

.alertwrapper {
	-webkit-animation-duration: 1s;
	animation-duration: 1s;
	-webkit-animation-fill-mode: both;
	animation-fill-mode: both;
	-webkit-animation-name: fadeInDown;
	animation-name: fadeInDown;
}

.alert {
	background: #3fcf7f;
	border-radius: 6px;
	color:#fff;
	padding:0 0.9em;
	display:inline-block;

	margin-bottom:1em;
	-webkit-animation-duration: 1s;
	animation-duration: 1s;
	-webkit-animation-fill-mode: both;
	animation-fill-mode: both;
	-webkit-animation-name: bounce;
	animation-name: bounce;	
	animation-delay:8s;
	-webkit-animation-delay:8s;	
}

.alert p {
	font-size:1.2em;
}

.alert p:before {
	content: "\f339";
	display: inline-block;
	-webkit-font-smoothing: antialiased;
	font: normal 20px/1 'dashicons';
	vertical-align: top;
	margin-right:8px;	
}

.alert a {
	font-weight:600;
	color:#fff;
}

.warning {
	background: #CF3F50;
}

.warning p:before {
	content: "\f348";
}

canvas {
	margin:5px 0 20px;
	width:100%;
	width: 100% !important;
	height: auto !important;
}


.title {
	display: inline-block;
	width:100%;
}

.title h1 {
	display: inline-block;
	margin-right:10px;	
}

.title h1.deselected {
	opacity:0.5;
}

@-webkit-keyframes bounce {
  0%, 20%, 50%, 80%, 100% {
    -webkit-transform: translateY(0);
    transform: translateY(0);
  }

  40% {
    -webkit-transform: translateY(-30px);
    transform: translateY(-30px);
  }

  60% {
    -webkit-transform: translateY(-15px);
    transform: translateY(-15px);
  }
}

@keyframes bounce {
  0%, 20%, 50%, 80%, 100% {
    -webkit-transform: translateY(0);
    -ms-transform: translateY(0);
    transform: translateY(0);
  }

  40% {
    -webkit-transform: translateY(-30px);
    -ms-transform: translateY(-30px);
    transform: translateY(-30px);
  }

  60% {
    -webkit-transform: translateY(-15px);
    -ms-transform: translateY(-15px);
    transform: translateY(-15px);
  }
}

@-webkit-keyframes fadeInDown {
  0% {
    opacity: 0;
    -webkit-transform: translateY(-20px);
    transform: translateY(-20px);
  }

  100% {
    opacity: 1;
    -webkit-transform: translateY(0);
    transform: translateY(0);
  }
}

@keyframes fadeInDown {
  0% {
    opacity: 0;
    -webkit-transform: translateY(-20px);
    -ms-transform: translateY(-20px);
    transform: translateY(-20px);
  }

  100% {
    opacity: 1;
    -webkit-transform: translateY(0);
    -ms-transform: translateY(0);
    transform: translateY(0);
  }
}

table.widefat {
	border:none;
	border-top:1px solid #e3e8ed;
	margin-bottom:10px;
}

.widefat thead,.widefat tfoot {
	background: #fff;
}

.widefat thead th {
	border:none;
}

.widefat tfoot th {
	border:none;

}

.widefat thead th.check-column, .widefat tbody th.check-column, .widefat tfoot th.check-column {
	padding: 20px 0 20px 3px;
}

.widefat thead tr th, .widefat tfoot tr th, h3.dashboard-widget-title, h3.dashboard-widget-title span, h3.dashboard-widget-title small {
	color:#888;
	font-weight:300;
}

th.manage-column a, th.sortable a:hover, th.sortable a:active, th.sortable a:focus {
	color:#777;
}

th .comment-grey-bubble:before {
	color:#777;
}

.widefat th.sortable, .widefat th.sorted {
	padding:8px 10px;
}

#the-list tr td, #the-list tr th {
	box-shadow: inset -1px 0px 0px #ddd,inset 1px 0px 0px #FFF;	
}

#the-list tr:last-child td, #the-list tr:last-child th {
	box-shadow: inset -1px 0px 0px #ddd,inset 1px 0px 0px #FFF;		
}

.dark .widefat thead,.dark .widefat tfoot,.dark .widefat {
	background: transparent;
	color:rgba(255,255,255,0.5);
}

.dark .widefat {
	border-top:1px solid rgba(255,255,255,0.3);	
}

.dark .alternate, .dark .alt {
	background: rgba(255,255,255,0.1);	
}

.dark .widefat td, .dark .widefat th {
	color:rgba(255,255,255,0.5);
}

.large .fixed .column-date{
	width:23%;
}

.wp-core-ui .button-primary {
	background: #f4c414;
	-webkit-box-shadow: inset 0 -2px 0 rgba(0,0,0,0.15);
	box-shadow: inset 0 -3px 0 rgba(0,0,0,0.15);
	border:none;
	height:46px;
	width: 130px;
	color:#fff;
	font-weight:700;
}

.wp-core-ui .button-primary:hover{
	background: #f4c414;
	-webkit-box-shadow: inset 0 -2px 0 rgba(0,0,0,0.25);
	box-shadow: inset 0 -3px 0 rgba(0,0,0,0.25);	
}

/* tabs */

h2.nav-tab-wrapper, h3.nav-tab-wrapper {
	margin: 20px 0 1.1em -22px;
	background: #babec7;
	padding:0 0 0 22px;
	width:calc(100% + 20px);
	-webkit-border-top-right-radius: 6px;
	-webkit-border-bottom-right-radius: 6px;
	-moz-border-radius-topright: 6px;
	-moz-border-radius-bottomright: 6px;
	border-top-right-radius: 6px;
	border-bottom-right-radius: 6px;	
}

h2 .nav-tab {
	padding:11px 16px;
	-webkit-border-top-left-radius: 3px;
	-webkit-border-top-right-radius: 3px;
	-moz-border-radius-topleft: 3px;
	-moz-border-radius-topright: 3px;
	border-top-left-radius: 3px;
	border-top-right-radius: 3px;	
}

.nav-tab {
	background: transparent;
	color:#687081;
	font-weight:300;
	border:none;	
	margin:10px 0 -1px 0;
}

.nav-tab:hover {
	background: #A8AFBE;
	color:#343842;
}

.nav-tab-active, .nav-tab-active:hover {
	background: #eee;
}

/* Tips */

.tip {
	background: #6D98C2;
	color:#fff;
}

.tip  p {
	color:#fff;
}

.stats {
	background: #fff;
	border:1px solid #e3e8ed;
	border-radius:6px;
	margin-bottom:20px;
}

.stats h1 {
	padding:10px;
	float:left;
}

ul.tabs {
	display:inline-block;
	position: relative;	
	border:1px solid #e3e8ed;
	margin-top:18px;
	margin-left:15px;
	border-radius:3px;
	overflow:hidden;
}

ul.tabs li {
	display: block;
	float: left;
	width:180px;
	z-index: 200;
	position: relative;	
	margin-bottom:0;
}

ul.tabs li a {
	color: #5F636A;
	padding: 10px;
	text-align: center;	
	display:block;
	font-size:1.2em;
	font-weight:700;
	text-decoration: none;
}

ul.tabs li.active a {
	color: #fff;
}

.mover {
	background-color: #5F636A;
	position: absolute;
	border-radius:3px;
	width: 180px;
	height:100%;
	z-index: 190;
	left: 0;
	background-position: bottom left;
	background-repeat: no-repeat;
}

.tabContent {
	padding:10px;
	width: 100%;
	display: inline-block;
}

.tabContent .panel {
	margin-bottom:0;
}

.tabslider {
	width: 5000px;
}

.tabslider ul {
	float: left;
	width: 600px;
	margin: 0px;
	padding: 0px;
	margin-right: 40px;
}


/* Settings forms */

.form-table {
	margin: 0 0 40px 20px;
	width:auto;
}

.form-options {
	padding:16px 0;
}

.form-options p {

}

table.form-table+p.submit, table.form-table+input+p.submit, table.form-table+input+input+p.submit {
	margin-top:20px;
	text-align: right;
}

/* price adjustment page */

input.output {
	border:none;
	padding:0;

-webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,.0);
box-shadow: inset 0 1px 2px rgba(0,0,0,.0);
font-size:1.8em;
}

</style>
<script>
jQuery(function(){
   

        jQuery(".tabs li").click(function(e) {

        	e.preventDefault();

  			var background = jQuery(this).parent().find(".mover");

			jQuery(background).stop().animate({
				left: jQuery(this).position()['left']
			}, {
				duration: 300
			});  	

            jQuery(".tabContent").each(function(){
            	jQuery(this).hide();
            });

            jQuery(".tabs li").each(function(){
            	jQuery(this).removeClass('active');
            });

            jQuery(this).addClass('active');

        	var id = jQuery(this).children('a').attr("href");
            
            jQuery(id).show();

//            console.log(id);

		});

});

jQuery( window ).load(function() {

	function updateFigures()
	{
		var distance=jQuery("#flight_distance").val();
		var speed=jQuery("#cruise_speed").val();
		var costperhour=jQuery("#cost_per_hour").val();
		var basecost=jQuery("#base_cost").val();

		var time=parseInt(distance)/parseInt(speed);
//		console.log(time);
		var hours = Math.floor(time);
		var minutes = Math.round((Number(time)-hours) * 60);

		jQuery('#flight_time').html(hours+':'+minutes);

		var result=((time*parseInt(costperhour)) + parseInt(basecost)).toFixed(2);
		jQuery("#result").html(result);
	}

	jQuery( "input[type='text'].change" ).change(updateFigures);			

	updateFigures();

});

jQuery(document).ready(function() {

	jQuery("#curr_select").select2();
});
</script>
<h2>Instajet Options</h2>
testingshit
<div class="wrap scroll">

		<?php
			if ( isset($_GET['updated']) && 'true' == esc_attr( $_GET['updated'] ) ) echo '<div class="updated" ><p>Theme Settings updated.</p></div>';
			if ( isset ( $_GET['tab'] ) ) ij_admin_tabs($_GET['tab']); else ij_admin_tabs('homepage');
		?>

        <?php
// todo remove this into some kind of seed file to be executed once to set sensible defaults for user??????
// insert a couple of aircraft posts - can be removed afterwards, or at least can be moved into a seed file of some kind?

// Create post object
//        $post = array(
//            'post_title'    => 'Slightly Larger Aircraft',
//            'post_content'  => 'This is the very light aircraft bullshit.',
//            'post_status'   => 'publish',
//            'post_type'     => 'aircraft',
//            'post_author'   => 1, // admin is author
//            'post_category' => '' // fuckknows
//        );
//
//        // Insert the post into the database - get the post_id because we want to add post meta
//        $post_id = wp_insert_post( $post );
//
//        add_post_meta( $post_id, 'aircraft_distance', '900' );
//        add_post_meta( $post_id, 'aircraft_cruisespeed', '540' );
//        add_post_meta( $post_id, 'aircraft_basecost', '900' );
//        add_post_meta( $post_id, 'aircraft_costperhour', '4000' );

        ?>
        <?php
            if( isset($_GET['updated']) ){
                echo 'Update was successful bitches!';
            }
        ?>
		<div class="panelwrap">

			<div class="panel medium">

				<div class="panelinner">

				<h1 class="icon-aircraftposttype">1. Select Aircraft</h1>

						<?php

						$loop = new WP_Query( array( 'post_type' => 'aircraft', 'posts_per_page' => -10 ) ); ?>

						<table class="wp-list-table widefat fixed posts" cellspacing="0">
							<thead>
							<tr>
								<th scope="col" id="title" class="manage-column column-title sortable desc">
									<a href="#"><span>Aircraft</span><span class="sorting-indicator"></span></a>
								</th>
								<th scope="col" id="jetcategory" class="manage-column column-title sortable desc">
									<a href="#"><span>Jet Category</span><span class="sorting-indicator"></span></a>
								</th>
							</tr>
							</thead>

							<tbody id="the-list">
								<?php while ($loop->have_posts()) : $loop->the_post();
								?>

								<tr id="post-<?php the_ID(); ?>" class="post-<?php the_ID(); ?>" valign="top">
									<td class="post-title page-title column-title">
										<strong><a class="row-title ajaxpost" href="#" data-postid="<?php the_ID(); ?>" title="Edit “<?php the_title(); ?>”"><?php the_title(); ?></a></strong>
									</td>
									<td class="categories column-categories"><a href="edit.php?category_name=uncategorised">Uncategorised</a></td>
								</tr>


								<?php endwhile; wp_reset_query();?>

							</tbody>
                            <tfoot>
                            <tr>
                                <th scope="col" class="manage-column column-title sortable desc" style="">
                                    <a href="?orderby=title&amp;order=asc"><span>Aircraft</span><span class="sorting-indicator"></span></a>
                                </th>
                                <th scope="col" id="jetcategory" class="manage-column column-title sortable desc">
                                    <a href="#"><span>Jet Category</span><span class="sorting-indicator"></span></a>
                                </th>
                            </tr>
                            </tfoot>
						</table>	


				</div>

		</div>

		<div class="panel medium">

				<div class="panelinner">
					lolwhut
				</div>
		</div>
	</div>
	<div class="panelwrap">
			<div class="panel medium">
				<div class="panelinner">
				<h1 class="icon-coin">2. Adjust Aircraft Pricing</h1>
				<p>Use this tool to adjust the pricing that InstaJet provides your site visitors.</p>

                    <form action="" method="POST">
                    <?php wp_nonce_field( 'ij_update_aircraft_action', 'ij_update_aircraft' ); ?>
                    <!--  todo - change this hidden production, but not while we are still dev -->
                    <input type="text" id="post_id" name="postid" value="">
                        <table class="form-table">
                            <tbody>
                                <tr><h3>First, enter the distance of the flight, and the cruise speed of the aircraft:</h3></tr>
                                <tr><th scope="row">Distance</th>
                                <td><input type="text" id="flight_distance" name="flight_distance" value="600" class="change distance"></td>
                                </tr>

                                <tr><th scope="row">Cruise Speed</th>
                                <td><input type="text" id="cruise_speed" name="cruise_speed" value="460" class="change speed"></td>
                                </tr>

                                <tr><th scope="row">Flight Time</th>
                                <td><span id="flight_time"></span></td>
                                </tr>

                            </tbody>
                        </table>
                        <table class="form-table">
                            <tbody>
                                <tr><h3>Second, enter the base cost of the aircraft, and its hourly rate:</h3></tr>
                                <tr><th scope="row">Base Cost</th>
                                <td><input type="text" id="base_cost" name="base_cost" value="600" class="change"></td>
                                </tr>

                                <tr><th scope="row">Cost Per Hour</th>
                                <td><input type="text" id="cost_per_hour" name="cost_per_hour" value="3000" class="change"></td>
                                </tr>

                                <tr><th scope="row">Max Passengers</th>
                                    <td><input type="text" id="ij_pax" name="ij_pax" value="3000" class="change"></td>
                                </tr>

                                <tr><th scope="row">Estimate</th>
                                <td><span id="result"></span></td>
                                </tr>

                            </tbody>
                        </table>
                        <button type="submit">Update</button>
                    </form>
					<h3>Check the price!</h3>
				</div>     

			</div>

			<div class="panel medium">
			</div>

		</div>

    <script>

        jQuery(function($){
            $('.ajaxpost').on('click', function(e) {
                e.preventDefault();

                var data = {
                    action: 'get_the_aircraft_info',
                    id: $(this).data('postid')
                }

                $.post( ajaxurl , data, function ( data ) {

                    var data = $.parseJSON( data );
                    // hidden input gets the postid
                    $('#post_id').val(data.postid);
                    // inputs
                    $('#flight_distance').val(data.ij_max_range);
                    $('#cruise_speed').val(data.ij_cruise_speed);
                    $('#base_cost').val(data.ij_bcpf);
                    $('#cost_per_hour').val(data.ij_cph);
                    $('#ij_pax').val(data.ij_pax);
//                    console.log(data.)



                });
            });
        });
    </script>


</div>



