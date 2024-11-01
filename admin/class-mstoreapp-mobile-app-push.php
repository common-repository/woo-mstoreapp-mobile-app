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
class Mstoreapp_Mobile_App_Push {

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

        add_submenu_page('mstoreapp-mobile-app', __('Push Notification', 'mstoreapp_app'),
            __('Push Notification', 'mstoreapp_app'), 'activate_plugins', 'mstoreapp_app_block_push', array(&$this, 'push_notification_page'));

    }

        public function push_notification_page() {

        echo '<div class="wrap">';
        echo '<h2>Send Push Notification</h2>';
        $status = '';
 
        if (isset($_REQUEST['push_all'])) {

            $values = array();

            if(isset($_REQUEST['title'])){
                $values['title'] = trim(strip_tags($_REQUEST['title']));
            }else {
                $values['title'] = '';
            }

            if(isset($_REQUEST['message'])){
                $values['message'] = trim(strip_tags($_REQUEST['message']));
            }else {
                $values['message'] = '';
            }

            if(isset($_REQUEST['filter'])){
                $values['filter'] = trim(strip_tags($_REQUEST['filter']));
            }else {
                $values['filter'] = '';
            }

            if(isset($_REQUEST['option'])){
                $values['option'] = trim(strip_tags($_REQUEST['option']));
            }else {
                $values['option'] = '';
            }

            if(isset($_REQUEST['isAndroid']) && $values['isAndroid'] == 1){
                $values['isAndroid'] = true;
            }else {
                $values['isAndroid'] = false;
            }

            if(isset($_REQUEST['isIos']) && $values['isIos'] == 1){
                $values['isIos'] = true;
            }else {
                $values['isIos'] = false;
            }
            
            $values['isIos'] = trim(strip_tags($_REQUEST['isIos']));

            update_option('mstoreapp_push', $values );

            $fields = array();

            if($values['option'] == "email"){
                $fields['filters'] = array(array("field" => "tag", "key" => "email", "relation" => "=", "value" => $values['filter']));
            }
            if($values['option'] == "pincode"){
                $fields['filters'] = array(array("field" => "tag", "key" => "pincode", "relation" => "=", "value" => $values['filter']));
            }
            if($values['option'] == "city"){
                $fields['filters'] = array(array("field" => "tag", "key" => "city", "relation" => "=", "value" => $values['filter']));
            }
            if($values['option'] == "state"){
                $fields['filters'] = array(array("field" => "tag", "key" => "state", "relation" => "=", "value" => $values['filter']));
            }
            if($values['option'] == "country"){
                $fields['filters'] = array(array("field" => "tag", "key" => "country", "relation" => "=", "value" => $values['filter']));
            }
            if($values['option'] == "topic"){
                $fields['filters'] = array(array("field" => "tag", "key" => "topic", "relation" => "=", "value" => $values['filter']));
            }



            $fields['included_segments'] = array("All");

            $fields['headings'] = array("en" => trim(strip_tags($_REQUEST['title'])));
            $fields['contents'] = array("en" => trim(strip_tags($_REQUEST['message'])));

            if($values['isAndroid'] == 1)
            $fields['isAndroid'] = true;
            else $fields['isAndroid'] = false;
            if($values['isIos'] == 1)
            $fields['isIos'] = true;
            else $fields['isIos'] = false;

            $fields['isAnyWeb'] = false;
            $fields['isWP'] = false;
            $fields['isAdm'] = false;
            $fields['isChrome'] = false;

            $onesignal_post_url = "https://onesignal.com/api/v1/notifications";
            $onesignal_wp_settings = OneSignal::get_onesignal_settings();
            $onesignal_auth_key = $onesignal_wp_settings['app_rest_api_key'];
            $fields['app_id'] = $onesignal_wp_settings['app_id'];

             
            $args = array(
              'body' => $fields,
              'timeout' => '5',
              'redirection' => '5',
              'httpversion' => '1.0',
              'blocking' => true,
              'headers' => array(),
              'cookies' => array(),
              'headers' => array(
                'Content-Type: application/json',
                'Authorization' => 'Basic ' . $onesignal_auth_key
              )
            );


            $response = wp_remote_post( $onesignal_post_url, $args );

            $body = wp_remote_retrieve_body( $response );

            if(isset($body['id']))
            $status = 'success';
            if($body['errors'][0])
            $status = 'errors';

        }


        ?>

<p>Please enter title and message to send all registred devices.</p>

    <?php if($status == 'success'){ ?>
        <div class="notice notice-success is-dismissible"> 
        <p><strong>Notification Sent. Total Recipients <?php echo $body['recipients'] ?></strong></p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">Dismiss this notice.</span>
        </button>
    </div>
    <?php } else if($status == 'errors') { ?>
            <div class="notice notice-error is-dismissible"> 
        <p><strong>Error</strong></p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">Dismiss this notice.</span>
        </button>
    </div>
    <?php } ?>

<?php $options = get_option( 'mstoreapp_push' ); ?>
        <form action="" method="post">


            <table class="form-table">


                <tr>
                    <th style="width:100px;"><label for="title">Title</label></th>
                    <td ><input class="regular-text" type="text" id="title" name="title"  value="<?php echo $options['title'] ?>" ></td>
                </tr>
                <tr>
                    <th style="width:100px;"><label for="message">Message</label></th>
                    <td ><input class="regular-text" type="text" id="message" name="message"  value="<?php echo $options['message']; ?>" ></td>
                </tr>

                    <tr>       
                    <th style="width:100px;"><label for="option">Target option</label></th>
                    <td><select name="option" id="option">
                    <option value="all" <?php if ( $options['option'] == 'all' ) echo 'selected="selected"'; ?>>Send to All Device</option>
                    <option value="pincode" <?php if ( $options['option'] == 'pincode' ) echo 'selected="selected"'; ?>>Send to Pincode</option>
                    <option value="city" <?php if ( $options['option'] == 'city' ) echo 'selected="selected"'; ?>>Send to City</option>
                    <option value="state" <?php if ( $options['option'] == 'state' ) echo 'selected="selected"'; ?>>Send to State</option>
                    <option value="country" <?php if ( $options['option'] == 'country' ) echo 'selected="selected"'; ?>>Send to Country</option>
                    <option value="topic" <?php if ( $options['option'] == 'topic' ) echo 'selected="selected"'; ?>>Send to Topic</option>
                    <option value="email" <?php if ( $options['option'] == 'email' ) echo 'selected="selected"'; ?>>Send to Email</option>
                    </select></td>
                    </tr>
                <tr>
                    <th style="width:100px;"><label for="filter">Target value</label></th>
                    <td ><input class="regular-text" type="text" id="filter" name="filter"  value="<?php echo $options['filter']; ?>" ><p>Leave blank to traget all devices</p><p>Enter Pincode or State or Country or Topic or Email</p></td>
                </tr>


                <tr>
                    <th style="width:50px;"><label for="is_android">Android</label></th>
                    <td><input type="checkbox" name="isAndroid" value="1"<?php checked( 1 == $options['isAndroid'] ); ?> /></td>
                </tr>

                <tr>
                    <th style="width:50px;"><label for="is_ios">iOS</label></th>
                    <td ><input type="checkbox" name="isIos" value="1"<?php checked( 1 == $options['isIos'] ); ?> /></td>
                </tr>


            </table>
            <p class="submit">
                <input type="submit" name="push_all" value="Send Now" class="button-primary" />
            </p>
        </form>
        <?php
        
        echo '</div>';

    }

}
