<?php
if( !class_exists('IJ_Database') ) {

    class IJ_Database
    {
        /**
         * This is run from the main plugin folder on init
         */
        static public function install()
        {
			ini_set('max_execution_time', 600);
			set_time_limit(600);
			
            global $wpdb;
			$table_name = $wpdb->prefix . 'legs';
			
			$charset_collate = $wpdb->get_charset_collate();
			// needed for dbDelta()
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

				/**
				 * Journey Legs
				 */
				$table_name = $wpdb->prefix . "legs";
				$sql = "CREATE TABLE $table_name (
				id INTEGER unsigned NOT NULL auto_increment,
				post_id INTEGER NOT NULL,
				outbound_date DATETIME,
				start_airport_id INTEGER NOT NULL,
				end_airport_id INTEGER NOT NULL,
				PRIMARY KEY  (id)
				) $charset_collate;";
	
				dbDelta($sql);
				
			}
			
			$table_name = $wpdb->prefix . 'airports';
			
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
				
				/**
				 * Airports
				 */
				$sql = "CREATE TABLE $table_name (
				id INTEGER unsigned NOT NULL auto_increment,
				name VARCHAR(250) NOT NULL,
				type VARCHAR(100),
				municipality VARCHAR(100),
				iso_country VARCHAR(2),
				continent VARCHAR(2),
				iata_code VARCHAR(4),
				ident VARCHAR(4),
				elevation_ft INTEGER(100),
				latitude_deg FLOAT(12,8),
				longitude_deg FLOAT(12,8),
				gps_code VARCHAR(4),
				PRIMARY KEY (id)
				) $charset_collate;";
				dbDelta($sql);
				//timezone INTEGER NOT NULL, - removed
				
				/**
				 * Check table for row count to see if we need to seed the database with airport information
				 */
				
				//$table_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
				$table_count = 0;
				
				/**
				 * insert the airport data into the airports table
				 */
				if ($table_count == 0) {
					
					/**
					 * Add Database Version as an option for future upgrades to the schemas etc
					 * So we can tell where the client is on our database structures
					 */
					add_option("instajet_db_version", "2.0");
					
					$fields = $keys = array();
					$keys[0] = "id";
					
					foreach ( $wpdb->get_col( "DESC " . $table_name, 0 ) as $column_name ) {
						$fields[] = $column_name;
					}
					
					//$filename = ABSPATH . "/wp-content/plugins/instajet/assets/data/airports.dat";
					$filename = plugins_url( "assets/data/airports.dat" , __FILE__ );
					
					if (($handle = fopen($filename, "r")) !== FALSE) {
						$i=0;
						while (($airportmeta = fgetcsv($handle)) !== FALSE) {
							if($i==0){
								//print_r($airportmeta);
								foreach($airportmeta as $k => $meta){
									if(array_search($meta,$fields)){
										$keys[$k] = $meta;
									}
								}
								//print_r($keys);
							} else {
								$string = "";
								$insert = array();
								foreach($keys as $k => $f){
									$insert[$f] = esc_sql($airportmeta[$k]);
								}	
								//print_r($insert);
								$q[] = $insert;
							}
							$i++;
							
							if($i%10==0){
								// insert 10 records - batching
								
								$query = "INSERT INTO $table_name (".implode(",",$keys).") VALUES ";
																								
								foreach($q as $j => $vals){
									if($j>0) $query .= ",";
									$query .= '("' . implode('","', $vals) . '")';
								}
																
								$wpdb->query($query);
								
								$q = array();
							}
						}
						fclose($handle);
					}
				}				
			}
        }
    }

    return new IJ_Database();

}

