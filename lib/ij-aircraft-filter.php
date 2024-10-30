<?php 

class IJ_Aircraft{

        public $id;
		// Pass in the flight distance and number of passengers for the flight
        public $maxpassengers;
        // Max Range
        public $maxrange;
        // Base Price used during any calculations for this jet
        public $basecost;
        // Cruise Speed
        public $cruise_speed;

        public $cost_per_hour;

        public $journey_cost;

        public $type;
        // the category of jet, makes sense to get it on instantiation
        public $term;

        public function __construct($ac) {

            $tax_terms = get_terms('aircrafttypes');

//            var_dump($ac);die;

            // first we need to retrieve the post ID
            if( isset( $ac->ID ) ){
                // assign the post ID to an identifier within this class
                $this->id = $ac->ID;
                if( absint( $ac->ID ) ){
                     $meta_values = get_post_meta( $ac->ID );

                     $terms = get_the_terms( $ac->ID, 'aircrafttypes' );
                     // always treat the first returned term as the 'be all and end all' of the terms
                     $this->term = $terms[0];

                     $this->name = $ac->post_title;
                     // if the jets uncategorized we omit it because sod that, categorise them properly
                     if( isset($ac->slug) && $ac->slug !== 'Uncategorized'){
                         $this->type = $ac->slug;
                     }
                }
            }
            if( !empty($meta_values) ){
                // todo convert to types
                $this->maxpassengers = $meta_values['_smartmeta_ij_pax'][0];
                $this->maxrange = $meta_values['_smartmeta_ij_max_range'][0];
                $this->basecost = $meta_values['_smartmeta_ij_bcpf'][0];
                $this->cruise_speed = $meta_values['_smartmeta_ij_cruise_speed'][0];
                $this->cost_per_hour = $meta_values['_smartmeta_ij_cph'][0];
            }


        }
        /**
         * CanJourney Will tell us whether we can take this plane for a spin, and wether it's worth actually calculating the
         * flight price / time taken
         * @param $pax
         * @param $distance
         * @return bool
         */
        public function canJourney($pax, $distance)
        {
            $bool = $pax < $this->maxpassengers && $distance < ($this->maxrange * 1.852);
            return $bool;
        }

        /**
        * Will Calculate Trip Time for this Aircraft
        * @param $distance
        * @return array('hours', 'money')
        */
        public function calculateTripTime($distance)
        {
            $flight_duration = ($distance / $this->cruise_speed);

            $quote = round((($flight_duration * $this->cost_per_hour) + $this->basecost ));

            $this->journey_cost = $quote;

            $this->flight_duration = $flight_duration;

//            return array('flight_duration' =>  $flight_duration, 'quote' => $quote);
        }

}
