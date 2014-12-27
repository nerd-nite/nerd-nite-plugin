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
    }

    /**
     * Load the widget code
     */
    public static function widget() {
        require_once( 'dashboard/widget.php' );
    }

    /**
     * Load widget config code.
     *
     * This is what will display when an admin clicks
     */
    public static function config() {
        require_once( 'dashboard/widget-config.php' );
    }
}