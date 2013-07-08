<?php
/*
Plugin Name: Citations
Plugin URI: http://www.jennysharps.com
Description: Add citation post type and custom fields
Version: 1.0
Author: Jenny Sharps
Author URI: http://www.jennysharps.com
*/


require_once( dirname( __FILE__ ) . '/autoloader.php' );
require_once( dirname( __FILE__ ) . '/inc/wrapper-functions.php' );

$citations = new JLS\Citations\Citation( __FILE__ );