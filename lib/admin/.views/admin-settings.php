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

            console.log(id);	

		});

});


jQuery(document).ready(function() { jQuery("#curr_select").select2(); });
</script>
<h2>Instajet Options</h2>
<div class="wrap">

		<?php
			if ( isset($_GET['updated']) && 'true' == esc_attr( $_GET['updated'] ) ) echo '<div class="updated" ><p>Theme Settings updated.</p></div>';
			if ( isset ( $_GET['tab'] ) ) ij_admin_tabs($_GET['tab']); else ij_admin_tabs('homepage');
		?>

<div class="alertwrapper">
<div class="alert">
	<p>You have <strong>15 new flight searches, 4 flight bookings</strong> since you last logged in. <a href="#">Go to Flight Requests &rarr;</a></p>
</div>
</div>



		<div class="stats stats-user panelwrap">
				<h1 class="icon-stats">Statistics</h1>
				<div class="left">
					<ul class="tabs">
						<span class='mover'></span>					
						<li class="active">
							<a href="#content1">
								Your Flight Stats
							</a>
						</li>
						<li>						
							<a href="#content2">
								Total Flight Stats
							</a>
						</li>						
					</ul>
				</div>
	
				<div class="tabContent" id="content1">
					<div class="panel small">
						<div class="panelinner">
						<h3>Flight Searches</h3>
						<h4>67</h4>
						<h5>5</h5>	
						<p>last 30 days</p>		
						</div>	
					</div><div class="panel small">
						<div class="panelinner">
						<h3>Flight Bookings</h3>
						<h4>23</h4>
						<h5>1</h5>	
						<p>last 30 days</p>					
						</div>
					</div><div class="panel small">
						<div class="panelinner">
						<h3>Total Quoted</h3>
						<h4>$45678</h4>
						<h5>$3456 <span class="up">7%</span></h5>
						<p>last 30 days</p>	
						</div>
					</div><div class="panel small">
						<div class="panelinner">
						<h3>Average Quote</h3>
						<h4>$8943 </h4>
						<h5>$4678 <span class="down">24%</span></h5>
						<p>last 30 days</p>	
						</div>
					</div><div class="panel small">
						<div class="panelinner">
						<h3>Paid Flights</h3>
						<h4>49</h4>
						<h5>2</h5>	
						<p>last 30 days</p>			
						</div>		
					</div><div class="panel small">
						<div class="panelinner">
						<h3>Sales</h3>
						<h4>$29567 </h4>
						<h5>$2345 <span class="down">3%</span></h5>
						<p>last 30 days</p>	
						</div>
					</div>
				</div>
				<div style="display:none" class="tabContent" id="content2">
					<div class="panel small">
						<div class="panelinner">
						<h3>Flight Searches</h3>
						<h4>4147</h4>
						<h5>55</h5>	
						<p>last 30 days</p>			
						</div>
					</div><div class="panel small">
						<div class="panelinner">
						<h3>Flight Bookings</h3>
						<h4>983</h4>
						<h5>157</h5>	
						<p>last 30 days</p>			
						</div>		
					</div><div class="panel small">
						<div class="panelinner">
						<h3>Total Quoted</h3>
						<h4>$1345678</h4>
						<h5>$543456 <span class="up">7%</span></h5>
						<p>last 30 days</p>	
						</div>
					</div><div class="panel small">
						<div class="panelinner">
						<h3>Average Quote</h3>
						<h4>$8763 </h4>
						<h5>$4568 <span class="down">24%</span></h5>
						<p>last 30 days</p>	
						</div>
					</div><div class="panel small">
						<div class="panelinner">
						<h3>Paid Flights</h3>
						<h4>567</h4>
						<h5>78</h5>	
						<p>last 30 days</p>				
						</div>
					</div><div class="panel small">
						<div class="panelinner">
						<h3>Sales</h3>
						<h4>$12429567 </h4>
						<h5>$122345 <span class="down">3%</span></h5>
						<p>last 30 days</p>	
						</div>
					</div>

				</div>

		</div>

		<div class="panelwrap">
			<div class="panel large">
				<div class="panelinner">
				<h1 class="icon-bars">Recent Searches</h1>

				<select name="search_status">
				<option selected="selected" value="Quote Requested">Quote Requested</option>
				</select>	

				<canvas id="first" height="250" width="890"></canvas>

				<script>

					var lineChartData = {
						labels : ["January","February","March","April","May","June","July"],
						datasets : [
							{
								fillColor : "rgba(220,220,220,0.5)",
								strokeColor : "rgba(220,220,220,1)",
								pointColor : "rgba(220,220,220,1)",
								pointStrokeColor : "#fff",
								data : [65,23,90,45,56,55,40]
							},
							{
								fillColor : "rgba(79, 223, 143, 0.6)",
								strokeColor : "rgba(79, 223, 143, 0.8)",
								pointColor : "rgba(79, 223, 143, 0.8)",
								pointStrokeColor : "#fff",
								data : [28,19,40,19,96,88,100]
							}
						]
						
					}

				var myLine = new Chart(document.getElementById("first").getContext("2d")).Line(lineChartData);
				
				</script>

				<table class="wp-list-table widefat fixed posts" cellspacing="0">
				<thead>
				<tr>
					<th class="manage-column column-cb check-column" style="">
						<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
						<input id="cb-select-all-1" type="checkbox">
					</th>
					<th scope="col" id="origin">From</th>
					<th scope="col" id="destination">To</th>
					<th scope="col" id="flight_date">Flight Date</th>
					<th scope="col" id="pax">PAX</th>
					<th scope="col" id="search_date" class="manage-column column-date sortable asc" style="">
						<a href="?orderby=date&amp;order=desc"><span>Date of Search</span><span class="sorting-indicator"></span></a>
					</th>

				</tr>
				</thead>

				<tfoot>
				<tr>
					<th class="manage-column column-cb check-column" style="">
						<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
						<input id="cb-select-all-1" type="checkbox">
					</th>
					<th scope="col" id="origin">From</th>
					<th scope="col" id="destination">To</th>
					<th scope="col" id="flight_date">Flight Date</th>
					<th scope="col" id="pax">PAX</th>
					<th scope="col" id="search_date" class="manage-column column-date sortable asc" style="">
						<a href="?orderby=date&amp;order=desc"><span>Date of Search</span><span class="sorting-indicator"></span></a>
					</th>
				</tfoot>

					<tr id="" class="alternate" valign="top">
							
							<th scope="row" class="check-column">
								<label class="screen-reader-text" for="cb-select-1">Select Flight One</label>
								<input id="cb-select-1" type="checkbox" name="post[]" value="1">
								<div class="locked-indicator"></div>
							</th>

						<td>Barcelona</td>
						<td>Malaga</td>
						<td>05/06/2014 14:00</td>
						<td>4</td>
						<td>23/05/2014 13:45</td>


					</tr>

					<tr id="" valign="top">
							
							<th scope="row" class="check-column">
								<label class="screen-reader-text" for="cb-select-1">Select Flight One</label>
								<input id="cb-select-1" type="checkbox" name="post[]" value="1">
								<div class="locked-indicator"></div>
							</th>

						<td>Barcelona</td>
						<td>Malaga</td>
						<td>05/06/2014 14:00</td>
						<td>4</td>
						<td>23/05/2014 13:45</td>


					</tr>


					<tr id="" class="alternate" valign="top">
							
							<th scope="row" class="check-column">
								<label class="screen-reader-text" for="cb-select-1">Select Flight One</label>
								<input id="cb-select-1" type="checkbox" name="post[]" value="1">
								<div class="locked-indicator"></div>
							</th>

						<td>Barcelona</td>
						<td>Malaga</td>
						<td>05/06/2014 14:00</td>
						<td>4</td>
						<td>23/05/2014 13:45</td>


					</tr>		
				</table>
				</div>
			</div><div class="panel large">
				<div class="panelinner dark">
				<h1 class="icon-bars">Your Bookings</h1>

				<select name="booking_status">
				<option selected="selected" value="Confirmed Flights">Confirmed Flights</option>
				</select>	

				<canvas id="second" height="250" width="890"></canvas>

				<script>

					var lineChartData = {
						labels : ["January","February","March","April","May","June","July"],
						datasets : [
							{
								fillColor : "rgba(220,220,220,0.5)",
								strokeColor : "rgba(220,220,220,1)",
								pointColor : "rgba(220,220,220,1)",
								pointStrokeColor : "#fff",
								data : [65,59,90,81,56,55,40]
							},
							{
								fillColor : "rgba(255, 255, 255, 0.6)",
								strokeColor : "rgba(255, 255, 255, 0.8)",
								pointColor : "rgba(255, 255, 255, 0.8)",
								pointStrokeColor : "#fff",
								data : [28,48,40,19,96,27,100]
							}
						]
						
					}

				var myLine = new Chart(document.getElementById("second").getContext("2d")).Line(lineChartData);
				
				</script>

				<table class="wp-list-table widefat fixed posts" cellspacing="0">
				<thead>
				<tr>
					<th class="manage-column column-cb check-column" style="">
						<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
						<input id="cb-select-all-1" type="checkbox">
					</th>
					<th scope="col" id="origin">From</th>
					<th scope="col" id="destination">To</th>
					<th scope="col" id="flight_date">Flight Date</th>
					<th scope="col" id="pax">PAX</th>
					<th scope="col" id="search_date" class="manage-column column-date sortable asc" style="">
						<a href="?orderby=date&amp;order=desc"><span>Date of Booking</span><span class="sorting-indicator"></span></a>
					</th>

				</tr>
				</thead>

				<tfoot>
				<tr>
					<th class="manage-column column-cb check-column" style="">
						<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
						<input id="cb-select-all-1" type="checkbox">
					</th>
					<th scope="col" id="origin">From</th>
					<th scope="col" id="destination">To</th>
					<th scope="col" id="flight_date">Flight Date</th>
					<th scope="col" id="pax">PAX</th>
					<th scope="col" id="search_date" class="manage-column column-date sortable asc" style="">
						<a href="?orderby=date&amp;order=desc"><span>Date of Booking</span><span class="sorting-indicator"></span></a>
					</th>
				</tfoot>

					<tr id="" class="alternate" valign="top">
							
							<th scope="row" class="check-column">
								<label class="screen-reader-text" for="cb-select-1">Select Flight One</label>
								<input id="cb-select-1" type="checkbox" name="post[]" value="1">
								<div class="locked-indicator"></div>
							</th>

						<td>Barcelona</td>
						<td>Malaga</td>
						<td>05/06/2014 14:00</td>
						<td>4</td>
						<td>23/05/2014 13:45</td>


					</tr>

					<tr id="" valign="top">
							
							<th scope="row" class="check-column">
								<label class="screen-reader-text" for="cb-select-1">Select Flight One</label>
								<input id="cb-select-1" type="checkbox" name="post[]" value="1">
								<div class="locked-indicator"></div>
							</th>

						<td>Barcelona</td>
						<td>Malaga</td>
						<td>05/06/2014 14:00</td>
						<td>4</td>
						<td>23/05/2014 13:45</td>


					</tr>


					<tr id="" class="alternate" valign="top">
							
							<th scope="row" class="check-column">
								<label class="screen-reader-text" for="cb-select-1">Select Flight One</label>
								<input id="cb-select-1" type="checkbox" name="post[]" value="1">
								<div class="locked-indicator"></div>
							</th>

						<td>Barcelona</td>
						<td>Malaga</td>
						<td>05/06/2014 14:00</td>
						<td>4</td>
						<td>23/05/2014 13:45</td>


					</tr>		
				</table>
				</div>
			</div>
		</div>

		<div class="panelwrap">
			<div class="panel">
			<div class="panelinner tip">

				<h1>InstaJet ProTip #45</h1>

				<p>You can control the pricing that Instajet displays. Click "Aircraft Attributes", select an aircraft, and you can edit the base cost and cost per hour.</p>

			</div>
			</div>

			<div class="panel">
			<div class="panelinner">

				<h1>InstaJet ProTip #45</h1>

				<p>You can control the pricing that Instajet displays. Click "Aircraft Attributes", select an aircraft, and you can edit the base cost and cost per hour.</p>

			</div>
			</div>

			<div class="panel">
			<div class="panelinner">

				<h1>InstaJet ProTip #45</h1>

				<p>You can control the pricing that Instajet displays. Click "Aircraft Attributes", select an aircraft, and you can edit the base cost and cost per hour.</p>

			</div>
			</div>					
			
			<div class="panel medium">
			<div class="panelinner">

				<h1>InstaJet ProTip #45</h1>

				<p>You can control the pricing that Instajet displays. Click "Aircraft Attributes", select an aircraft, and you can edit the base cost and cost per hour.</p>

			</div>
			</div>				
		</div>




</div>