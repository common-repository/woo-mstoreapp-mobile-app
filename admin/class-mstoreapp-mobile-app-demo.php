<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://mstoreapp.com
 * @since      1.0.0
 *
 * @package    Mstoreapp_Mobile_App
 * @subpackage Mstoreapp_Mobile_App/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mstoreapp_Mobile_App
 * @subpackage Mstoreapp_Mobile_App/admin
 * @author     Mstoreapp <support@mstoreapp.com>
 */
class Mstoreapp_Mobile_App_Demo {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  /**
   * Register the stylesheets for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Mstoreapp_Mobile_App_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Mstoreapp_Mobile_App_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mstoreapp-mobile-app-admin.css', array(), $this->version, 'all' );

  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Mstoreapp_Mobile_App_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Mstoreapp_Mobile_App_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mstoreapp-mobile-app-admin.js', array( 'jquery' ), $this->version, false );

  }


    public function mstoreapp_mobile_app_menu() {

        add_submenu_page('mstoreapp-mobile-app', __('Import demo', 'mstoreapp_app'),
            __('Import demo', 'mstoreapp_app'), 'activate_plugins', 'mstoreapp_app_import_demo', array(&$this, 'import_demo_page'));

    }

    public function import_demo_page() {

        echo '<div class="wrap">';
        echo '<h2>Import demo will delete your existing blocks.</h2>';

        $status = '';
        if (isset($_REQUEST['demo_import'])) {

            $values = array();

            if(isset($_REQUEST['option'])){
                $demo = trim(strip_tags($_REQUEST['option']));
            }else {
                $demo = '';
            }

            $status = $this->import_demo_content($demo);

        }


        ?>


    <?php if($status == 'success'){ ?>
        <div class="notice notice-success is-dismissible"> 
        <p><strong>Demo blocks succefully imported</strong></p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">Dismiss this notice.</span>
        </button>
    </div>
    <?php } else if($status == 'errors') { ?>
            <div class="notice notice-error is-dismissible"> 
        <p><strong>Unknown error</strong></p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">Dismiss this notice.</span>
        </button>
    </div>
    <?php } ?>


        <form action="" method="post">


            <table class="form-table">

                    <tr>       
                    <th style="width:100px;"><label for="option">Select demo</label></th>
                    <td><select name="option" id="option">
                    <option value="basic" <?php if ( $demo == 'basic' ) echo 'selected="selected"'; ?>>Basic</option>
                    <option value="fashion" <?php if ( $demo == 'fashion' ) echo 'selected="selected"'; ?>>Fashion</option>
                    <!--option value="electronics" <?php //if ( $demo == 'electronics' ) echo 'selected="selected"'; ?>>Electronics</option>
                    <option value="grocery" <?php //if ( $demo == 'grocery' ) echo 'selected="selected"'; ?>>Grocery</option>
                    <option value="furniture" <?php //if ( $demo == 'furniture' ) echo 'selected="selected"'; ?>>Furniture</option-->
                    </select></td>
                    </tr>
               
            </table>
            <p class="submit">
                <input type="submit" name="demo_import" value="Import" class="button-primary" />
            </p>
        </form>

        <?php if($status == 'success'){ ?>
        <h2 style="color: green">Don't forget to change link type and link id for Banner</h2>
        <?php } ?>
        <?php
        
        echo '</div>';

    }

    public function import_demo_content($demo) {

      switch ($demo) {
          case "basic":
              $this->import_basic();
              break;
          case "fashion":
              $this->import_fashion();
              break;
          case "electronics":
              $this->import_electronics();
              break;
          case "grocery":
              $this->import_grocery();
              break;
          case "furniture":
              $this->import_furniture();
              break;
          default:
              $this->import_fashion();
      }

      return 'success';
    }

    public function import_fashion() {

      $data = json_decode('[{"id":"1","name":"HOME","parent_id":"0","description":null,"block_type":"home","image_url":"","link_id":"0","link_type":"","tag":"","sort_order":"0","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"top_left","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"2","name":"BANNER SLIDER","parent_id":"1","description":null,"block_type":"banner_slider","image_url":"","link_id":"0","link_type":"product","tag":"","sort_order":"0","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"top_left","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"3","name":"BANNER SLIDER BANNER 1","parent_id":"2","description":null,"block_type":"banner","image_url":"https:\/\/drive.google.com\/uc?export=view&id=1PxN4ds2_RglCBtRTn68WK2Y1yjaH3jNA","link_id":"0","link_type":"","tag":"","sort_order":"0","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"top_left","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"4","name":"BANNER SLIDER BANNER 2","parent_id":"2","description":null,"block_type":"banner","image_url":"https:\/\/drive.google.com\/uc?export=view&id=1EnZOqANbKIo4xhmR1Ek7R-dNY5ZsstlM","link_id":"0","link_type":"","tag":"","sort_order":"1","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"top_left","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"5","name":"BANNER SLIDER BANNER 3","parent_id":"2","description":null,"block_type":"banner","image_url":"https:\/\/drive.google.com\/uc?export=view&id=1o7nrtNoJedvSG1ACyeCxjXhzrhf2ZHRF","link_id":"0","link_type":"","tag":"","sort_order":"2","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"top_left","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"6","name":"BANNER SLIDER BANNER 4","parent_id":"2","description":null,"block_type":"banner","image_url":"https:\/\/drive.google.com\/uc?export=view&id=1p_EAipi4LkIJ1Cr5FMSuAFO0EVB8CLSE","link_id":"0","link_type":"","tag":"","sort_order":"3","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"top_left","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"7","name":"CATEGORIES","parent_id":"1","description":null,"block_type":"category_block","image_url":"","link_id":"0","link_type":"product","tag":"","sort_order":"1","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"grid","layout_grid_col":"two","shape":"square","header_align":"none","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"8","name":"PRODUCT SLIDER","parent_id":"1","description":null,"block_type":"product_block","image_url":"","link_id":"31","link_type":"product","tag":"","sort_order":"15","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"% ","padding_left":"0","padding_left_dimension":"% ","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"none","text_color":"#000000","card_style":"card_without_shadow","end_time":""},{"id":"14","name":"NEWLY LANDED","parent_id":"1","description":null,"block_type":"banner_block","image_url":"","link_id":"0","link_type":"product","tag":"","sort_order":"0","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"20","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"20","padding_bottom_dimension":"px","padding_left":"40","padding_left_dimension":"% ","bg_color":"#1f3648","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"left_floating","text_color":"#ffffff","card_style":"card_with_shadow","end_time":""},{"id":"15","name":"NEWLY LANDED BANNER 1","parent_id":"14","description":null,"block_type":"banner","image_url":"https:\/\/drive.google.com\/uc?export=view&id=1mKNDo0N6xDJO4r5Wo3Zn4HKe_6p7QVTc","link_id":"0","link_type":"","tag":"","sort_order":"0","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"10","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"none","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"16","name":"NEWLY LANDED BANNER 2","parent_id":"14","description":null,"block_type":"banner","image_url":"https:\/\/drive.google.com\/uc?export=view&id=1wp1M8V6sTNYwLlo2Bkng3QSYLB0d8tl_","link_id":"0","link_type":"","tag":"","sort_order":"2","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"10","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"none","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"17","name":"NEWLY LANDED BANNER 3","parent_id":"14","description":null,"block_type":"banner","image_url":"https:\/\/drive.google.com\/uc?export=view&id=1Xmp4DzzcRY_yzmAQwpgy7LNDz9i1dO3U","link_id":"0","link_type":"","tag":"","sort_order":"0","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"10","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"none","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"18","name":"NEWLY LANDED BANNER 4","parent_id":"14","description":null,"block_type":"banner","image_url":"https:\/\/drive.google.com\/uc?export=view&id=19Ubj0HOhu9TCOlQ6JncWq_Ll4EBe55W9","link_id":"0","link_type":"","tag":"","sort_order":"0","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"10","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"none","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"19","name":"NEW ARRIVALS","parent_id":"1","description":null,"block_type":"banner_block","image_url":"","link_id":"0","link_type":"product","tag":"","sort_order":"8","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"20","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"20","padding_bottom_dimension":"px","padding_left":"10","padding_left_dimension":"px","bg_color":"#f6f5f3","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"none","text_color":"#7ab8dd","card_style":"card_with_shadow","end_time":""},{"id":"20","name":"NEW ARRIVALS BANNER 1","parent_id":"19","description":null,"block_type":"banner","image_url":"https:\/\/drive.google.com\/uc?export=view&id=1hXQDadr_UWENce4P7hfVTHpYQatiu1iu","link_id":"0","link_type":"","tag":"","sort_order":"0","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"10","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"none","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"21","name":"NEW ARRIVALS BANNER 2","parent_id":"19","description":null,"block_type":"banner","image_url":"https:\/\/drive.google.com\/uc?export=view&id=1w007zk8xCRnvTK0b5FOLHVv1mH4fmVkT","link_id":"0","link_type":"","tag":"","sort_order":"2","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"10","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"none","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"22","name":"NEW ARRIVALS BANNER 3","parent_id":"19","description":null,"block_type":"banner","image_url":"https:\/\/drive.google.com\/uc?export=view&id=16J0reZhOBnnMFUD7fdVuBPgZfSX7Ee9_","link_id":"0","link_type":"","tag":"","sort_order":"0","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"10","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"none","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"23","name":"NEW ARRIVALS BANNER 4","parent_id":"19","description":null,"block_type":"banner","image_url":"https:\/\/drive.google.com\/uc?export=view&id=1rb-jrKdGTpBKNqUbivNASDwc7fzWQdct","link_id":"0","link_type":"","tag":"","sort_order":"0","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"10","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"none","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"24","name":"TOP BRANDS NEW COlLECTIONS","parent_id":"1","description":null,"block_type":"banner_block","image_url":"","link_id":"0","link_type":"product","tag":"","sort_order":"10","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"20","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"20","padding_bottom_dimension":"px","padding_left":"40","padding_left_dimension":"% ","bg_color":"#007396","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"left_floating","text_color":"#ffffff","card_style":"card_with_shadow","end_time":""},{"id":"25","name":"TOP BRANDS NEW COLLECTION BANNER 1","parent_id":"24","description":null,"block_type":"banner","image_url":"https:\/\/drive.google.com\/uc?export=view&id=1VO_nvYNLAnFQkG834mDFlbjIi3iIggfj","link_id":"0","link_type":"","tag":"","sort_order":"0","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"10","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"50","border_radius_dimension":"% ","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"none","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"26","name":"TOP BRANDS NEW COLLECTION BANNER 4","parent_id":"24","description":null,"block_type":"banner","image_url":"https:\/\/drive.google.com\/uc?export=view&id=1ioCC_o_ryqtZOLJHUcF63Js4Fnm1xy2c","link_id":"0","link_type":"","tag":"","sort_order":"4","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"10","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"50","border_radius_dimension":"% ","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"none","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"27","name":"TOP BRANDS NEW COlLECTIONS BANNER 2","parent_id":"24","description":null,"block_type":"banner","image_url":"https:\/\/drive.google.com\/uc?export=view&id=1BN0VCJc7cToCaIRSoa5gQ0dGCbrWiyMz","link_id":"0","link_type":"","tag":"","sort_order":"0","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"10","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"50","border_radius_dimension":"% ","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"none","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"28","name":"TOP BRANDS NEW COlLECTIONS BANNER 3","parent_id":"24","description":null,"block_type":"banner","image_url":"https:\/\/drive.google.com\/uc?export=view&id=10-eE8VxbDS6TwPJ8YM4sZ_wIxyN4nvq8","link_id":"0","link_type":"","tag":"","sort_order":"0","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"10","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"50","border_radius_dimension":"% ","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"none","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"29","name":"FLASH SALE","parent_id":"1","description":"","block_type":"flash_sale","image_url":"","link_id":"0","link_type":"","tag":"on_sale","sort_order":"11","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"none","text_color":"#000000","card_style":"card_without_shadow","end_time":"Sep 09, 2019 12:00"}]', true);

      global $wpdb;
      $table_name = $wpdb->prefix . "mstoreapp_blocks";

      $sql = "DELETE FROM $table_name";

      $wpdb->query($sql);

      foreach ($data as $value) {
         $result = $wpdb->insert($table_name, $value);
      }

    }

    public function import_basic() {

           $data = json_decode('[{"id":"1","name":"HOME","parent_id":"0","description":null,"block_type":"home","image_url":"","link_id":"0","link_type":"","tag":"","sort_order":"0","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"top_left","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"2","name":"BANNER SLIDER","parent_id":"1","description":null,"block_type":"banner_slider","image_url":"","link_id":"0","link_type":"product","tag":"","sort_order":"0","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"top_left","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"3","name":"BANNER SLIDER BANNER 1","parent_id":"2","description":null,"block_type":"banner","image_url":"https://drive.google.com/uc?export=view&id=1PxN4ds2_RglCBtRTn68WK2Y1yjaH3jNA","link_id":"0","link_type":"","tag":"","sort_order":"0","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"top_left","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"4","name":"BANNER SLIDER BANNER 2","parent_id":"2","description":null,"block_type":"banner","image_url":"https://drive.google.com/uc?export=view&id=1EnZOqANbKIo4xhmR1Ek7R-dNY5ZsstlM","link_id":"0","link_type":"","tag":"","sort_order":"1","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"top_left","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"5","name":"BANNER SLIDER BANNER 3","parent_id":"2","description":null,"block_type":"banner","image_url":"https://drive.google.com/uc?export=view&id=1o7nrtNoJedvSG1ACyeCxjXhzrhf2ZHRF","link_id":"0","link_type":"","tag":"","sort_order":"2","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"top_left","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"6","name":"BANNER SLIDER BANNER 4","parent_id":"2","description":null,"block_type":"banner","image_url":"https://drive.google.com/uc?export=view&id=1p_EAipi4LkIJ1Cr5FMSuAFO0EVB8CLSE","link_id":"0","link_type":"","tag":"","sort_order":"3","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"scroll","layout_grid_col":"two","shape":"square","header_align":"top_left","text_color":"#000000","card_style":"card_with_shadow","end_time":""},{"id":"7","name":"CATEGORIES","parent_id":"1","description":null,"block_type":"category_block","image_url":"","link_id":"0","link_type":"product","tag":"","sort_order":"1","status":"true","margin_top":"0","margin_top_dimension":"px","margin_right":"0","margin_right_dimension":"px","margin_bottom":"0","margin_bottom_dimension":"px","margin_left":"0","margin_left_dimension":"px","padding_top":"0","padding_top_dimension":"px","padding_right":"0","padding_right_dimension":"px","padding_bottom":"0","padding_bottom_dimension":"px","padding_left":"0","padding_left_dimension":"px","bg_color":"#ffffff","border_radius":"0","border_radius_dimension":"px","layout":"grid","layout_grid_col":"two","shape":"square","header_align":"none","text_color":"#000000","card_style":"card_with_shadow","end_time":""}]', true);

      global $wpdb;
      $table_name = $wpdb->prefix . "mstoreapp_blocks";

      $sql = "DELETE FROM $table_name";

      $wpdb->query($sql);

      foreach ($data as $value) {
         $result = $wpdb->insert($table_name, $value);
      }

    }

    public function import_electronics() {

    }

    public function import_grocery() {

    }

    public function import_furniture() {



    }


}
