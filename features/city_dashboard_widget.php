<?php
/*
Plugin Name: Nerd Nite Dashboard Widget
Plugin URI: http://nerdnite.com
Description: Dashboard showing the pertinent Nerd Nite City information
Version: 1.0
Author: Dan Rumney
Author URI: http://danrumney.com
License: GPLv2 or later
*/
const GOOGLE_MAPS_V3_API_KEY = 'AIzaSyBYrgCehrswg4kbfjc9IOo-o2SIkmKKAYs';

add_action('wp_dashboard_setup', array('NerdNite_City_Dashboard_Widget','init') );

class NerdNite_City_Dashboard_Widget {

    /**
     * The id of this widget.
     */
    const wid = 'nerdnite_city_dashboard_widget';

    /**
     * Hook to wp_dashboard_setup to add the widget.
     */
    public static function init() {
        //Register the widget...
        wp_add_dashboard_widget(
            self::wid,                                  //A unique slug/ID
            __( 'Nerd Nite City Dashboard Widget', 'nouveau' ),//Visible name for the widget
            array('NerdNite_City_Dashboard_Widget','widget'),      //Callback for the main widget content
            array('NerdNite_City_Dashboard_Widget','config')       //Optional callback for widget configuration content
        );

        wp_register_style('nn-city-dashboard', plugins_url('/dashboard/city-dashboard.css', __FILE__), array());
        wp_register_script('googlemaps', '//maps.googleapis.com/maps/api/js?key=' . GOOGLE_MAPS_V3_API_KEY . '&sensor=false', false, '3');
        wp_register_script('nn-city-dashboard', plugins_url('/dashboard/city-dashboard.js', __FILE__), array('jquery', 'googlemaps'), '2.1');
    }

    /**
     * Load the widget code
     */
    public static function widget() {
        wp_enqueue_style('nn-city-dashboard');
        wp_enqueue_script('nn-city-dashboard');
        require_once( 'dashboard/widget.php' );
    }

    /**
     * Load widget config code.
     *
     * This is what will display when an admin clicks
     */
    public static function config() {
        wp_enqueue_style('nn-city-dashboard');
        wp_enqueue_script('nn-city-dashboard');
        require_once( 'dashboard/widget-config.php' );
    }
}