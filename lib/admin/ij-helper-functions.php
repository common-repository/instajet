<?php
/**
 * Created by IntelliJ IDEA.
 * User: jck
 * Date: 24/03/2014
 * Time: 12:25
 * General Admin Functionality
 */
function ij_get_page_id( $page ) {
    
    $page = get_option('instajet_' . $page . 'page_id' );
    return $page ? $page : -1;
}

function ij_create_page( $slug, $option = '', $page_title = '', $page_content = '', $post_parent = 0, $template = '' ) {
    global $wpdb;

    $option_value = get_option( $option );

    if ( $option_value > 0 && get_post( $option_value ) )
        return -1;

    $page_found = null;

    if ( strlen( $page_content ) > 0 ) {
        // Search for an existing page with the specified page content (typically a shortcode)
        $page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_type='page' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
    } else {
        // Search for an existing page with the specified page slug
        $page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_type='page' AND post_name = %s LIMIT 1;", $slug ) );
    }

    if ( $page_found ) {
        if ( ! $option_value )
            update_option( $option, $page_found );

        return $page_found;
    }

    $page_data = array(
        'post_status'       => 'publish',
        'post_type'         => 'page',
        'post_author'       => 1,
        'post_name'         => $slug,
        'post_title'        => $page_title,
        'post_content'      => $page_content,
        'post_parent'       => $post_parent,
        'comment_status'    => 'closed'
    );

    $page_id = wp_insert_post( $page_data );

    if( -1 != $page_id ) {
        if(strlen($template) > 0){
            update_post_meta( $page_id, '_wp_page_template', $template );
        }   
    } // end if

    if( $option )
        update_option($option, $page_id);

    return $page_id;

}
// http://stackoverflow.com/questions/14348470/is-ajax-in-wordpress
function ij_is_ajax()
{
    if( defined('DOING_AJAX') && DOING_AJAX ){
        return true;
    } else {
        return false;
    }
}

function ij_admin_tabs( $current = 'homepage' ) {
    $tabs = array( 'homepage' => 'Home', 'general-settings' => 'General Settings', 'pricing-adjustment' => 'Pricing Adjustment', 'licence' => 'Licence' ); 
    $links = array();
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=instajet&tab=$tab'>$name</a>";
        
    }
    echo '</h2>';
}
