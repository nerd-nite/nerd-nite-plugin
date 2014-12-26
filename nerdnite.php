<?php
defined('ABSPATH') or die("Direct access not supported!");
/**
 * Plugin Name: nerdnite
 * Plugin URI: http://www.danrumney.com
 * Description:  A plugin adding nerdnite functions
 * Version: 2.0
 * Author: Dan Rumney
 * Author URI: http://www.danrumney.com
 * License: GPL2
 */

foreach ( glob( plugin_dir_path( __FILE__ ) . "features/*.php" ) as $file ) {
    include_once $file;
}
?>