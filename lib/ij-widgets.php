<?php
/**
 *
 * @author 		Instajet
 * @category 	Widgets
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// all of my what?
add_action('widgets_init',create_function('', 'return register_widget("EditFlightWidget");'));

class EditFlightWidget extends WP_Widget
{

    public function __Construct()
    {

        parent::__construct(
            'EditFlightWidget', //base id
            __('Flight Editor', 'text_domain'),
            array('description' => __('The InstaJet flight editor', 'text_domain'),) // args
        );

    }

    public function widget($args, $instance)
    {


        if (is_page('search results shortcode')) {

            echo $args['before_widget'];

            echo 'lol';

            echo $args['after_widget'];

        };

    }


    public function form($instance)
    {

    }


    public function update($new_instance, $old_instance)
    {

    }

}


