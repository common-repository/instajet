<?php
/**
 * Created by IntelliJ IDEA.
 * User: jck
 * Date: 01/04/2014
 * Time: 15:56
 * the installation scripts
 */

if ( ! defined('ABSPATH' ) ) exit;

if ( ! class_exists( 'IJ_Install' ) ) :

    class IJ_Install{

        public function __construct() {
            // this ensures these pages are always available to us :)
            add_action( 'admin_init', array( $this, 'install_actions' ));
        }

        // main install script
        public function install_actions()
        {
            // create the predefined pages required to run the plugin
            self::create_pages();
        }

        public function create_pages()
        {
            $pages = apply_filters('instajet_create_pages', array(
                'searchpage' => array(
                    'name' => _x( 'searchpage', 'Page slug', 'instajet'),
                    'title' => _x( 'Search Page', 'Page Title', 'instajet'),
                    'content' => '[search-form][search-results]',
                    'template' => ''
                ),
                /*'flightsearchresults' => array(
                    'name' => _x( 'flightsearchresults', 'Page slug', 'instajet'),
                    'title' => _x( 'Search Results', 'Page Title', 'instajet'),
                    'content' => '[search-results-form][search-results]',
                    'template' => ''
                ),*/
                /*'flightbooking' => array(
                    'name' => _x('flightbooking', 'Page slug', 'instajet'),
                    'title' => _x('Flight Booking Form', 'Page Title', 'instajet' ),
                    'content' =>  '[flight-booking-form]',
                    'template' => ''
                ),*/
                'flightstatus' => array(
                    'name' => _x( 'flight-status', 'Page slug', 'instajet'),
                    'title' => _x( 'Flight Status', 'Page Title', 'instajet'),
                    'content' => '',
                    'template' => 'flight-requests.php'
                )
            ));

            foreach($pages as $key => $page){
                ij_create_page( $page['name'], 'instajet_' . $key . 'page_id', $page['title'], $page['content'], $page['template'] );
            }
        }
    }

new IJ_Install();

endif;


