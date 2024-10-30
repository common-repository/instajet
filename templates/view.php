<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Private Jet Charter Search Results</title>
        
        <?php wp_head(); ?>   
        
        <!--[if lt IE 9]>
          <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script>
        	// This is the URL where we need to make our AJAX calls.
        	// We are making it available to JavaScript as a global variable.
        	var ajaxurl = '<?php echo admin_url('admin-ajax.php')?>';
        </script>
    </head>
    
    <body>

    	<?php Michael_Utilities::get_template_parts( array( 'parts/shared/header' ) ); ?>

		<div id="todo">
		
			<h2>Flights</h2>


			<?php



			$type = 'lj';
			$distance = '1000';
			$FlightType = "single";


			$qarray = get_flight_quote($type, $distance, $FlightType);

			$price = number_format($qarray['quote']); echo $price; ?>


		</div>


        <footer>

        </footer>



        
        <?php wp_footer() ?>


         
    </body>
</html>
