<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('ijAdminMenus') ) :

    class ijAdminMenus {
        public function __construct()
        {
            add_action('admin_menu', array($this, 'admin_menu'), 9);

        }
        public function admin_menu()
        {
            global $menu;
//            $menu[] = array( '', 'read', 'separator-instajet', '', 'wp-menu-separator ' );
            $main_page = add_menu_page('InstaJET', 'InstaJET', 'manage_options', 'instajet', array( $this, 'insta_settings_page' ), '', 55.5);
            add_submenu_page( 'instajet', 'Aircraft Attributes', 'Aircraft Attributes', 'manage_options', 'insta_aircraft', array( $this, 'aircraft_attributes' ) );

        }
        public function insta_settings_page()
        {            

            $tab = isset($_GET['tab']) ? $_GET['tab'] : 'homepage';

            switch($tab){
                case 'homepage' :
                    include_once('ij-admin-settings-page.php');
                    IJ_Admin_Settings::output();                    
                break;
                case 'general-settings' :
                    include_once('ij-admin-general-page.php');
                    IJ_Admin_General::output();                       
                break;
                case 'pricing-adjustment' :
                    include_once('ij-admin-price-adjust-page.php');
                    IJ_Admin_Price_Adjust::output();                       
                break;                
                default :
                    include_once('ij-admin-settings-page.php');
                    IJ_Admin_Settings::output(); 
                break;                  
            }

        }
        
        public function aircraft_attributes() {
          include_once( 'views/admin-aircraftattributes.php' );
        }
        
    }

endif;

return new ijAdminMenus();
