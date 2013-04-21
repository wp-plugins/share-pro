<?php


/*

Plugin Name: SharePro
Description: With SharePro you can raise your sites user registrations quickly.
Version: 1.0
Author: vuzzu
Author URI: http://vuzzu.net/
Plugin URI: http://vuzzu.net/plugins/sharepro

*/


# Plugin constants
define('SHAREPRO_DIR', plugin_dir_path(__FILE__));
define('SHAREPRO_URL', plugin_dir_url(__FILE__));


# Database table name
$sp_table_name = $wpdb->prefix . "sharepro_downloads";


# INITIALIZATION OF PLUGIN
sharepro_init();
function sharepro_init(){

    if( is_admin() )
    	require_once( SHAREPRO_DIR.'admin/main.php' );

    require_once( SHAREPRO_DIR.'includes/main.php' );
    require_once( SHAREPRO_DIR.'includes/ajax.php' );

}

# Pro User Role
add_action('init', 'sharepro_downloader_role');
function sharepro_downloader_role( ) {
	$author = get_role('subscriber');
	$caps 	= $author->capabilities;
	add_role( 'proaccess', "Pro-Access", $caps );
}


# Activating plugin
register_activation_hook(__FILE__, 'sharepro_activation');
function sharepro_activation() {
	global $wpdb,$sp_table_name;

	$wpdb->query( "CREATE TABLE IF NOT EXISTS $sp_table_name (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	  ip tinytext NOT NULL,
	  count int NOT NULL,
	  UNIQUE KEY id (id) );");

	# Registering uninstallation hook
	register_uninstall_hook(__FILE__, 'sharepro_uninstallation');

}


# Deactivating plugin
register_deactivation_hook(__FILE__, 'sharepro_deactivation');
function sharepro_deactivation() {

	# Remove Pro User Role
	remove_role( 'proaccess' );
	 
}


# Uninstalling plugin
function sharepro_uninstallation() {
	global $wpdb,$sp_table_name;

	$wpdb->query( "DROP TABLE IF_EXISTS $sp_table_name");
}


?>