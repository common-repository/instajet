<?php

// act as a singleton connection for each req/resp cycle
if (!class_exists('IJ_Query')) :
	
class IJ_Query {

    public $query_vars = array();

    public $meta_query = '';


    public function __construct()
    {
        if ( ! is_admin() ) {

            add_action('init', array( $this, 'get_errors') );
            add_filter('query_vars', array( $this, 'add_query_vars' ) );

        }
        $this->init_query_vars();
    }

    public function init_query_vars()
    {


    }


}

endif;