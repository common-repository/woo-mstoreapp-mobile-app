<?php

/**
 * Fired during plugin activation
 *
 * @link       http://mstoreapp.com
 * @since      1.0.0
 *
 * @package    Mstoreapp_Mobile_App
 * @subpackage Mstoreapp_Mobile_App/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Mstoreapp_Mobile_App
 * @subpackage Mstoreapp_Mobile_App/includes
 * @author     Mstoreapp <support@mstoreapp.com>
 */
class Mstoreapp_Mobile_App_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

	  // Add the rewrite rule on activation

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

	    $table_name = $wpdb->prefix . "mstoreapp_wishlist";

	    $lk_tbl_sql = "CREATE TABLE " . $table_name . " (
	              id bigint(20) unsigned NOT NULL auto_increment,
	              customer_id int NOT NULL,
	              product_id int NOT NULL,
	              PRIMARY KEY (id)
	              )" . $charset_collate . ";";
	    dbDelta($lk_tbl_sql);

	    $wpdb->query('CREATE UNIQUE INDEX index_name ON $table_name(customer_id, product_id)');

	    $table_name = $wpdb->prefix . 'mstoreapp_blocks'; // do not forget about tables prefix
   		
	    $sql = "CREATE TABLE " . $table_name . " (
	      id int(11) NOT NULL AUTO_INCREMENT,
	      name VARCHAR(50) NULL,
	      parent_id int(15) NULL,
	      description varchar(255) NULL,
	      status VARCHAR(50) NULL,
	      block_type VARCHAR(255) NULL, 
	      image_url VARCHAR(500) NULL,
	      link_id int(15) NULL,
	      link_type VARCHAR(50) NULL,
	      end_time VARCHAR(50) NULL,
	      tag VARCHAR(50) NULL,
	      sort_order int(15) NULL,
	      margin_top int(4) NULL,
	      margin_top_dimension VARCHAR(50) NULL,
	      margin_right int(4) NULL,
	      margin_right_dimension VARCHAR(50) NULL,
	      margin_bottom int(4) NULL,
	      margin_bottom_dimension VARCHAR(50) NULL,
	      margin_left int(4) NULL,
	      margin_left_dimension VARCHAR(50) NULL,
	      padding_top int(4) NULL,
	      padding_top_dimension VARCHAR(50) NULL,
	      padding_right int(4) NULL,
	      padding_right_dimension VARCHAR(50) NULL,
	      padding_bottom int(4) NULL,
	      padding_bottom_dimension VARCHAR(50) NULL,
	      padding_left int(4) NULL,
	      padding_left_dimension VARCHAR(50) NULL,
	      border_radius int(4) NULL,
	      border_radius_dimension  VARCHAR(50) NULL,
	      layout VARCHAR(50) NULL,
	      layout_grid_col VARCHAR(50) NULL,
	      card_style VARCHAR(50) NULL,
	      shape VARCHAR(50) NULL,
	      header_align VARCHAR(50) NULL,
	      text_color VARCHAR(50) NULL,
	      bg_color  VARCHAR(50) NULL,
	      PRIMARY KEY  (id)
	    );";              
	    dbDelta($sql);
	}

}
