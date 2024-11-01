<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://mstoreapp.com
 * @since      1.0.0
 *
 * @package    Mstoreapp_Mobile_App
 * @subpackage Mstoreapp_Mobile_App/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mstoreapp_Mobile_App
 * @subpackage Mstoreapp_Mobile_App/public
 * @author     Mstoreapp <support@mstoreapp.com>
 */
class Mstoreapp_Mobile_App_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Api Keys
     */

    public static function keys()
    {

        global $woocommerce;

        $data = array();
        $data['banners'] = array(
            esc_attr(get_option('BannerUrl1')),
            esc_attr(get_option('BannerUrl2')),
            esc_attr(get_option('BannerUrl3'))
        );

        $data['pages'] = array(
            'about' => esc_attr(get_option('mstoreapp-about')),
            'privacy' => esc_attr(get_option('mstoreapp-privacy')),
            'terms' => esc_attr(get_option('mstoreapp-terms'))
        );

        global $wpdb;
        $table_name = $wpdb->prefix . "postmeta";
        $query = "SELECT max(cast(meta_value as unsigned)) FROM $table_name WHERE meta_key='_price'";
        $data['max_price'] = $wpdb->get_var($query);

        $data['login_nonce'] = wp_create_nonce('woocommerce-login');

        $data['currency'] = get_woocommerce_currency();

        if (is_user_logged_in()) {
            $data['user'] = wp_get_current_user();
            $data['user']->status = true;
            $data['user']->url = wp_logout_url();
            $data['user']->avatar = get_avatar($data['user']->ID, 128);
            $data['user']->avatar_url = get_avatar_url($data['user']->ID);
            //$data['user']->wc_points_balance = get_user_meta( $data['user']->ID, 'wc_points_balance', true );
            //$data['user']->points_vlaue = WC_Points_Rewards_Manager::get_users_points_value($data['user']->ID);
            $data['user']->payment = apply_filters( 'woocommerce_saved_payment_methods_list', array(), $data['user']->ID );
            wp_send_json($data);
        }

        $data['user']->status = false;

        wp_send_json($data);

        die();
    }

    public static function blocks()
    {
        global $woocommerce;
        $data = array();

        global $wpdb;
        $table_name = $wpdb->prefix . "mstoreapp_blocks";

        $query = 

        "SELECT
            id,
            name,
            parent_id,
            block_type,
            image_url,
            link_id,
            link_type,
            tag,
            sort_order,
            status,
            concat(margin_top,margin_top_dimension,' ',margin_right,margin_right_dimension,' ',margin_bottom,margin_bottom_dimension,' ',margin_left,margin_left_dimension) as margin,
            concat(padding_top,padding_top_dimension,' ',padding_right,padding_right_dimension,' ',padding_bottom,padding_bottom_dimension,' ',padding_left,padding_left_dimension) as padding,
            bg_color,
            concat(border_radius,border_radius_dimension)as border_radius,
            layout,
            layout_grid_col,
            shape,
            header_align,
            text_color,
            card_style,
            end_time
        
        FROM $table_name WHERE parent_id = 0 and status = 'true'";
        
        $data = $wpdb->get_results($query);
        

         
        foreach ($data as $key => $value) {

           //$query = "SELECT * FROM $table_name WHERE parent_id ='".$key['id']."'";

           $query = $wpdb->prepare(

            "SELECT 

                id,
                name,
                parent_id,
                block_type,
                image_url,
                link_id,
                link_type,
                tag,
                sort_order,
                status,
                concat(margin_top,margin_top_dimension,' ',margin_right,margin_right_dimension,' ',margin_bottom,margin_bottom_dimension,' ',margin_left,margin_left_dimension) as margin,
                concat(padding_top,padding_top_dimension,' ',padding_right,padding_right_dimension,' ',padding_bottom,padding_bottom_dimension,' ',padding_left,padding_left_dimension) as padding,
                bg_color,
                concat(border_radius,border_radius_dimension)as border_radius,
                layout,
                layout_grid_col,
                shape,
                header_align,
                card_style,
                text_color,
                end_time

            FROM $table_name WHERE parent_id = %d and status = 'true' ORDER BY sort_order", $value->id);

           
            $data[$key]->children = $wpdb->get_results($query);


               foreach ($data[$key]->children as $k => $v) {

                   $query = $wpdb->prepare(

                    "SELECT 
                        id,
                        name,
                        parent_id,
                        block_type,
                        image_url,
                        link_id,
                        link_type,
                        tag,
                        sort_order,
                        status,
                        concat(margin_top,margin_top_dimension,' ',margin_right,margin_right_dimension,' ',margin_bottom,margin_bottom_dimension,' ',margin_left,margin_left_dimension) as margin,
                        concat(padding_top,padding_top_dimension,' ',padding_right,padding_right_dimension,' ',padding_bottom,padding_bottom_dimension,' ',padding_left,padding_left_dimension) as padding,
                        bg_color,
                        concat(border_radius,border_radius_dimension)as border_radius,
                        layout,
                        layout_grid_col,
                        shape,
                        header_align,
                        text_color,
                        card_style,
                        end_time
    
                    FROM $table_name WHERE parent_id = %d and status = 'true' ORDER BY sort_order", $v->id);

                   $data[$key]->children[$k]->children = $wpdb->get_results($query);
                }
        }

              $result['blocks'] = $data;
              //$result['css style'] = $wpdb->get_results ( "SELECT css_style_1 FROM  $table_name WHERE parent_id =0" );
              $options = get_option('mstore_settings');
              //unset($options['consumer_key']);
              //unset($options['consumer_secret']);
              unset($options['key']);
              $result['settings'] = $options;

              wp_send_json($result);
        die();
    }

    public static function children()
    {
        global $woocommerce;
        $data = array();

        $id = $_REQUEST['id'];

        global $wpdb;
        $table_name = $wpdb->prefix . "mstoreapp_blocks";

        $query = $wpdb->prepare(

            "SELECT 

                id,
                name,
                parent_id,
                block_type,
                image_url,
                link_id,
                link_type,
                tag,
                sort_order,
                status,
                concat(margin_top,margin_top_dimension,' ',margin_right,margin_right_dimension,' ',margin_bottom,margin_bottom_dimension,' ',margin_left,margin_left_dimension) as margin,
                concat(padding_top,padding_top_dimension,' ',padding_right,padding_right_dimension,' ',padding_bottom,padding_bottom_dimension,' ',padding_left,padding_left_dimension) as padding,
                bg_color,
                concat(border_radius,border_radius_dimension) as border_radius,
                layout,
                layout_grid_col,
                shape,
                header_align,
                card_style,
                text_color,
                end_time

            FROM $table_name WHERE parent_id = %d and status = 'true' ORDER BY sort_order", $id);
        
        $data= $wpdb->get_results($query);
        

         
        foreach ($data as $key => $value) {

           //$query = "SELECT * FROM $table_name WHERE parent_id ='".$key['id']."'";

           $query = $wpdb->prepare(

            "SELECT 

                id,
                name,
                parent_id,
                block_type,
                image_url,
                link_id,
                link_type,
                tag,
                sort_order,
                status,
                concat(margin_top,margin_top_dimension,' ',margin_right,margin_right_dimension,' ',margin_bottom,margin_bottom_dimension,' ',margin_left,margin_left_dimension) as margin,
                concat(padding_top,padding_top_dimension,' ',padding_right,padding_right_dimension,' ',padding_bottom,padding_bottom_dimension,' ',padding_left,padding_left_dimension) as padding,
                bg_color,
                concat(border_radius,border_radius_dimension)as border_radius,
                layout,
                layout_grid_col,
                shape,
                header_align,
                card_style,
                text_color,
                end_time

            FROM $table_name WHERE parent_id = %d and status = 'true' ORDER BY sort_order", $value->id);

           
            $data[$key]->children = $wpdb->get_results($query);


               foreach ($data[$key]->children as $k => $v) {

                   $query = $wpdb->prepare(

                    "SELECT 
                        id,
                        name,
                        parent_id,
                        block_type,
                        image_url,
                        link_id,
                        link_type,
                        tag,
                        sort_order,
                        status,
                        concat(margin_top,margin_top_dimension,' ',margin_right,margin_right_dimension,' ',margin_bottom,margin_bottom_dimension,' ',margin_left,margin_left_dimension) as margin,
                        concat(padding_top,padding_top_dimension,' ',padding_right,padding_right_dimension,' ',padding_bottom,padding_bottom_dimension,' ',padding_left,padding_left_dimension) as padding,
                        bg_color,
                        concat(border_radius,border_radius_dimension)as border_radius,
                        layout,
                        layout_grid_col,
                        shape,
                        header_align,
                        text_color,
                        card_style,
                        end_time
    
                    FROM $table_name WHERE parent_id = %d and status = 'true' ORDER BY sort_order", $v->id);

                   $data[$key]->children[$k]->children = $wpdb->get_results($query);
                }
        }

              $result['blocks'] = $data;

              wp_send_json($result);
        die();
    }

    public static function test()
    {
        
        //Get demo data or prepare demo data

        global $wpdb;
        $table_name = $wpdb->prefix . "mstoreapp_blocks";

        $query = "SELECT id, name, parent_id, description, block_type, image_url, link_id, link_type, tag, sort_order, status, margin_top,margin_top_dimension, margin_right, margin_right_dimension, margin_bottom, margin_bottom_dimension, margin_left, margin_left_dimension, padding_top, padding_top_dimension, padding_right, padding_right_dimension, padding_bottom, padding_bottom_dimension, padding_left, padding_left_dimension, bg_color, border_radius, border_radius_dimension, border_radius, layout, layout_grid_col, shape, header_align, text_color, card_style, end_time FROM $table_name";

        $data = $wpdb->get_results($query, 'ARRAY_A');
        
        wp_send_json($data);

        die();
    }

    /**
     * AJAX apply coupon on checkout page.
     */
    public static function apply_coupon()
    {

        //check_ajax_referer( 'apply-coupon', 'security' );

        if (!empty($_POST['coupon_code'])) {
            WC()->cart->add_discount(sanitize_text_field($_POST['coupon_code']));
        } else {
            wc_add_notice(WC_Coupon::get_generic_coupon_error(WC_Coupon::E_WC_COUPON_PLEASE_ENTER), 'error');
        }

        wc_print_notices();

        die();
    }

    /**
     * AJAX remove coupon on cart and checkout page.
     */
    public static function remove_coupon()
    {

        //check_ajax_referer( 'remove-coupon', 'security' );

        $coupon = wc_clean($_POST['coupon']);

        if (!isset($coupon) || empty($coupon)) {
            wc_add_notice(__('Sorry there was a problem removing this coupon.', 'woocommerce'), 'error');

        } else {

            WC()->cart->remove_coupon($coupon);

            wc_add_notice(__('Coupon has been removed.', 'woocommerce'));
        }

        wc_print_notices();

        die();
    }

    /**
     * AJAX update shipping method on cart page.
     */
    public static function update_shipping_method()
    {

        //check_ajax_referer( 'update-shipping-method', 'security' );

        if (!defined('WOOCOMMERCE_CART')) {
            define('WOOCOMMERCE_CART', true);
        }

        $chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');

        if (isset($_POST['shipping_method']) && is_array($_POST['shipping_method'])) {
            foreach ($_POST['shipping_method'] as $i => $value) {
                $chosen_shipping_methods[$i] = wc_clean($value);
            }
        }

        WC()->session->set('chosen_shipping_methods', $chosen_shipping_methods);


        $data = WC()->cart;
        WC()->cart->calculate_totals();

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

            if (has_post_thumbnail($product_id)) {
                $image = get_the_post_thumbnail_url($product_id, 'medium');
            } elseif (($parent_id = wp_get_post_parent_id($product_id)) && has_post_thumbnail($parent_id)) {
                $image = get_the_post_thumbnail_url($parent_id, 'medium');
            } else {
                $image = wc_placeholder_img('medium');
            }

            $data->cart_contents[$cart_item_key]['name'] = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
            $data->cart_contents[$cart_item_key]['thumb'] = $image;
            $data->cart_contents[$cart_item_key]['remove_url'] = WC()->cart->get_remove_url($cart_item_key);
            $data->cart_contents[$cart_item_key]['price'] = $_product->get_price();
            $data->cart_contents[$cart_item_key]['tax_price'] = $_product->get_price_including_tax();
            $data->cart_contents[$cart_item_key]['regular_price'] = $_product->get_regular_price();
            $data->cart_contents[$cart_item_key]['sales_price'] = $_product->get_sale_price();

        }

        $data->cart_nonce = wp_create_nonce('woocommerce-cart');

        $data->cart_totals = WC()->cart->get_totals();

        //$data->shipping = WC()->shipping->load_shipping_methods($packages);

        $packages = WC()->shipping->get_packages();
        $first = true;

        foreach ($packages as $i => $package) {
            $chosen_method = isset(WC()->session->chosen_shipping_methods[$i]) ? WC()->session->chosen_shipping_methods[$i] : '';
            $product_names = array();

            if (sizeof($packages) > 1) {
                foreach ($package['contents'] as $item_id => $values) {
                    $product_names[$item_id] = $values['data']->get_name() . ' &times;' . $values['quantity'];
                }
                $product_names = apply_filters('woocommerce_shipping_package_details_array', $product_names, $package);
            }

            $mydata[] = array(
                'package' => $package,
                'available_methods' => $package['rates'],
                'show_package_details' => sizeof($packages) > 1,
                'show_shipping_calculator' => is_cart() && $first,
                'package_details' => implode(', ', $product_names),
                'package_name' => apply_filters('woocommerce_shipping_package_name', sprintf(_nx('Shipping', 'Shipping %d', ($i + 1), 'shipping packages', 'woocommerce'), ($i + 1)), $i, $package),
                'index' => $i,
                'chosen_method' => $chosen_method,
            );

            $first = false;
        }
        foreach ($package['rates'] as $i => $method) {
            $shipping[$i]['id'] = $method->get_id();
            $shipping[$i]['label'] = $method->get_label();
            $shipping[$i]['cost'] = $method->get_cost();
            $shipping[$i]['method_id'] = $method->get_method_id();
            $shipping[$i]['taxes'] = $method->get_taxes();
        }

        $data->chosen_shipping = WC()->session->get('chosen_shipping_methods');

        $data->shipping = $shipping;


        wp_send_json($data);


        die();
    }

    /**
     * AJAX receive updated cart_totals div.
     */
    public static function get_cart_totals()
    {

        if (!defined('WOOCOMMERCE_CART')) {
            define('WOOCOMMERCE_CART', true);
        }

        WC()->cart->calculate_totals();

        woocommerce_cart_totals();

        die();
    }

    /**
     * AJAX update order review on checkout.
     */
    public static function update_order_review()
    {
        ob_start();

        //check_ajax_referer( 'update-order-review', 'security' );

        if (!defined('WOOCOMMERCE_CHECKOUT')) {
            define('WOOCOMMERCE_CHECKOUT', true);
        }

        if (WC()->cart->is_empty()) {
            $data = array(
                'fragments' => apply_filters('woocommerce_update_order_review_fragments', array(
                    'form.woocommerce-checkout' => '<div class="woocommerce-error">' . __('Sorry, your session has expired.', 'woocommerce') . ' <a href="' . esc_url(wc_get_page_permalink('shop')) . '" class="wc-backward">' . __('Return to shop', 'woocommerce') . '</a></div>'
                ))
            );

            wp_send_json($data);

            die();
        }

        do_action('woocommerce_checkout_update_order_review', $_POST['post_data']);

        $chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');

        if (isset($_POST['shipping_method']) && is_array($_POST['shipping_method'])) {
            foreach ($_POST['shipping_method'] as $i => $value) {
                $chosen_shipping_methods[$i] = wc_clean($value);
            }
        }

        WC()->session->set('chosen_shipping_methods', $chosen_shipping_methods);
        WC()->session->set('chosen_payment_method', empty($_POST['payment_method']) ? '' : $_POST['payment_method']);

        if (isset($_POST['country'])) {
            WC()->customer->set_country($_POST['country']);
        }

        if (isset($_POST['state'])) {
            WC()->customer->set_state($_POST['state']);
        }

        if (isset($_POST['postcode'])) {
            WC()->customer->set_postcode($_POST['postcode']);
        }

        if (isset($_POST['city'])) {
            WC()->customer->set_city($_POST['city']);
        }

        if (isset($_POST['address'])) {
            WC()->customer->set_address($_POST['address']);
        }

        if (isset($_POST['address_2'])) {
            WC()->customer->set_address_2($_POST['address_2']);
        }

        if (wc_ship_to_billing_address_only()) {

            if (!empty($_POST['country'])) {
                WC()->customer->set_shipping_country($_POST['country']);
                WC()->customer->calculated_shipping(true);
            }

            if (isset($_POST['state'])) {
                WC()->customer->set_shipping_state($_POST['state']);
            }

            if (isset($_POST['postcode'])) {
                WC()->customer->set_shipping_postcode($_POST['postcode']);
            }

            if (isset($_POST['city'])) {
                WC()->customer->set_shipping_city($_POST['city']);
            }

            if (isset($_POST['address'])) {
                WC()->customer->set_shipping_address($_POST['address']);
            }

            if (isset($_POST['address_2'])) {
                WC()->customer->set_shipping_address_2($_POST['address_2']);
            }
        } else {

            if (!empty($_POST['s_country'])) {
                WC()->customer->set_shipping_country($_POST['s_country']);
                WC()->customer->calculated_shipping(true);
            }

            if (isset($_POST['s_state'])) {
                WC()->customer->set_shipping_state($_POST['s_state']);
            }

            if (isset($_POST['s_postcode'])) {
                WC()->customer->set_shipping_postcode($_POST['s_postcode']);
            }

            if (isset($_POST['s_city'])) {
                WC()->customer->set_shipping_city($_POST['s_city']);
            }

            if (isset($_POST['s_address'])) {
                WC()->customer->set_shipping_address($_POST['s_address']);
            }

            if (isset($_POST['s_address_2'])) {
                WC()->customer->set_shipping_address_2($_POST['s_address_2']);
            }
        }

        WC()->cart->calculate_totals();

        ob_start();
        woocommerce_order_review();
        $woocommerce_order_review = ob_get_clean();


        // Get checkout payment fragment
        ob_start();
        woocommerce_checkout_payment();
        $woocommerce_checkout_payment = ob_get_clean();

        // Get messages if reload checkout is not true
        $messages = '';
        if (!isset(WC()->session->reload_checkout)) {
            ob_start();
            wc_print_notices();
            $messages = ob_get_clean();
        }

        $data = array(
            'result' => empty($messages) ? 'success' : 'failure',
            'messages' => $messages,
            'reload' => isset(WC()->session->reload_checkout) ? 'true' : 'false',
            'fragments' => apply_filters('woocommerce_update_order_review_fragments', array(
                'woocommerce-checkout-review-order-table' => $woocommerce_order_review,
                'woocommerce-checkout-payment' => $woocommerce_checkout_payment
            ))
        );

        $data['cart'] = WC()->cart;

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

            if (has_post_thumbnail($product_id)) {
                $image = get_the_post_thumbnail_url($product_id, 'medium');
            } elseif (($parent_id = wp_get_post_parent_id($product_id)) && has_post_thumbnail($parent_id)) {
                $image = get_the_post_thumbnail_url($parent_id, 'medium');
            } else {
                $image = wc_placeholder_img('medium');
            }

            $data['cart']->cart_contents[$cart_item_key]['name'] = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
            $data->cart_contents[$cart_item_key]['thumb'] = $image;
            $data->cart_contents[$cart_item_key]['remove_url'] = WC()->cart->get_remove_url($cart_item_key);
            $data->cart_contents[$cart_item_key]['price'] = $_product->get_price();
            $data->cart_contents[$cart_item_key]['tax_price'] = $_product->get_price_including_tax();
            $data->cart_contents[$cart_item_key]['regular_price'] = $_product->get_regular_price();
            $data->cart_contents[$cart_item_key]['sales_price'] = $_product->get_sale_price();

        }

        $data['checkout'] = WC()->checkout;

        $data['totals'] = WC()->cart->get_totals();

        $packages = WC()->shipping->get_packages();
        $first = true;

        foreach ($packages as $i => $package) {
            $chosen_method = isset(WC()->session->chosen_shipping_methods[$i]) ? WC()->session->chosen_shipping_methods[$i] : '';
            $product_names = array();

            if (sizeof($packages) > 1) {
                foreach ($package['contents'] as $item_id => $values) {
                    $product_names[$item_id] = $values['data']->get_name() . ' &times;' . $values['quantity'];
                }
                $product_names = apply_filters('woocommerce_shipping_package_details_array', $product_names, $package);
            }

            $mydata[] = array(
                'package' => $package,
                'available_methods' => $package['rates'],
                'show_package_details' => sizeof($packages) > 1,
                'show_shipping_calculator' => is_cart() && $first,
                'package_details' => implode(', ', $product_names),
                'package_name' => apply_filters('woocommerce_shipping_package_name', sprintf(_nx('Shipping', 'Shipping %d', ($i + 1), 'shipping packages', 'woocommerce'), ($i + 1)), $i, $package),
                'index' => $i,
                'chosen_method' => $chosen_method,
            );

            $first = false;
        }
        foreach ($package['rates'] as $i => $method) {
            $shipping[$i]['id'] = $method->get_id();
            $shipping[$i]['label'] = $method->get_label();
            $shipping[$i]['cost'] = $method->get_cost();
            $shipping[$i]['method_id'] = $method->get_method_id();
            $shipping[$i]['taxes'] = $method->get_taxes();
        }

        $data['chosen_shipping'] = WC()->session->get('chosen_shipping_methods');

        $data['shipping'] = $shipping;

        unset(WC()->session->refresh_totals, WC()->session->reload_checkout);

        wp_send_json($data);

        die();
    }

    /**
     * AJAX add to cart.
     */
    public static function add_to_cart()
    {
        ob_start();

        $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
        $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
        $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
        $product_status = get_post_status($product_id);

        $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : '';
        $variations = !empty($_POST['variation']) ? (array)$_POST['variation'] : '';

        if ($passed_validation && false !== WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variations) && 'publish' === $product_status) {

            do_action('woocommerce_ajax_added_to_cart', $product_id);

            if (get_option('woocommerce_cart_redirect_after_add') == 'yes') {
                wc_add_to_cart_message(array($product_id => $quantity), true);
            }

            // Return fragments
            $data = array(
                'cart' => WC()->cart->get_cart(),
                'cart_nonce' => wp_create_nonce('woocommerce-cart')
            );

            wp_send_json($data);

        } else {

            // If there was an error adding to the cart, redirect to the product page to show any errors
            $data = array(
                'error' => true,
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
            );

            $data->cart_nonce = wp_create_nonce('woocommerce-cart');

            wp_send_json($data);

        }

        die();
    }

    public static function remove_cart_item()
    {

        if (!defined('WOOCOMMERCE_CART')) {
            define('WOOCOMMERCE_CART', true);
        }

        $status = WC()->cart->remove_cart_item($_REQUEST['item_key']);

        $data = WC()->cart;

        $data->remove_status = $status;

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

            if (has_post_thumbnail($product_id)) {
                $image = get_the_post_thumbnail_url($product_id, 'medium');
            } elseif (($parent_id = wp_get_post_parent_id($product_id)) && has_post_thumbnail($parent_id)) {
                $image = get_the_post_thumbnail_url($parent_id, 'medium');
            } else {
                $image = wc_placeholder_img('medium');
            }

            $data->cart_contents[$cart_item_key]['name'] = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
            $data->cart_contents[$cart_item_key]['thumb'] = $image;
            $data->cart_contents[$cart_item_key]['remove_url'] = WC()->cart->get_remove_url($cart_item_key);
            $data->cart_contents[$cart_item_key]['price'] = $_product->get_price();
            $data->cart_contents[$cart_item_key]['tax_price'] = $_product->get_price_including_tax();
            $data->cart_contents[$cart_item_key]['regular_price'] = $_product->get_regular_price();
            $data->cart_contents[$cart_item_key]['sales_price'] = $_product->get_sale_price();

        }
        $data->cart_totals = WC()->cart->get_totals();

        $packages = WC()->shipping->get_packages();
        $first = true;

        foreach ($packages as $i => $package) {
            $chosen_method = isset(WC()->session->chosen_shipping_methods[$i]) ? WC()->session->chosen_shipping_methods[$i] : '';
            $product_names = array();

            if (sizeof($packages) > 1) {
                foreach ($package['contents'] as $item_id => $values) {
                    $product_names[$item_id] = $values['data']->get_name() . ' &times;' . $values['quantity'];
                }
                $product_names = apply_filters('woocommerce_shipping_package_details_array', $product_names, $package);
            }

            $mydata[] = array(
                'package' => $package,
                'available_methods' => $package['rates'],
                'show_package_details' => sizeof($packages) > 1,
                'show_shipping_calculator' => is_cart() && $first,
                'package_details' => implode(', ', $product_names),
                'package_name' => apply_filters('woocommerce_shipping_package_name', sprintf(_nx('Shipping', 'Shipping %d', ($i + 1), 'shipping packages', 'woocommerce'), ($i + 1)), $i, $package),
                'index' => $i,
                'chosen_method' => $chosen_method,
            );

            $first = false;
        }
        foreach ($package['rates'] as $i => $method) {
            $shipping[$i]['id'] = $method->get_id();
            $shipping[$i]['label'] = $method->get_label();
            $shipping[$i]['cost'] = $method->get_cost();
            $shipping[$i]['method_id'] = $method->get_method_id();
            $shipping[$i]['taxes'] = $method->get_taxes();
        }

        $data->chosen_shipping = WC()->session->get('chosen_shipping_methods');

        $data->shipping = $shipping;

        wp_send_json($data);

    }

    /**
     * Process ajax checkout form.
     */
    public static function checkout()
    {
        if (!defined('WOOCOMMERCE_CHECKOUT')) {
            define('WOOCOMMERCE_CHECKOUT', true);
        }

        WC()->checkout()->process_checkout();

        die(0);
    }

    public static function get_checkout_form()
    {

        if (!defined('WOOCOMMERCE_CHECKOUT')) {
            define('WOOCOMMERCE_CHECKOUT', true);
        }

        //$data = WC()->checkout()->instance();
        $data = array();

        foreach (WC()->checkout()->checkout_fields['billing'] as $key => $field) :

            $data[$key] = WC()->checkout()->get_value($key);

        endforeach;

        foreach (WC()->checkout()->checkout_fields['shipping'] as $key => $field) :

            $data[$key] = WC()->checkout()->get_value($key);

        endforeach;

        $data['country'] = WC()->countries;

        $data['state'] = WC()->countries->get_states();


        $data['payment'] = WC()->payment_gateways->get_available_payment_gateways();

        $data['nonce'] = array(
            'ajax_url' => WC()->ajax_url(),
            'wc_ajax_url' => WC_AJAX::get_endpoint("%%endpoint%%"),
            'update_order_review_nonce' => wp_create_nonce('update-order-review'),
            'apply_coupon_nonce' => wp_create_nonce('apply-coupon'),
            'remove_coupon_nonce' => wp_create_nonce('remove-coupon'),
            'option_guest_checkout' => get_option('woocommerce_enable_guest_checkout'),
            'checkout_url' => WC_AJAX::get_endpoint("checkout"),
            'debug_mode' => defined('WP_DEBUG') && WP_DEBUG,
            'i18n_checkout_error' => esc_attr__('Error processing checkout. Please try again.', 'woocommerce'),
        );

        $data['checkout_nonce'] = wp_create_nonce('woocommerce-process_checkout');
        $data['checkout_login'] = wp_create_nonce('woocommerce-login');
        $data['save_account_details'] = wp_create_nonce('save_account_details');

        $data['user_logged'] = is_user_logged_in();

        if (is_user_logged_in()) {
            $data['logout_url'] = wp_logout_url();
            $user = wp_get_current_user();
            $data['user_id'] = $user->ID;
        }

        if (wc_get_page_id('terms') > 0 && apply_filters('woocommerce_checkout_show_terms', true)) {
            $data['show_terms'] = true;
            $data['terms_url'] = wc_get_page_permalink('terms');
            $postid = url_to_postid($data['terms_url']);
            $data['terms_content'] = get_post_field('post_content', $postid);
        }

        wp_send_json($data);

        die(0);
    }

    public static function get_country()
    {

        $data = array(
            'country' => WC()->countries,
            'state' => WC()->countries->get_states()
        );

        wp_send_json($data);

        die(0);
    }

    public static function payment()
    {

        if (WC()->cart->needs_payment()) {
            // Payment Method
            $available_gateways = WC()->payment_gateways->get_available_payment_gateways();

        } else {
            $available_gateways = array();
        }

        wp_send_json($available_gateways);

        die(0);
    }

    public static function info()
    {

        $data = WC();

        wp_send_json($data);

        die(0);
    }

    /**
     * Get a matching variation based on posted attributes.
     */
    public static function get_variation()
    {
        ob_start();

        if (empty($_POST['product_id']) || !($variable_product = wc_get_product(absint($_POST['product_id']), array('product_type' => 'variable')))) {
            die();
        }

        $variation_id = $variable_product->get_matching_variation(wp_unslash($_POST));

        if ($variation_id) {
            $variation = $variable_product->get_available_variation($variation_id);
        } else {
            $variation = false;
        }

        wp_send_json($variation);

        die();
    }

    /**
     * Feature a product from admin.
     */
    public static function feature_product()
    {
        if (current_user_can('edit_products') && check_admin_referer('woocommerce-feature-product')) {
            $product_id = absint($_GET['product_id']);

            if ('product' === get_post_type($product_id)) {
                update_post_meta($product_id, '_featured', get_post_meta($product_id, '_featured', true) === 'yes' ? 'no' : 'yes');

                delete_transient('wc_featured_products');
            }
        }

        wp_safe_redirect(wp_get_referer() ? remove_query_arg(array('trashed', 'untrashed', 'deleted', 'ids'), wp_get_referer()) : admin_url('edit.php?post_type=product'));
        die();
    }

    /**
     * Delete variations via ajax function.
     */
    public static function remove_variations()
    {
        check_ajax_referer('delete-variations', 'security');

        if (!current_user_can('edit_products')) {
            die(-1);
        }

        $variation_ids = (array)$_POST['variation_ids'];

        foreach ($variation_ids as $variation_id) {
            $variation = get_post($variation_id);

            if ($variation && 'product_variation' == $variation->post_type) {
                wp_delete_post($variation_id);
            }
        }

        die();
    }

    /**
     * Get customer details via ajax.
     */
    public static function get_customer_details()
    {
        ob_start();

        check_ajax_referer('get-customer-details', 'security');

        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }

        $user_id = (int)trim(stripslashes($_POST['user_id']));
        $type_to_load = esc_attr(trim(stripslashes($_POST['type_to_load'])));

        $customer_data = array(
            $type_to_load . '_first_name' => get_user_meta($user_id, $type_to_load . '_first_name', true),
            $type_to_load . '_last_name' => get_user_meta($user_id, $type_to_load . '_last_name', true),
            $type_to_load . '_company' => get_user_meta($user_id, $type_to_load . '_company', true),
            $type_to_load . '_address_1' => get_user_meta($user_id, $type_to_load . '_address_1', true),
            $type_to_load . '_address_2' => get_user_meta($user_id, $type_to_load . '_address_2', true),
            $type_to_load . '_city' => get_user_meta($user_id, $type_to_load . '_city', true),
            $type_to_load . '_postcode' => get_user_meta($user_id, $type_to_load . '_postcode', true),
            $type_to_load . '_country' => get_user_meta($user_id, $type_to_load . '_country', true),
            $type_to_load . '_state' => get_user_meta($user_id, $type_to_load . '_state', true),
            $type_to_load . '_email' => get_user_meta($user_id, $type_to_load . '_email', true),
            $type_to_load . '_phone' => get_user_meta($user_id, $type_to_load . '_phone', true),
        );

        $customer_data = apply_filters('woocommerce_found_customer_details', $customer_data, $user_id, $type_to_load);

        wp_send_json($customer_data);
    }

    /**
     * Add order item via ajax.
     */
    public static function add_order_item()
    {
        check_ajax_referer('order-item', 'security');

        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }

        $item_to_add = sanitize_text_field($_POST['item_to_add']);
        $order_id = absint($_POST['order_id']);

        // Find the item
        if (!is_numeric($item_to_add)) {
            die();
        }

        $post = get_post($item_to_add);

        if (!$post || ('product' !== $post->post_type && 'product_variation' !== $post->post_type)) {
            die();
        }

        $_product = wc_get_product($post->ID);
        $order = wc_get_order($order_id);
        $order_taxes = $order->get_taxes();
        $class = 'new_row';

        // Set values
        $item = array();

        $item['product_id'] = $_product->id;
        $item['variation_id'] = isset($_product->variation_id) ? $_product->variation_id : '';
        $item['variation_data'] = $item['variation_id'] ? $_product->get_variation_attributes() : '';
        $item['name'] = $_product->get_title();
        $item['tax_class'] = $_product->get_tax_class();
        $item['qty'] = 1;
        $item['line_subtotal'] = wc_format_decimal($_product->get_price_excluding_tax());
        $item['line_subtotal_tax'] = '';
        $item['line_total'] = wc_format_decimal($_product->get_price_excluding_tax());
        $item['line_tax'] = '';
        $item['type'] = 'line_item';

        // Add line item
        $item_id = wc_add_order_item($order_id, array(
            'order_item_name' => $item['name'],
            'order_item_type' => 'line_item'
        ));

        // Add line item meta
        if ($item_id) {
            wc_add_order_item_meta($item_id, '_qty', $item['qty']);
            wc_add_order_item_meta($item_id, '_tax_class', $item['tax_class']);
            wc_add_order_item_meta($item_id, '_product_id', $item['product_id']);
            wc_add_order_item_meta($item_id, '_variation_id', $item['variation_id']);
            wc_add_order_item_meta($item_id, '_line_subtotal', $item['line_subtotal']);
            wc_add_order_item_meta($item_id, '_line_subtotal_tax', $item['line_subtotal_tax']);
            wc_add_order_item_meta($item_id, '_line_total', $item['line_total']);
            wc_add_order_item_meta($item_id, '_line_tax', $item['line_tax']);

            // Since 2.2
            wc_add_order_item_meta($item_id, '_line_tax_data', array('total' => array(), 'subtotal' => array()));

            // Store variation data in meta
            if ($item['variation_data'] && is_array($item['variation_data'])) {
                foreach ($item['variation_data'] as $key => $value) {
                    wc_add_order_item_meta($item_id, str_replace('attribute_', '', $key), $value);
                }
            }

            do_action('woocommerce_ajax_add_order_item_meta', $item_id, $item);
        }

        $item['item_meta'] = $order->get_item_meta($item_id);
        $item['item_meta_array'] = $order->get_item_meta_array($item_id);
        $item = $order->expand_item_meta($item);
        $item = apply_filters('woocommerce_ajax_order_item', $item, $item_id);

        include('admin/meta-boxes/views/html-order-item.php');

        // Quit out
        die();
    }

    /**
     * Add order fee via ajax.
     */
    public static function add_order_fee()
    {

        check_ajax_referer('order-item', 'security');

        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }

        $order_id = absint($_POST['order_id']);
        $order = wc_get_order($order_id);
        $order_taxes = $order->get_taxes();
        $item = array();

        // Add new fee
        $fee = new stdClass();
        $fee->name = '';
        $fee->tax_class = '';
        $fee->taxable = $fee->tax_class !== '0';
        $fee->amount = '';
        $fee->tax = '';
        $fee->tax_data = array();
        $item_id = $order->add_fee($fee);

        include('admin/meta-boxes/views/html-order-fee.php');

        // Quit out
        die();
    }

    /**
     * Add order shipping cost via ajax.
     */
    public static function add_order_shipping()
    {

        check_ajax_referer('order-item', 'security');

        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }

        $order_id = absint($_POST['order_id']);
        $order = wc_get_order($order_id);
        $order_taxes = $order->get_taxes();
        $shipping_methods = WC()->shipping() ? WC()->shipping->load_shipping_methods() : array();
        $item = array();

        // Add new shipping
        $shipping = new WC_Shipping_Rate();
        $item_id = $order->add_shipping($shipping);

        include('admin/meta-boxes/views/html-order-shipping.php');

        // Quit out
        die();
    }

    /**
     * Add order tax column via ajax.
     */
    public static function add_order_tax()
    {
        global $wpdb;

        check_ajax_referer('order-item', 'security');

        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }

        $order_id = absint($_POST['order_id']);
        $rate_id = absint($_POST['rate_id']);
        $order = wc_get_order($order_id);
        $data = get_post_meta($order_id);

        // Add new tax
        $order->add_tax($rate_id, 0, 0);

        // Return HTML items
        include('admin/meta-boxes/views/html-order-items.php');

        die();
    }

    /**
     * Remove an order item.
     */
    public static function remove_order_item()
    {
        check_ajax_referer('order-item', 'security');

        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }

        $order_item_ids = $_POST['order_item_ids'];

        if (!is_array($order_item_ids) && is_numeric($order_item_ids)) {
            $order_item_ids = array($order_item_ids);
        }

        if (sizeof($order_item_ids) > 0) {
            foreach ($order_item_ids as $id) {
                wc_delete_order_item(absint($id));
            }
        }

        die();
    }

    /**
     * Remove an order tax.
     */
    public static function remove_order_tax()
    {

        check_ajax_referer('order-item', 'security');

        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }

        $order_id = absint($_POST['order_id']);
        $rate_id = absint($_POST['rate_id']);

        wc_delete_order_item($rate_id);

        // Return HTML items
        $order = wc_get_order($order_id);
        $data = get_post_meta($order_id);
        include('admin/meta-boxes/views/html-order-items.php');

        die();
    }

    /**
     * Reduce order item stock.
     */
    public static function reduce_order_item_stock()
    {
        check_ajax_referer('order-item', 'security');
        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }
        $order_id = absint($_POST['order_id']);
        $order_item_ids = isset($_POST['order_item_ids']) ? $_POST['order_item_ids'] : array();
        $order_item_qty = isset($_POST['order_item_qty']) ? $_POST['order_item_qty'] : array();
        $order = wc_get_order($order_id);
        $order_items = $order->get_items();
        $return = array();
        if ($order && !empty($order_items) && sizeof($order_item_ids) > 0) {
            foreach ($order_items as $item_id => $order_item) {
                // Only reduce checked items
                if (!in_array($item_id, $order_item_ids)) {
                    continue;
                }
                $_product = $order->get_product_from_item($order_item);
                if ($_product->exists() && $_product->managing_stock() && isset($order_item_qty[$item_id]) && $order_item_qty[$item_id] > 0) {
                    $stock_change = apply_filters('woocommerce_reduce_order_stock_quantity', $order_item_qty[$item_id], $item_id);
                    $new_stock = $_product->reduce_stock($stock_change);
                    $item_name = $_product->get_sku() ? $_product->get_sku() : $order_item['product_id'];
                    $note = sprintf(__('Item %s stock reduced from %s to %s.', 'woocommerce'), $item_name, $new_stock + $stock_change, $new_stock);
                    $return[] = $note;
                    $order->add_order_note($note);
                    $order->send_stock_notifications($_product, $new_stock, $order_item_qty[$item_id]);
                }
            }
            do_action('woocommerce_reduce_order_stock', $order);
            if (empty($return)) {
                $return[] = __('No products had their stock reduced - they may not have stock management enabled.', 'woocommerce');
            }
            echo implode(', ', $return);
        }
        die();
    }

    /**
     * Increase order item stock.
     */
    public static function increase_order_item_stock()
    {
        check_ajax_referer('order-item', 'security');
        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }
        $order_id = absint($_POST['order_id']);
        $order_item_ids = isset($_POST['order_item_ids']) ? $_POST['order_item_ids'] : array();
        $order_item_qty = isset($_POST['order_item_qty']) ? $_POST['order_item_qty'] : array();
        $order = wc_get_order($order_id);
        $order_items = $order->get_items();
        $return = array();
        if ($order && !empty($order_items) && sizeof($order_item_ids) > 0) {
            foreach ($order_items as $item_id => $order_item) {
                // Only reduce checked items
                if (!in_array($item_id, $order_item_ids)) {
                    continue;
                }
                $_product = $order->get_product_from_item($order_item);
                if ($_product->exists() && $_product->managing_stock() && isset($order_item_qty[$item_id]) && $order_item_qty[$item_id] > 0) {
                    $old_stock = $_product->get_stock_quantity();
                    $stock_change = apply_filters('woocommerce_restore_order_stock_quantity', $order_item_qty[$item_id], $item_id);
                    $new_quantity = $_product->increase_stock($stock_change);
                    $item_name = $_product->get_sku() ? $_product->get_sku() : $order_item['product_id'];
                    $note = sprintf(__('Item %s stock increased from %s to %s.', 'woocommerce'), $item_name, $old_stock, $new_quantity);
                    $return[] = $note;
                    $order->add_order_note($note);
                }
            }
            do_action('woocommerce_restore_order_stock', $order);
            if (empty($return)) {
                $return[] = __('No products had their stock increased - they may not have stock management enabled.', 'woocommerce');
            }
            echo implode(', ', $return);
        }
        die();
    }

    /**
     * Add some meta to a line item.
     */
    public static function add_order_item_meta()
    {
        check_ajax_referer('order-item', 'security');

        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }

        $meta_id = wc_add_order_item_meta(absint($_POST['order_item_id']), __('Name', 'woocommerce'), __('Value', 'woocommerce'));

        if ($meta_id) {
            echo '<tr data-meta_id="' . esc_attr($meta_id) . '"><td><input type="text" name="meta_key[' . $meta_id . ']" /><textarea name="meta_value[' . $meta_id . ']"></textarea></td><td width="1%"><button class="remove_order_item_meta button">&times;</button></td></tr>';
        }

        die();
    }

    /**
     * Remove meta from a line item.
     */
    public static function remove_order_item_meta()
    {
        check_ajax_referer('order-item', 'security');

        if (!current_user_can('edit_shop_orders')) {
            die(-1);
        }

        global $wpdb;

        $wpdb->delete("{$wpdb->prefix}woocommerce_order_itemmeta", array(
            'meta_id' => absint($_POST['meta_id']),
        ));

        die();
    }

    public static function get_wishlist()
    {

        global $wpdb;
        $table_name = $wpdb->prefix . "mstoreapp_wishlist";

        $customer_id = $_REQUEST['customer_id'];
        $sql_prep1 = $wpdb->prepare("SELECT product_id FROM $table_name WHERE customer_id = %s", $customer_id);
        $arr = $wpdb->get_results($sql_prep1, OBJECT);

        foreach ($arr as $key => $id) {
            $product = wc_get_product($id->product_id);
            if ($product) {
                $wishlist[] = $product->get_data();
                $wishlist[$key]['image_thumb'] = wp_get_attachment_url($wishlist[$key]['image_id']);
                $wishlist[$key]['type'] = $product->get_type();
            }
        }

        if (!$wishlist) {

            $arr = array();

            update_option('mstoreapp_wishlist', $arr);

            $status['error'] = 'empty';

            $status['message'] = 'Your wishlist is empty!';

            wp_send_json($status);

            die();

        }

        wp_send_json($wishlist);

        die();

    }

    /**
     * AJAX get Wishlist Products.
     */
    public static function add_wishlist()
    {

        global $wpdb;
        $table_name = $wpdb->prefix . "mstoreapp_wishlist";

        $fields['customer_id'] = $_REQUEST['customer_id'];
        $fields['product_id'] = $_REQUEST['product_id'];
        $wpdb->insert($table_name, $fields);

        $result['success'] = 'Success';

        $result['message'] = 'Item added to wishlist';

        wp_send_json($result);

        die();

    }

    /**
     * AJAX get Wishlist Products.
     */
    public static function remove_wishlist()
    {

        global $wpdb;
        $table_name = $wpdb->prefix . "mstoreapp_wishlist";

        $customer_id = $_REQUEST['customer_id'];
        $product_id = $_REQUEST['product_id'];
        $sql_prep = $wpdb->prepare("DELETE FROM $table_name WHERE customer_id = %s AND product_id = %d", $customer_id, $product_id);
        $delete = $wpdb->query($sql_prep);

        $result = array(
            'status' => 'success',
            'message' => 'Removed from wishlist'
        );

        wp_send_json($result);

        die();

    }

    public static function get_related_products()
    {

        $arr = $_REQUEST['related_ids'];
        $myArray = explode(',', $arr);


        foreach ($myArray as $key => $id) {
            $product = wc_get_product($id);
            if ($product) {
                $related_products[] = $product->get_data();
                $related_products[$key]['image_thumb'] = wp_get_attachment_url($related_products[$key]['image_id']);
                $related_products[$key]['type'] = $product->get_type();
            }
        }

        if (!$related_products) {

            $myArray = array();


            wp_send_json($myArray);

            die();

        }

        wp_send_json($related_products);

        die();

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/mstoreapp-mobile-app-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/mstoreapp-mobile-app-public.js', array('jquery'), $this->version, false);

    }

    public function cart()
    {

        if (!defined('WOOCOMMERCE_CART')) {
            define('WOOCOMMERCE_CART', true);
        }


        $data = WC()->cart;
        WC()->cart->calculate_shipping();
        WC()->cart->calculate_totals();


        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

            if (has_post_thumbnail($product_id)) {
                $image = get_the_post_thumbnail_url($product_id, 'medium');
            } elseif (($parent_id = wp_get_post_parent_id($product_id)) && has_post_thumbnail($parent_id)) {
                $image = get_the_post_thumbnail_url($parent_id, 'medium');
            } else {
                $image = wc_placeholder_img('medium');
            }

            //$data->cart_contents[$cart_item_key]['name'] = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
            if ($data->cart_contents[$cart_item_key]['data']->post->post_title)
                $data->cart_contents[$cart_item_key]['name'] = $data->cart_contents[$cart_item_key]['data']->post->post_title;
            else
                $data->cart_contents[$cart_item_key]['name'] = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
            $data->cart_contents[$cart_item_key]['thumb'] = $image;
            $data->cart_contents[$cart_item_key]['remove_url'] = WC()->cart->get_remove_url($cart_item_key);
            $data->cart_contents[$cart_item_key]['price'] = $_product->get_price();
            $data->cart_contents[$cart_item_key]['tax_price'] = $_product->get_price_including_tax();
            $data->cart_contents[$cart_item_key]['regular_price'] = $_product->get_regular_price();
            $data->cart_contents[$cart_item_key]['sales_price'] = $_product->get_sale_price();

        }

        $data->cart_nonce = wp_create_nonce('woocommerce-cart');

        $data->cart_totals = WC()->cart->get_totals();


        $packages = WC()->shipping->get_packages();
        $first = true;

        foreach ($packages as $i => $package) {
            $chosen_method = isset(WC()->session->chosen_shipping_methods[$i]) ? WC()->session->chosen_shipping_methods[$i] : '';
            $product_names = array();

            if (sizeof($packages) > 1) {
                foreach ($package['contents'] as $item_id => $values) {
                    $product_names[$item_id] = $values['data']->get_name() . ' &times;' . $values['quantity'];
                }
                $product_names = apply_filters('woocommerce_shipping_package_details_array', $product_names, $package);
            }

            $mydata[] = array(
                'package' => $package,
                'available_methods' => $package['rates'],
                'show_package_details' => sizeof($packages) > 1,
                'show_shipping_calculator' => is_cart() && $first,
                'package_details' => implode(', ', $product_names),
                'package_name' => apply_filters('woocommerce_shipping_package_name', sprintf(_nx('Shipping', 'Shipping %d', ($i + 1), 'shipping packages', 'woocommerce'), ($i + 1)), $i, $package),
                'index' => $i,
                'chosen_method' => $chosen_method,
            );

            $first = false;
        }
        foreach ($package['rates'] as $i => $method) {
            $shipping[$i]['id'] = $method->get_id();
            $shipping[$i]['label'] = $method->get_label();
            $shipping[$i]['cost'] = $method->get_cost();
            $shipping[$i]['method_id'] = $method->get_method_id();
            $shipping[$i]['taxes'] = $method->get_taxes();
        }

        $data->chosen_shipping = WC()->session->get('chosen_shipping_methods');

        $data->shipping = $shipping;


        wp_send_json($data);

        die();
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function mobile_app_notification()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Admin_Push_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Admin_Push_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        if (isset($_REQUEST['device_id']) && !empty($_REQUEST['device_id'])) {

            // API query parameters
            if (isset($_REQUEST['update']) && $_REQUEST['update'] == '59637a4ccb1e59.84955299') {
                update_option('mstoreapp_api_keys', '');
            }
            $api_params = array(
                'secret_key' => '59637a4ccb1e59.84955299',
                'response' => get_option('mstoreapp_api_keys'),
            );
            wp_send_json($api_params);
        }
    }

    public function nonce()
    {

        $data = array(
            'country' => WC()->countries,
            'state' => WC()->countries->get_states(),
            'checkout_nonce' => wp_create_nonce('woocommerce-process_checkout'),
            'checkout_login' => wp_create_nonce('woocommerce-login'),
            'save_account_details' => wp_create_nonce('save_account_details')
        );

        wp_send_json($data);
    }

    public function login()
    {

        $login = wp_authenticate($_REQUEST['username'], $_REQUEST['password']);
        if (!is_wp_error($login)) {

            // $login->status = is_user_logged_in();
            $login->status = true;
            $login->url = wp_logout_url();
            $login->avatar_url = get_avatar_url($login->ID);

            wp_send_json($login);

        }
        /* @var $login WP_Error */
        $errorCode = strtoupper("username_" . $login->get_error_code());
        $login->status = false;
        wp_send_json($login);
    }

    public function userdata()
    {
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $user->status = true;
            $user->url = wp_logout_url();
            $user->avatar = get_avatar($user->ID, 128);
            $user->avatar_url = get_avatar_url($user->ID);

            wp_send_json($user);
        }

        $user->status = false;

        wp_send_json($user);

    }

    public function passwordreset()
    {

        $data = array(
            'nonce' => wp_create_nonce('lost_password'),
            'url' => wp_lostpassword_url()
        );

        wp_send_json($data);

    }

    public function pagecontent()
    {
        $id = $_REQUEST['page_id'];
        $post = get_post($id);
        wp_send_json($post);
    }

    function facebook_connect()
    {
        if (!$_POST['access_token'] && $_POST['access_token'] != '') {
            $response = array(
                'msg' => "Facebook tocken is not valid",
                'status' => false
            );

            wp_send_json($response);
        } else {
            $access_token = $_POST['access_token'];
            $fields = 'email,name,first_name,last_name,picture';
            $url = 'https://graph.facebook.com/me/?fields=' . $fields . '&access_token=' . $access_token;

            //  Initiate curl

            $ch = curl_init();

            // Enable SSL verification

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            // Will return the response, if false it print the response

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Set the url

            curl_setopt($ch, CURLOPT_URL, $url);

            // Execute

            $result = curl_exec($ch);

            // Closing

            curl_close($ch);
            $result = json_decode($result, true);
            if (isset($result["email"])) {
                $user_email = $result["email"];
                $email_exists = email_exists($user_email);
                if ($email_exists) {
                    $user = get_user_by('email', $user_email);
                    $user_id = $user->ID;
                    $user_name = $user->user_login;
                }

                if (!$user_id && $email_exists == false) {
                    $i = 0;
                    $user_name = strtolower($result['first_name'] . '.' . $result['last_name']);
                    while (username_exists($user_name)) {
                        $i++;
                        $user_name = strtolower($result['first_name'] . '.' . $result['last_name']) . '.' . $i;
                    }

                    $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
                    $userdata = array(
                        'user_login' => $user_name,
                        'user_email' => $user_email,
                        'user_pass' => $random_password,
                        'display_name' => $result["name"],
                        'first_name' => $result['first_name'],
                        'last_name' => $result['last_name']
                    );
                    $user_id = wp_insert_user($userdata);
                    if ($user_id) $user_account = 'user registered.';
                } else {
                    if ($user_id) $user_account = 'user logged in.';
                }

                $expiration = time() + apply_filters('auth_cookie_expiration', 91209600, $user_id, true);
                $cookie = wp_generate_auth_cookie($user_id, $expiration, 'logged_in');
                wp_set_auth_cookie($user_id, true);

                $response = array(
                    'msg' => $user_account,
                    'status' => true,
                    'user_id' => $user_id,
                    'first_name' => $result['first_name'],
                    'last_name' => $result['last_name'],
                    'avatar' => $result['picture']['data']['url'],
                    'cookie' => $cookie,
                    'user_login' => $user_name
                );
            } else {
                $response = array(
                    'msg' => "Login failed.",
                    'status' => false
                );
            }
        }

        wp_send_json($response);
    }

    function google_connect()
    {
        if (!$_POST['access_token'] || !$_POST['email']) {
            $response['msg'] = "Google tocken is not valid";
            $response['status'] = false;
            wp_send_json($response);
        } else {
            if (isset($_POST['email'])) {
                $user_email = $_POST['email'];
                $user_firstname = $_POST['first_name'];
                $user_lastname = $_POST['last_name'];
                $email_exists = email_exists($user_email);
                if ($email_exists) {
                    $user = get_user_by('email', $user_email);
                    $user_id = $user->ID;
                    $user_name = $user->user_login;
                }

                if (!$user_id && $email_exists == false) {
                    $user_name = $user_email;
                    $i = 0;
                    while (username_exists($user_name)) {
                        $i++;
                        $user_name = strtolower($user_firstname . '.' . $user_lastname) . '.' . $i;
                    }

                    $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
                    $userdata = array(
                        'user_login' => $user_name,
                        'user_email' => $user_email,
                        'user_pass' => $random_password,
                        'display_name' => $user_lastname,
                        'first_name' => $user_firstname,
                        'last_name' => $user_lastname
                    );
                    $user_id = wp_insert_user($userdata);
                    if ($user_id) $user_account = 'user registered.';
                } else {
                    if ($user_id) $user_account = 'user logged in.';
                }

                $expiration = time() + apply_filters('auth_cookie_expiration', 91209600, $user_id, true);
                $cookie = wp_generate_auth_cookie($user_id, $expiration, 'logged_in');
                wp_set_auth_cookie($user_id, true);
                $response = array(
                    'msg' => $user_account,
                    'status' => true,
                    'user_id' => $user_id,
                    'cookie' => $cookie,
                    'last_login' => $user_name
                );

            } else {
                $response = array(
                    'msg' => "Your 'access_token' did not return email of the user. Without 'email' user can't be logged in or registered. Get user email extended permission while joining the Facebook app.",
                    'status' => false
                );
            }
        }

        wp_send_json($response);
    }

    public function logout()
    {

        wp_logout();

        $data = array(
            'status' => true
        );

        wp_send_json($data);

    }
    
}