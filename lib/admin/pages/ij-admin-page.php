<?php
/**
 * Created by IntelliJ IDEA.
 * User: jck
 * Date: 24/03/2014
 * Time: 12:43
 *
 * // base class for all other admin pages
 */
if( ! class_exists( 'ij_admin_page' ) ):
class ij_admin_page{

    protected $id = '';
    protected $label = '';

    public function add_settings_page($pages) {
        $pages[ $this->id ] = $this->label;
        return $pages;
    }

    /**
     * Get sections
     *
     * @return array
     */
    public function get_sections() {
        return apply_filters( 'instajet_get_sections_' . $this->id, array() );
    }

    /**
     * Output sections
     */
    public function output_sections() {
        global $current_section;

        $sections = $this->get_sections();

        if ( empty( $sections ) )
            return;

        echo '<ul class="subsubsub">';

        $array_keys = array_keys( $sections );

        foreach ( $sections as $id => $label )
            echo '<li><a href="' . admin_url( 'admin.php?page=ij-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';

        echo '</ul><br class="clear" />';
    }

    /**
     * Output the settings
     */
    public function output() {
        $settings = $this->get_settings();

        ij_admin_settings::output_fields( $settings );
    }

    /**
     * Save settings
     */
    public function save() {
        global $current_section;

        $settings = $this->get_settings();
        ij_admin_settings::save_fields( $settings );

        if ( $current_section )
            do_action( 'instajet_update_options_' . $this->id . '_' . $current_section );
    }

}

endif;

?>