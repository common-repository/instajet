<?php
/**
 * Created by IntelliJ IDEA.
 * User: jck
 * Date: 22/03/2014
 * Time: 01:53
 */
class IJ_Geocode {
	
    // assume curl is not active until we work out whether it is or not!
    public $curl = false;
    private $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&types[]=airport&address=";
	public $errors = array();
		
		/**
		 * Constructor
		 * 
		 * Checks for availability of cURL
		 */
    public function __construct(){
        $this->curl = function_exists('curl_version') ? true : false;
    }

    /**
		 * Geocodes an address
		 * 
		 * @param string $address
		 * @return string|boolean 
		 */
    public function locate($address){
        $url = $this->url . urlencode($address);
        $resp = self::curl_file_get_contents($url);
		return $resp;
		
		// redundant vv
        if($resp['status']=='OK'){
            return $resp['results'][0]['geometry']['location'];
        } else {
            // insert error here instead of below in the exception - so we can keep track of if we was not successful on request and do checks like (! $geocode->hasErrors() )
            return $resp['status'];
        }
    }
		
		/**
		 * Gets the response from the geocoding service
		 * 
		 * @param string $url
		 * @return array|boolean
		 */
    private function curl_file_get_contents($url){       
        try {
            if( $this->curl ){
                $c = curl_init();
                curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($c, CURLOPT_URL, $url);
                $contents = curl_exec($c);
                curl_close($c); 
            } else {
                $contents = file_get_contents($url);
            }

        } catch ( Exception $e ){
            $this->errors[] = $e->getMessage();
        } 

        return ( isset( $contents ) && strlen( $contents ) > 0) ? json_decode($contents, true) : false;
    }

		/**
		 * Checks for the existence of errors
		 * 
		 * @return boolean
		 */
    public function has_errors(){
        return empty($this->errors) ? true : false;
    }

}
