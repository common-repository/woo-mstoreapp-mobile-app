<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://mstoreapp.com
 * @since      1.0.0
 *
 * @package    Mstoreapp_Mobile_App
 * @subpackage Mstoreapp_Mobile_App/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Mstoreapp_Mobile_App
 * @subpackage Mstoreapp_Mobile_App/includes
 * @author     Mstoreapp <support@mstoreapp.com>
 */
class Mstoreapp_Mobile_App_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

	  // Remove the rewrite rule on deactivation
	  global $wp_rewrite;
	  $wp_rewrite->flush_rules();

	  global $wpdb;
	    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');  

	    $charset_collate = '';
	    if (!empty($wpdb->charset)){
	        $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	    }else{
	        $charset_collate = "DEFAULT CHARSET=utf8";
	    }
	    if (!empty($wpdb->collate)){
	        $charset_collate .= " COLLATE $wpdb->collate";
	    }

	    $table_name = $wpdb->prefix . "mstoreapp_blocks";

	    $sql = "DROP TABLE IF EXISTS $table_name";

	    $wpdb->query($sql);

	    $table_name = $wpdb->prefix . "mstoreapp_wishlist";

	    $sql = "DROP TABLE IF EXISTS $table_name";

	    $wpdb->query($sql);

	}

}
