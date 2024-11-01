<?php

/**
 * The block-specific functionality of the plugin.
 *
 * @link       http://mstoreapp.com
 * @since      1.0.0
 *
 * @package    Mstoreapp_Mobile_App
 * @subpackage Mstoreapp_Mobile_App/block
 */

/**
 * The block-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the block-specific stylesheet and JavaScript.
 *
 * @package    Mstoreapp_Mobile_App
 * @subpackage Mstoreapp_Mobile_App/block
 * @author     Mstoreapp <support@mstoreapp.com>
 */
//----------------------------------------------------------------------------------------------------------------------------------------------

class Mstoreapp_Mobile_App_Block {

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

        global $status, $page;

	}

//------------------------------------------------------------------Plugin Menu Page----------------------------------------------------------- 

    public function mstoreapp_mobile_app_menu() {

        add_menu_page('Mstoreapp Mobile App', 'Mobile Blocks', 'manage_options', 'mstoreapp-mobile-app', array(&$this, 'mstoreapp_app_block_page_handler'), 'dashicons-smartphone');

        add_submenu_page('mstoreapp-mobile-app', __('Add New Block', 'mstoreapp_app'),
            __('Add New Block', 'mstoreapp_app'), 'activate_plugins', 'mstoreapp_app_block_form', array(&$this, 'mstoreapp_app_block_form_page_handler'));
    
    }
//---------------------------------------------------------------------------------------------------------------------------------------------- 


    public function mstoreapp_app_block_page_handler()
    {

        wp_enqueue_media();
        global $wpdb;

        $table = new Custom_Table_app_List_Table();     
        $table->prepare_items();
        $message = '';

        if ('delete' === $table->current_action()) {

            $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'mstoreapp_app'), count($_REQUEST['id'])) . '</p></div>';
        }
    ?>
 
    <div class="wrap">

        <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>

        <h2>

        <?php 
             _e('Mstore Block', 'mstoreapp_app')?> 

                <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=mstoreapp_app_block_form');?>" >
                <?php _e('Add new', 'mstoreapp_app')?>   
                </a>

        </h2>

        <?php echo $message; ?>
        <form id="block-table" method="post">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
             <?php $table->search_box('Search Name', 's'); ?>
            <?php $table->display() ?>


        </form>

    </div>
    <?php
    }
//------------------------------------------------------------Block_page_handler-----------------------------------------------------------------

    public function mstoreapp_app_block_form_page_handler()
    {

        global $wpdb;
        $table_name = $wpdb->prefix . 'mstoreapp_blocks'; // do not forget about tables prefix
        $message = '';
        $notice = '';

        // This is default $item which will be used for new records

        $default = array(

                                    'id' => 0,

                                    'name' => '',

                                    'parent_id' => null,

                                    'description'=>'',

                                    'block_type' => '',

                                    'image_url' => '',

                                    'link_id' => null,

                                    'link_type' => '',

                                    'tag' => '',

                                    'sort_order' => '',

                                    'status' => '',

                                    'end_time' => '',

                                    'margin_top' => 0,

                                    'margin_top_dimension'=> '',

                                    'margin_right' => 0,

                                    'margin_right_dimension'=>'',

                                    'margin_bottom' => 0,

                                    'margin_bottom_dimension'=>'',

                                    'margin_left' => 0, 

                                    'margin_left_dimension'=>'',

                                    'padding_top' => 0,

                                    'padding_top_dimension'=>'',

                                    'padding_right' => 0,

                                    'padding_right_dimension'=>'',

                                    'padding_bottom' => 0,

                                    'padding_bottom_dimension'=>'',

                                    'padding_left' => 0,

                                    'padding_left_dimension'=>'',

                                    'border_radius' => 0,

                                    'border_radius_dimension' =>'',

                                    'layout'=>'',

                                    'layout_grid_col'=>'',

                                    'card_style'=>'',

                                    'shape'=>'',

                                    'header_align'=>'',

                                    'text_color'=>'#000000',

                                    'bg_color' =>'#ffffff'

                    );

            // here we are verifying does this request is post back and have correct nonce

        if ( isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {

            // combine our default item with request params

            $item = shortcode_atts($default, $_REQUEST);

            // validate data, and if all ok save item to database

            // if id is zero insert otherwise update

            $item_valid = $this->mstoreapp_app_validate_block($item);

            if ($item_valid === true) {
                if ($item['id'] == 0) {

                    $result = $wpdb->insert($table_name, $item);

                    $item['id'] = $wpdb->insert_id;

                    if ($result) 
                    {

                        $message = __('Item was successfully saved', 'mstoreapp_app');
                    } 

                    else   
                    {
                        $notice = __('There was an error while saving item', 'mstoreapp_app');
                    }
                } 

                else {

                    $result = $wpdb->update($table_name, $item, array('id' => $item['id']));

                    if ($result) 
                    {
                        $message = __('Item was successfully updated', 'mstoreapp_app');
                    } 

                    else 
                    {
                       $message = __('Item was successfully updated', 'mstoreapp_app');
                    }
                }
            } 
            else {

                // if $item_valid not true it contains error message(s)

                $notice = $item_valid;
            }
        }
        else {

            // if this is not post back we load item to edit or give new one to create

            $item = $default;

            if (isset($_REQUEST['id'])) {

                $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);

                if (!$item) {

                    $item = $default;
                    $notice = __('Item not found', 'mstoreapp_app');
                }
            }
        }


        // here we adding our meta box

        add_meta_box('mstoreapp_app_block_form_meta_box', 'Mstore Block data', array(&$this, 'mstoreapp_app_block_form_meta_box_handler'), 'block', 'normal', 'default');

        ?>
        
     <div class="wrap">   

        <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>

        <h2>

        <?php

         _e('Mstore Block', 'mstoreapp_app')?> 
         
             <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=mstoreapp-mobile-app');?>">

                <?php _e('Back to list', 'mstoreapp_app')?>    

             </a>

        </h2>

        <?php if (!empty($notice)): ?>

        <div id="notice" class="error"><p><?php echo $notice ?></p></div>

            <?php endif;?>

            <?php if (!empty($message)): ?>

        <div id="message" class="updated"><p><?php echo $message ?></p></div>

            <?php endif;?>

        <form id="form" method="POST" >

            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>

                <?php /* NOTICE: here we storing id to determine will be item added or updated */ ?>

            <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>

            <div class="metabox-holder" id="poststuff">

                <div id="post-body">

                    <div id="post-body-content">

                        <?php /* And here we call our custom meta box */ ?>

                        <?php do_meta_boxes('block', 'normal', $item); ?>

                        <input type="submit" value="<?php _e('Save', 'mstoreapp_app')?>" id="submit" class="button-primary" name="submit">

                    </div>

                </div>

            </div>

        </form>

    </div>

    <?php
    }

    /**
     * This function renders our custom meta box
     * $item is row
     *
     * @param $item
     */
//------------------------------------------------------------------Meta_Box---------------------------------------------------------------------
   
    public function mstoreapp_app_block_form_meta_box_handler($item)
    {

        global $wpdb;

        $table_name = $wpdb->prefix . 'mstoreapp_blocks';

        $parents = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

        //$block_id = $wpdb->get_results("SELECT id FROM $table_name", ARRAY_A);

    ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
                    
            $(document).ready(function(){

 
                $("#image_url_tr").hide();
                $("#link_type_tr").hide();
                $("#link_id_tr").hide();
                $("#tag_tr").hide();
                $("#sort_order_tr").hide();
                $("#border_radius_tr").hide();
                $("#layout_tr").hide();
                $("#border_radius_tr").show();
                $("#margin_tr").hide();
                $("#padding_tr").hide();
                $("#bg_color_tr").hide();
                $("#card_style_tr").hide();
                $("#shape_tr").hide();
                $("#text_color_tr").hide()
                $("#end_time_tr").hide();
                $("#layout_grid_col").hide();
                $("#lbl_layout_grid_col").hide();
                $("#header_align_tr").hide();


        
                if ( $("#select_block").val() == "home"){ 

                        $("#image_url_tr").hide();
                        $("#link_type_tr").hide();
                        $("#link_id_tr").hide();
                        $("#tag_tr").hide();
                        $("#sort_order_tr").hide();
                        $("#border_radius_tr").hide();
                        $("#layout_tr").hide();
                        $("#border_radius_tr").show();
                        $("#margin_tr").show();
                        $("#padding_tr").show();
                        $("#bg_color_tr").show();
                        $("#card_style_tr").hide();
                        $("#end_time_tr").hide();
                        $("#header_align_tr").hide();
                    }

                      else if($("#select_block").val() == "menu") {

                        $("#image_url_tr").hide();
                        $("#link_type_tr").hide();
                        $("#link_id_tr").hide();
                        $("#tag_tr").hide();
                        $("#sort_order_tr").hide();
                        $("#border_radius_tr").hide();
                        $("#layout_tr").hide();
                        $("#border_radius_tr").show();
                        $("#margin_tr").hide();
                        $("#padding_tr").hide();
                        $("#bg_color_tr").hide();
                        $("#card_style_tr").hide();
                        $("#end_time_tr").hide();
                        $("#header_align_tr").hide();
                    }


                    else if($("#select_block").val() == "block") {

                        $("#image_url_tr").hide();
                        $("#link_type_tr").hide();
                        $("#link_id_tr").show();
                        $("#tag_tr").hide();
                        $("#sort_order_tr").hide();
                        $("#border_radius_tr").hide();
                        $("#layout_tr").hide();
                        $("#border_radius_tr").hide();
                        $("#margin_tr").hide();
                        $("#padding_tr").hide();
                        $("#bg_color_tr").hide();
                        $("#card_style_tr").hide();
                        $("#end_time_tr").hide();
                        $("#header_align_tr").hide();
                    }

                    else if($("#select_block").val() == "banner") {
                                
                        $("#image_url_tr").show();
                        $("#link_type_tr").show();
                        $("#link_id_tr").show();
                        $("#tag_tr").hide();
                        $("#sort_order_tr").show();
                        $("#border_radius_tr").show();
                        $("#layout_tr").hide();
                        $("#border_radius_tr").show();
                        $("#margin_tr").show();
                        $("#padding_tr").show();
                        $("#bg_color_tr").show();
                        $("#card_style_tr").show();
                        $("#end_time_tr").hide();
                        $("#shape_tr").show();
                        $("#text_color_tr").hide();
                        $('#link_type option[value="block"]').show();
                        $("#header_align_tr").hide();

                        if ($('#layout_grid').attr("checked") == "checked") {
                        $("#layout_grid_col").show();
                        $("#lbl_layout_grid_col").show();
                        }
                         $('#layout_scroll').click(function(){

                        $("#layout_grid_col").hide();
                        $("#lbl_layout_grid_col").hide();

                    });
                        $('#layout_grid').click(function(){

                        $("#layout_grid_col").show();
                        $("#lbl_layout_grid_col").show();

                    });                            

                    }

                     else if($("#select_block").val() == "banner_slider") {
                                
                        $("#image_url_tr").show();
                        $("#link_type_tr").show();
                        $("#link_id_tr").show();
                        $("#tag_tr").hide();
                        $("#sort_order_tr").show();
                        $("#border_radius_tr").show();
                        $("#layout_tr").hide();
                        $("#border_radius_tr").show();
                        $("#margin_tr").show();
                        $("#padding_tr").show();
                        $("#bg_color_tr").show();
                        $("#card_style_tr").hide();
                        $("#end_time_tr").hide();
                        $("#shape_tr").hide();
                        $("#text_color_tr").hide();
                        $('#link_type option[value="block"]').show();
                        $("#header_align_tr").hide();

                    }

                     else if($("#select_block").val() == "banner_block") {
                                
                        $("#image_url_tr").show();
                        $("#link_type_tr").show();
                        $("#link_id_tr").show();
                        $("#tag_tr").hide();
                        $("#sort_order_tr").show();
                        $("#border_radius_tr").show();
                        $("#layout_tr").show();
                        $("#border_radius_tr").show();
                        $("#margin_tr").show();
                        $("#padding_tr").show();
                        $("#bg_color_tr").show();
                        $("#card_style_tr").hide();
                        $("#end_time_tr").hide();
                        $("#shape_tr").hide();
                        $("#text_color_tr").show();
                        $('#link_type option[value="block"]').show();
                        $("#header_align_tr").show();

                         if ($('#layout_grid').attr("checked") == "checked") {
                        $("#layout_grid_col").show();
                        $("#lbl_layout_grid_col").show();
                        }
                         $('#layout_scroll').click(function(){

                        $("#layout_grid_col").hide();
                        $("#lbl_layout_grid_col").hide();

                    });
                        $('#layout_grid').click(function(){

                        $("#layout_grid_col").show();
                        $("#lbl_layout_grid_col").show();

                    });                            


                    }


                   else if($("#select_block").val() == "product" || $("#select_block").val() == "post") {
                            
                        $("#link_id_tr").show();
                        $("#tag_tr").hide();
                        $("#sort_order_tr").show();
                        $("#border_radius_tr").show();
                        $("#layout_tr").show();
                        $("#margin_tr").show();
                        $("#padding_tr").show();
                        $("#bg_color_tr").show();
                        $("#shape_tr").hide();
                        $("#text_color_tr").show();
                        $("#card_style_tr").show();
                        $("#end_time_tr").hide();
                        $("#lbl_layout_grid_col").show();
                        $("#layout_grid_col").hide();
                        $("#header_align_tr").show();

                    }

                    else if($("#select_block").val() == "product_block" || $("#select_block").val() == "post_block") {
                            
                    $("#link_id_tr").show();
                    $("#link_type_tr").hide();
                    $("#tag_tr").show();
                    $("#sort_order_tr").show();
                    $("#border_radius_tr").show();
                    $("#layout_tr").show();
                    $("#margin_tr").show();
                    $("#padding_tr").show();
                    $("#bg_color_tr").show();
                    $("#shape_tr").hide();
                    $("#text_color_tr").show();
                    $("#card_style_tr").show();
                    $("#end_time_tr").hide();
                    $("#header_align_tr").show();

                    }

                    else if($("#select_block").val() == "category") {
                    
                    $("#image_url_tr").show();        
                    $("#link_id_tr").show();
                    $("#tag_tr").show();
                    $("#sort_order_tr").show();
                    $("#border_radius_tr").hide();
                    $("#layout_tr").show();
                    $("#margin_tr").show();
                    $("#padding_tr").show();
                    $("#bg_color_tr").show();
                    $("#shape_tr").show();
                    $("#text_color_tr").show();
                    $("#card_style_tr").show();
                    $("#end_time_tr").hide();
                    $("#lbl_layout_grid_col").hide();
                    $("#layout_grid_col").hide();
                    $("#header_align_tr").show();
                    }


                    else if($("#select_block").val() == "category_block") {
                    
                    $("#image_url_tr").hide();        
                    $("#link_id_tr").show();
                    $("#link_type_tr").hide();
                    $("#tag_tr").show();
                    $("#sort_order_tr").show();
                    $("#border_radius_tr").show();
                    $("#layout_tr").show();
                    $("#margin_tr").show();
                    $("#padding_tr").show();
                    $("#bg_color_tr").show();
                    $("#shape_tr").hide();
                    $("#text_color_tr").show();
                    $("#card_style_tr").show();
                    $("#end_time_tr").hide();
                    $("#lbl_layout_grid_col").hide();
                    $("#layout_grid_col").hide();
                    $("#header_align_tr").show();

                    }

                    else if($("#select_block").val() == "flash_sale") {
                    
                  $("#image_url_tr").hide();        
                    $("#link_id_tr").hide();
                    $("#tag_tr").show();
                    $("#sort_order_tr").show();
                    $("#border_radius_tr").show();
                    $("#layout_tr").show();
                    $("#margin_tr").show();
                    $("#padding_tr").show();
                    $("#bg_color_tr").show();
                    $("#shape_tr").hide();
                    $("#text_color_tr").show();
                    $("#card_style_tr").show();
                    $("#end_time_tr").show();
                     $("#link_type_tr").hide();
                   // $('#link_type option[value="product"]').prop('selected',true);

                    }



                $("#select_block").change(function() {

                    if ( $(this).val() == "home"){ 

                        $("#image_url_tr").hide();
                        $("#link_type_tr").hide();
                        $("#link_id_tr").hide();
                        $("#tag_tr").hide();
                        $("#sort_order_tr").hide();
                        $("#border_radius_tr").hide();
                        $("#layout_tr").hide();
                        $("#border_radius_tr").show();
                        $("#margin_tr").show();
                        $("#padding_tr").show();
                        $("#bg_color_tr").show();
                        $("#card_style_tr").hide();
                        $("#end_time_tr").hide();
                        $("#header_align_tr").hide();

                    }

                    else if($(this).val() == "menu") {

                        $("#image_url_tr").hide();
                        $("#link_type_tr").hide();
                        $("#link_id_tr").hide();
                        $("#tag_tr").hide();
                        $("#sort_order_tr").hide();
                        $("#border_radius_tr").hide();
                        $("#layout_tr").hide();
                        $("#border_radius_tr").show();
                        $("#margin_tr").hide();
                        $("#padding_tr").hide();
                        $("#bg_color_tr").hide();
                        $("#card_style_tr").hide();
                        $("#end_time_tr").hide();
                        $("#header_align_tr").hide();
                    }

                    else if($(this).val() == "block") {

                        $("#image_url_tr").hide();
                        $("#link_type_tr").hide();
                        $("#link_id_tr").show();
                        $("#tag_tr").hide();
                        $("#sort_order_tr").hide();
                        $("#border_radius_tr").hide();
                        $("#layout_tr").hide();
                        $("#border_radius_tr").hide();
                        $("#margin_tr").hide();
                        $("#padding_tr").hide();
                        $("#bg_color_tr").hide();
                        $("#card_style_tr").hide();
                        $("#end_time_tr").hide();
                        $("#header_align_tr").hide();
                    }

                    else if($(this).val() == "banner") {
                                
                        $("#image_url_tr").show();
                        $("#link_type_tr").show();
                        $("#link_id_tr").show();
                        $("#tag_tr").hide();
                        $("#sort_order_tr").show();
                        $("#border_radius_tr").show();
                        $("#layout_tr").hide();
                        $("#border_radius_tr").show();
                        $("#margin_tr").show();
                        $("#padding_tr").show();
                        $("#bg_color_tr").show();
                        $("#shape_tr").show();
                        $("#text_color_tr").hide();
                        $("#card_style_tr").hide();
                        $("#end_time_tr").hide();
                        $('#link_type option[value="Select Link Type"]').show();
                        $('#link_type option[value="block"]').show();
                        $("#header_align_tr").hide();
                        $('#attachment_preview').attr('src','http://localhost/wp/wp-content/uploads/2018/04/logo-1.jpg');

                        $('#layout_grid').click(function(){
                        $("#layout_grid_col").show();
                        $("#lbl_layout_grid_col").show();

                    });

                        $('#layout_scroll').click(function(){

                        $("#layout_grid_col").hide();
                        $("#lbl_layout_grid_col").hide();

                    });

                    }

                    else if($(this).val() == "banner_slider") {
                                
                        $("#image_url_tr").show();
                        $("#link_type_tr").show();
                        $("#link_id_tr").show();
                        $("#tag_tr").hide();
                        $("#sort_order_tr").show();
                        $("#border_radius_tr").show();
                        $("#layout_tr").hide();
                        $("#border_radius_tr").show();
                        $("#margin_tr").show();
                        $("#padding_tr").show();
                        $("#bg_color_tr").show();
                        $("#shape_tr").hide();
                        $("#text_color_tr").hide();
                        $("#card_style_tr").hide();
                        $("#end_time_tr").hide();
                        $("#header_align_tr").hide();
                        $('#link_type option[value="block"]').show();
                        $('#link_type option[value="product"]').prop('selected',true);
                        $('#attachment_preview').attr('src','http://localhost/wp/wp-content/uploads/2018/04/logo-1.jpg');



                    }

                     else if($(this).val() == "banner_block") {
                                
                        $("#image_url_tr").show();
                        $("#link_type_tr").show();
                        $("#link_id_tr").show();
                        $("#tag_tr").hide();
                        $("#sort_order_tr").show();
                        $("#border_radius_tr").show();
                        $("#layout_tr").show();
                        $("#border_radius_tr").show();
                        $("#margin_tr").show();
                        $("#padding_tr").show();
                        $("#bg_color_tr").show();
                        $("#shape_tr").hide();
                        $("#text_color_tr").show();
                        $("#card_style_tr").show();
                        $("#end_time_tr").hide();
                        $("#header_align_tr").show();
                        $('#link_type option[value="block"]').show();
                        $('#link_type option[value="product"]').prop('selected',true);
                        $('#attachment_preview').attr('src','http://localhost/wp/wp-content/uploads/2018/04/logo-1.jpg');


                        $('#layout_grid').click(function(){
                        $("#layout_grid_col").show();
                        $("#lbl_layout_grid_col").show();

                    });

                        $('#layout_scroll').click(function(){

                        $("#layout_grid_col").hide();
                        $("#lbl_layout_grid_col").hide();

                    });



                    }

                    else if($(this).val() == "product" || $(this).val() == "post") {
                            
                        $("#link_id_tr").show();
                        $("#tag_tr").show();
                        $("#sort_order_tr").show();
                        $("#border_radius_tr").show();
                        $("#layout_tr").show();
                        $("#margin_tr").show();
                        $("#padding_tr").show();
                        $("#bg_color_tr").show();
                        $("#shape_tr").hide();
                        $("#text_color_tr").show();
                        $("#card_style_tr").show();
                        $("#end_time_tr").hide();
                        $("#lbl_layout_grid_col").hide();
                        $("#layout_grid_col").hide();
                        $("#header_align_tr").show();
                        $('#link_type option[value="product"]').prop('selected',true);


                        $('#layout_grid').click(function(){

                        $("#layout_grid_col").hide();
                        $("#lbl_layout_grid_col").hide();

                    });

                        $('#layout_scroll').click(function(){

                        $("#layout_grid_col").hide();
                        $("#lbl_layout_grid_col").hide();

                    });

                    }

                   else if($(this).val() == "product_block" || $(this).val() == "post_block") {
                            
                    $("#link_id_tr").show();
                    $("#link_type_tr").hide();
                    $("#tag_tr").show();
                    $("#sort_order_tr").show();
                    $("#border_radius_tr").show();
                    $("#layout_tr").show();
                    $("#margin_tr").show();
                    $("#padding_tr").show();
                    $("#bg_color_tr").show();
                    $("#shape_tr").hide();
                    $("#text_color_tr").show();
                    $("#card_style_tr").show();
                    $("#end_time_tr").hide();
                    $("#header_align_tr").show();
                    $('#link_type option[value="product"]').prop('selected',true);
                    }

                    else if($(this).val() == "category") {
                    
                    $("#image_url_tr").show();        
                    $("#link_id_tr").show();
                    $("#tag_tr").show();
                    $("#sort_order_tr").show();
                    $("#border_radius_tr").hide();
                    $("#layout_tr").show();
                    $("#margin_tr").show();
                    $("#padding_tr").show();
                    $("#bg_color_tr").show();
                    $("#shape_tr").show();
                    $("#text_color_tr").show();
                    $("#card_style_tr").show();
                    $("#end_time_tr").hide();
                    $("#lbl_layout_grid_col").hide();
                    $("#layout_grid_col").hide();
                    $("#header_align_tr").show();
                    $('#link_type option[value="product"]').prop('selected',true);
                    $('#attachment_preview').attr('src','http://localhost/wp/wp-content/uploads/2018/04/logo-1.jpg');




                    }


                    else if($(this).val() == "category_block") {
                    
                    $("#image_url_tr").hide();
                    $("#link_type_tr").hide();        
                    $("#link_id_tr").show();
                    $("#tag_tr").show();
                    $("#sort_order_tr").show();
                    $("#border_radius_tr").show();
                    $("#layout_tr").show();
                    $("#margin_tr").show();
                    $("#padding_tr").show();
                    $("#bg_color_tr").show();
                    $("#shape_tr").hide();
                    $("#text_color_tr").show();
                    $("#header_align_tr").show();
                    $("#card_style_tr").show();
                    $("#end_time_tr").hide();
                    $("#lbl_layout_grid_col").hide();
                    $("#layout_grid_col").hide();
                    $('#link_type option[value="product"]').prop('selected',true);


                    $('#layout_grid').click(function(){

                    $("#layout_grid_col").hide();
                    $("#lbl_layout_grid_col").hide();

                    });

                    $('#layout_scroll').click(function(){

                    $("#layout_grid_col").hide();
                    $("#lbl_layout_grid_col").hide();

                    });

                    }

                    else if($(this).val() == "flash_sale") {
                    
                    $("#image_url_tr").hide();        
                    $("#link_id_tr").hide();
                    $("#tag_tr").show();
                    $("#sort_order_tr").show();
                    $("#border_radius_tr").show();
                    $("#layout_tr").show();
                    $("#margin_tr").show();
                    $("#padding_tr").show();
                    $("#bg_color_tr").show();
                    $("#shape_tr").hide();
                    $("#text_color_tr").show();
                    $("#card_style_tr").show();
                    $("#end_time_tr").show();
                    //$('#link_type option[value="product"]').prop('selected',true);
                     $("#link_type_tr").hide();
                    }


                   
                       
                }); 

            });


            </script>

           
    <table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
         
    <tbody>

        <tr class="form-field" id="name_tr">

            <th valign="top" scope="row">

                <label for="name" id="lbl_name">

                    <?php _e('Name', 'mstoreapp_app')?>
                    
                </label>

            </th>

            <td>

                <input id="name" name="name" autocomplete="off" type="text" autocomplete="off" style="width: 95%" 

                        value="<?php echo esc_attr($item['name'])?>"

                            size="50" class="code" placeholder="<?php _e('Block name', 'mstoreapp_app')?>" required>

                </input>

            </td>

        </tr>


        <tr class="form-field" id="parent_id_tr">

            <th valign="top" scope="row">

                <label for="parent_id" id="lbl_parent_id">
                
                    <?php _e('Parent', 'mstoreapp_app')?>
                    
                </label>

            </th>

            <td>

                <select name="parent_id" type="text" style="width: 95%"  id="parent_id" class="code">

                    <option value="0">Root</option>    

                        <?php foreach ($parents as $key => $value)  {  ?>

                        <option value="<?php echo $value['id'] ?>" <?php if ( $value['id'] == $item['parent_id'] ) echo 'selected="selected"'?>> 

                            <?php echo $value['name'] ?>
                                
                        </option>   

                        <?php } ?> 

                </select> 

            </td>

        </tr>

        <tr class="form-field" id="desc_tr">

            <th valign="top" scope="row">

                <label for="desc" id="lbl_desc">

                <?php _e('Description', 'mstoreapp_app')?>
                    
                </label>

            </th>

            <td>
                <input type="text" id="desc" name="description"  autocomplete="off"

                     value="<?php echo esc_attr($item['description'])?>"

                        size="50" class="code" placeholder="<?php _e('Description', 'mstoreapp_app')?>">

                </input>

            </td>

        </tr>

        <tr class="form-field" id="status_tr" style="width: 95%">

            <th valign="top" scope="row">

                <label for="status" id="lbl_status">

                        <?php _e('Status', 'mstoreapp_app')?>
                            
                </label>

            </th>

            <td>

               <select name="status" style="width: 95%;" class="code" id="status">

                    <option value="true" <?php if ( $item['status'] == 'true' ) echo 'selected="selected"'?>>

                                Enabled

                    </option>

                    <option value="false" <?php if ( $item['status'] == 'false') echo 'selected="selected"'?>>

                                Disabled

                    </option>

                </select>

            </td>

        </tr>

        <tr class="form-field" id="block_type_tr">

            <th valign="top" scope="row">

                <label for="block_type" id="lbl_block_type">

                    <?php _e('Block Type', 'mstoreapp_app')?>
                    
                </label>

            </th>

            <td>
               
                <select name="block_type" type="text" style="width: 95%" id="select_block" class="code">

                    <option value="" disabled selected hidden>

                                Select Block Type
                    </option>

                    <option value="home" <?php if ( $item['block_type'] == 'home' ) echo 'selected="selected"'?>>

                                Home
                    </option>

                    <option value="menu" <?php if ( $item['block_type'] == 'menu' ) echo 'selected="selected"'?>>

                                Menu
                    </option>

                    <option value="banner_slider" <?php if ( $item['block_type'] == 'banner_slider' ) echo 'selected="selected"'?>>

                                Banner Slider
                    </option>


                    <option value="banner_block" <?php if ( $item['block_type'] == 'banner_block' ) echo 'selected="selected"'?>>

                                Banner Block
                    </option>

                    <option value="category_block" <?php if ( $item['block_type'] == 'category_block' ) echo 'selected="selected"'?>>

                                Category Block
                    </option>

                    <option value="product_block" <?php if ( $item['block_type'] == 'product_block' ) echo 'selected="selected"'?>>

                                Product Block
                    </option>

                    <option value="post_block" <?php if ( $item['block_type'] == 'post_block' ) echo 'selected="selected"'?>>

                                Post Block
                    </option>

                    <option value="banner" <?php if ( $item['block_type'] == 'banner' ) echo 'selected="selected"'?>>

                                Banner
                    </option>

                    <option value="flash_sale" <?php if ( $item['block_type'] == 'flash_sale' ) echo 'selected="selected"'?>>

                               Flash Sale
                    </option>


                    <!--option value="category" <!?php if ( $item['block_type'] == 'category' ) echo 'selected="selected"'?>>

                                Category
                    </option->

                    <option value="product" <!?php if ( $item['block_type'] == 'product' ) echo 'selected="selected"'?>>
                                    
                                Product
                    </option>


                    <option value="post" <!?php if ( $item['block_type'] == 'post' ) echo 'selected="selected"'?>>

                                Post
                    </option-->

                 
                     <!--option value="block" <!?php if ( $item['block_type'] == 'block' ) echo 'selected="selected"'?>>

                              Block Page
                    </option-->

                </select>   

            </td>

        </tr>

        <tr class="form-field" id="image_url_tr">

            <th valign="top" scope="row">

                <label for="image_url" id="lbl_image_url">
                
                    <?php _e('Image', 'mstoreapp_app')?>
                    
                </label>
            </th>
                           <style>
                           #attachment_preview{
                                        max-height: 141px;
                                        max-width: 340px;
                                        padding: 5px;
                                        margin-bottom: 0;
                                        margin-top: 10px;
                                        margin-right: 15px;
                                        border: 2px solid #c4c4c4;
                                        background: #eee;
                                        -moz-border-radius: 3px;
                                        -khtml-border-radius: 3px;
                                        -webkit-border-radius: 3px;
                                        border-radius: 3px;
                                        }
                           </style>
            <td>
                <!--input type="file" name="image_url" id="image_url"  value="--><!--?php echo esc_attr($item['image_url'])?>""-->
                <input id="image_url" name="image_url" type="hidden" class="img_style" 

                    value="<?php echo esc_attr($item['image_url'])?>"

                       size="50" class="code" placeholder="<?php _e('Image', 'mstoreapp_app')?>">

                </input>
                            <img src="" id="attachment_preview"  style="height:130px;width:auto" /><br><br>

                            <input id="upload_image_button" class="button" type="button" value="Upload Image" style="background: #006799;width:150px;height:30px;color:#fff;" /><br>

                            <input type="hidden" name="attachment_id" id="attachment_id" value=""><br>

                            <input type="hidden" name="attachment_url" class="code" id="attachment_url" value="">

                    <script>

                        $(document).ready(function(){
                        $("#attachment_preview").attr('src',$("#image_url").val());
                        $('#upload_image_button').click(function(e) {
                        e.preventDefault();
                        $("#upld_img").attr('src',$("#image_url").val());
                        var custom_uploader = wp.media({
                            title: 'Custom Title',
                            button: {
                            text: 'Select Image to Upload'
                            },
                            multiple: false  // Set this to true to allow multiple files to be selected
                            })
                        .on('select', function() {
                        var attachment = custom_uploader.state().get('selection').first().toJSON().url;

                        $("#attachment_id").val(attachment.id);
                            //$("#attachment_url").val(attachment.url);
                        $("#attachment_preview").attr('src', attachment);
                            $("#image_url").val(attachment);
                            })
                            .open();
        
                            });
                            });
                            </script>

            </td>

        </tr>


         <tr class="form-field"  id="link_type_tr">

            <th valign="top" scope="row">

                <label for="link_type" id="lbl_link_type">
                
                    <?php _e('Link Type', 'mstoreapp_app')?>
                    
                </label>

            </th>

            <td>
                <select name="link_type" style="width: 95%" id="link_type">

                     <option value="" disabled selected hidden>

                                Select Link Type
                    </option>

                    <option value="product" <?php if ( $item['link_type'] == 'product' ) echo 'selected="selected"'?>>

                                    Product 
                    </option>   

                    <option value="category" <?php if ( $item['link_type'] == 'category' ) echo 'selected="selected"'?>>

                                    Category 
                    </option>

                    <option value="post" <?php if ( $item['link_type'] == 'post' ) echo 'selected="selected"'?>>

                                    Post 
                    </option>

                    <option value="block" <?php if ( $item['link_type'] == 'block' ) echo 'selected="selected"'?>>

                                    Block Page
                    </option>

                </select>  

            </td>

            <td>

        </tr>

        <tr class="form-field" id="link_id_tr">

            <th valign="top" scope="row">

                <label for="link_id" id="lbl_link_id">

                    <?php _e('Link Id', 'mstoreapp_app')?>
                        
                </label>

            </th>

            <td>

                <input id="link_id" name="link_id" type="text" autocomplete="off" style="width: 95%" 

                    value="<?php echo esc_attr($item['link_id'])?>"

                       size="50" class="code" placeholder="<?php _e('Link Id', 'mstoreapp_app')?>">

                </input>

            </td>

        </tr>


        <!--tr class="form-field" id="block_id_tr">

            <th valign="top" scope="row">

                <label for="block_id" id="lbl_link_id">

                    <!?php _e('Block id', 'mstoreapp_app')?>
                        
                </label>

            </th>

            <td>

                <input id="block_id" name="block_id" type="text" style="width: 95%" 

                    value="<!?php echo esc_attr($item['block_id'])?>"

                       size="50" class="code" placeholder="<!?php _e('Block id', 'mstoreapp_app')?>">

                </input>



            </td>

        </tr-->
       

        <tr class="form-field" id="tag_tr">

            <th valign="top" scope="row">

                <label for="tag" id="lbl_tag">

                     <?php _e('Tag Id', 'mstoreapp_app')?>
                        
                </label>

            </th>

            <td>
                <input id="tag" name="tag" type="text" autocomplete="off" style="width: 95%"

                    value="<?php echo esc_attr($item['tag'])?>"

                       size="50" class="code" placeholder="<?php _e('Tag Id', 'mstoreapp_app')?>">

                </input>

            </td>

        </tr>
     

        <tr class="form-field" id="sort_order_tr">

            <th valign="top" scope="row">

                <label for="sort_order" id="lbl_sort_order">

                    <?php _e('Sort Order', 'mstoreapp_app')?>
                    
                </label>

            </th>

            <td>

                <input id="sort_order" name="sort_order" type="number" style="width: 95%" 

                    value="<?php echo esc_attr($item['sort_order'])?>"

                       size="50" class="code" placeholder="<?php _e('Sort Order', 'mstoreapp_app')?>">

                </input>

            </td>

        </tr>


        <tr class="form-field" id="end_time_tr">

            <th valign="top" scope="row">

                <label for="end_time" id="lbl_end_time">

                    <?php _e('End Time', 'mstoreapp_app')?>
                    
                </label>

            </th>

            <td>
                       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />

                        <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
                        <script src="http://cdn.craig.is/js/rainbow-custom.min.js"></script>
                        <script type="text/javascript">
                                jQuery(document).ready(function() {
                                jQuery('#end_time').datetimepicker({
                                    format:'M d, Y H:i', 
                                        //defaultDate: +2,   
                                        //minDate: 0, 
                                        //maxDate: '+2y',
                                        //numberOfMonths: 1,
                                        //showAnim: 'fadeIn',
                                        //showButtonPanel: true,
                                        //buttonImageOnly: true,
                                        //buttonText: 'Pick a date',
                                        //showOn: 'both'
                                });
                                });
                        </script>

                <input id="end_time" name="end_time" type="text" autocomplete="off" style="width: auto" 

                    value="<?php echo esc_attr($item['end_time'])?>"

                       size="50" class="code" placeholder="<?php _e('Jun 30,2018 15:37', 'mstoreapp_app')?>">

                </input>

                <div style="font-size: 12px;margin-left: 8px;padding-top: 12px;color: #80808096;">Add flash sale end time eg: Jun 30,2018 15:37:25</div>

            </td>

        </tr>


        <tr class="form-field" id="margin_tr">

            <th valign="top" scope="row">

                <label for="margin" id="lbl_margin">

                    <?php _e('Margin', 'mstoreapp_app')?>
                    
                </label>

           </th>

            <td>
            <style>
                    @media screen and (max-width: 781px){
                    #margin_top .form-table textarea, .form-table span.description, .form-table td input[type=text], .form-table td input[type=password], .form-table td input[type=email], .form-table td select, .form-table td textarea {
                    width: 100%;
                    font-size: 16px;
                    line-height: 1.5;
                    padding: 7px 10px;
                    display: inline-block;
                    max-width: none;
                    box-sizing: border-box;
                    }}

            </style>

           
                <input id="margin_top" autocomplete="off" name="margin_top" type="text" style="width: 125px; min-width: 100px;" 

                    value="<?php echo esc_attr($item['margin_top'])?>"

                       size="50" class="code" placeholder="<?php _e('Margin Top', 'mstoreapp_app')?>">

                </input>  

                        <select name="margin_top_dimension" id="margin_top_dimension" style="background-color: #eee;min-width: 60px;width: 60px;max-width: 60px;margin-left: -5px;margin-top: -4px;">

                            <option value="px" <?php if ( $item['margin_top_dimension'] == 'px' ) echo 'selected="selected"'?>>

                                            px 
                            </option>   

                            <option value="% " <?php if ( $item['margin_top_dimension'] == '% ' ) echo 'selected="selected"'?>>

                                            % 
                            </option>

                            <option value="em" <?php if ( $item['margin_top_dimension'] == 'em' ) echo 'selected="selected"'?>>

                                            em 
                            </option>

                        </select>  

                    
                <input id="margin_right" autocomplete="off" name="margin_right" type="text" style="width: 125px; min-width: 100px;" 

                    value="<?php echo esc_attr($item['margin_right'])?>"

                       size="50" class="code" placeholder="<?php _e('Margin Right', 'mstoreapp_app')?>">

                </input>

                          <select name="margin_right_dimension" id="margin_right_dimension" style="background-color: #eee;min-width: 60px;width: 60px;max-width: 60px;margin-left: -5px;margin-top: -4px;">

                            <option value="px" <?php if ( $item['margin_right_dimension'] == 'px' ) echo 'selected="selected"'?>>

                                            px 
                            </option>   

                            <option value="% " <?php if ( $item['margin_right_dimension'] == '% ' ) echo 'selected="selected"'?>>

                                            % 
                            </option>

                            <option value="em" <?php if ( $item['margin_right_dimension'] == 'em' ) echo 'selected="selected"'?>>

                                            em 
                            </option>

                        </select>  

                <input id="margin_bottom" name="margin_bottom" type="text" autocomplete="off"  style="width: 125px; min-width: 100px;" 

                    value="<?php echo esc_attr($item['margin_bottom'])?>"

                       size="50" class="code" placeholder="<?php _e('Margin Bottom', 'mstoreapp_app')?>">

                </input>

                     <select name="margin_bottom_dimension" id="margin_bottom_dimension" style="background-color: #eee;min-width: 60px;width: 60px;max-width: 60px;margin-left: -5px;margin-top: -4px;">

                            <option value="px" <?php if ( $item['margin_bottom_dimension'] == 'px' ) echo 'selected="selected"'?>>

                                            px 
                            </option>   

                            <option value="% " <?php if ( $item['margin_bottom_dimension'] == '% ' ) echo 'selected="selected"'?>>

                                            % 
                            </option>

                            <option value="em" <?php if ( $item['margin_bottom_dimension'] == 'em' ) echo 'selected="selected"'?>>

                                            em 
                            </option>

                        </select>  

                <input id="margin_left" name="margin_left" autocomplete="off" type="text" style="width: 125px; min-width: 100px;" 

                    value="<?php echo esc_attr($item['margin_left'])?>"

                       size="50" class="code" placeholder="<?php _e('Margin Left', 'mstoreapp_app')?>">

                </input>

                        <select name="margin_left_dimension" id="margin_left_dimension" style="background-color: #eee;min-width: 60px;width: 60px;max-width: 60px;margin-left: -5px;margin-top: -4px;">

                            <option value="px" <?php if ( $item['margin_left_dimension'] == 'px' ) echo 'selected="selected"'?>>

                                            px 
                            </option>   

                            <option value="% " <?php if ( $item['margin_left_dimension'] == '% ' ) echo 'selected="selected"'?>>

                                            % 
                            </option>

                            <option value="em" <?php if ( $item['margin_left_dimension'] == 'em' ) echo 'selected="selected"'?>>

                                            em 
                            </option>

                        </select> 

            </td>

        </tr>


        <tr class="form-field" id="padding_tr" >

            <th valign="top" scope="row">

                <label for="padding" id="lbl_padding">

                    <?php _e('Padding', 'mstoreapp_app')?>
                
                </label>

            </th>

            <td>

                <input id="padding_top" name="padding_top" type="text" autocomplete="off" style="width: 125px;"

                    value="<?php echo esc_attr($item['padding_top'])?>"

                       size="50" class="code" placeholder="<?php _e('Padding Top', 'mstoreapp_app')?>">

                </input>

                    <select name="padding_top_dimension" id="padding_top_dimension"  style="background-color: #eee;min-width: 60px;width: 60px;max-width: 60px;margin-left: -5px;margin-top: -4px;">

                            <option value="px" <?php if ( $item['padding_top_dimension'] == 'px' ) echo 'selected="selected"'?>>

                                            px 
                            </option>   

                            <option value="% " <?php if ( $item['padding_top_dimension'] == '% ' ) echo 'selected="selected"'?>>

                                            % 
                            </option>

                            <option value="em" <?php if ( $item['padding_top_dimension'] == 'em' ) echo 'selected="selected"'?>>

                                            em 
                            </option>

                        </select> 

                <input id="padding_right" name="padding_right" autocomplete="off" type="text"  style="width: 125px; min-width: 100px;"  

                    value="<?php echo esc_attr($item['padding_right'])?>"

                       size="50" class="code" placeholder="<?php _e('Padding Right', 'mstoreapp_app')?>">

                </input>

                    <select name="padding_right_dimension" id="padding_right_dimension"  style="background-color: #eee;min-width: 60px;width: 60px;max-width: 60px;margin-left: -5px;margin-top: -4px;">

                            <option value="px" <?php if ( $item['padding_right_dimension'] == 'px' ) echo 'selected="selected"'?>>

                                            px 
                            </option>   

                            <option value="% " <?php if ( $item['padding_right_dimension'] == '% ' ) echo 'selected="selected"'?>>

                                            % 
                            </option>

                            <option value="em" <?php if ( $item['padding_right_dimension'] == 'em' ) echo 'selected="selected"'?>>

                                            em 
                            </option>

                        </select> 

                 <input id="padding_bottom" autocomplete="off" name="padding_bottom" type="text"  style="width: 125px; min-width: 100px;" 

                    value="<?php echo esc_attr($item['padding_bottom'])?>"

                       size="50" class="code" placeholder="<?php _e('Padding Bottom', 'mstoreapp_app')?>">

                </input>

                           <select name="padding_bottom_dimension" id="padding_right_dimension" style="background-color: #eee;min-width: 60px;width: 60px;max-width: 60px;margin-left: -5px;margin-top: -4px;">

                            <option value="px" <?php if ( $item['padding_bottom_dimension'] == 'px' ) echo 'selected="selected"'?>>

                                            px 
                            </option>   

                            <option value="% " <?php if ( $item['padding_bottom_dimension'] == '% ' ) echo 'selected="selected"'?>>

                                            % 
                            </option>

                            <option value="em" <?php if ( $item['padding_bottom_dimension'] == 'em' ) echo 'selected="selected"'?>>

                                            em 
                            </option>

                        </select> 

                <input id="padding_left" name="padding_left" type="text"  autocomplete="off" style="width: 125px; min-width: 100px;"  

                    value="<?php echo esc_attr($item['padding_left'])?>"

                       size="50" class="code" placeholder="<?php _e('Padding Left', 'mstoreapp_app')?>">

                </input>


                    <select name="padding_left_dimension" id="padding_left_dimension"  style="background-color: #eee;min-width: 60px;width: 60px;max-width: 60px;margin-left: -5px;margin-top: -4px;">

                            <option value="px" <?php if ( $item['padding_left_dimension'] == 'px' ) echo 'selected="selected"'?>>

                                            px 
                            </option>   

                            <option value="% " <?php if ( $item['padding_left_dimension'] == '% ' ) echo 'selected="selected"'?>>

                                            % 
                            </option>

                            <option value="em" <?php if ( $item['padding_left_dimension'] == 'em' ) echo 'selected="selected"'?>>

                                            em 
                            </option>

                        </select> 

            </td>

        </tr>

        <tr class="form-field" id="border_radius_tr">

            <th valign="top" scope="row">

                <label for="border_radius" id="lbl_border_radius">

                    <?php _e('Border Radius', 'mstoreapp_app')?>
                        
                </label>

            </th>

            <td>
                <input id="border_radius" autocomplete="off" name="border_radius" type="text" style="width: 30%;" 

                    value="<?php echo esc_attr($item['border_radius'])?>"  

                        size="50" class="code" placeholder="<?php _e('Border Radius', 'mstoreapp_app')?>">

                </input>    

                <select name="border_radius_dimension" id="border_radius_dimension"  style="background-color: #eee;min-width: 60px;width: 60px;max-width: 60px;margin-left: -5px;margin-top: -4px;">

                            <option value="px" <?php if ( $item['border_radius_dimension'] == 'px' ) echo 'selected="selected"'?>>

                                            px 
                            </option>   

                            <option value="% " <?php if ( $item['border_radius_dimension'] == '% ' ) echo 'selected="selected"'?>>

                                            % 
                            </option>

                            <option value="em" <?php if ( $item['border_radius_dimension'] == 'em' ) echo 'selected="selected"'?>>

                                            em 
                            </option>

                        </select> 
           

            </td>

        </tr>

        <tr class="form-field" id="layout_tr">

            <th valign="top" scope="row">

                <label for="layout" id="lbl_layout">

                    <?php _e('Layout', 'mstoreapp_app')?>
                        
                </label><br><br><br><br>

                 <label for="layout_grid_col" id="lbl_layout_grid_col">

                    <?php _e('Grid Column', 'mstoreapp_app')?>
                        
                </label>

            </th>


            <td>

                <input id="layout_scroll" name="layout" type="radio"  

                    value="scroll"  <?php if ( $item['layout'] == 'scroll' ) echo 'checked ="checked "'?>checked>

                        Scroll 
                                <!--table  style="margin-left:8em;background:#f2f2f2" id="card_style_tr">

                                    <tr id="shape_tr">
                                        <td>

                                            <input id="shape_circular" name="shape" type="radio"  
                                            value="circular"  <!?php if ( $item['shape'] == 'circular' ) echo 'checked ="checked "'?>checked>

                                            Circular 

                                            <br>
                                            <br>

                                            </input>

                                            <input id="shape_box" name="shape" type="radio"  

                                            value="box" <!?php if ( $item['shape'] == 'box' ) echo 'checked ="checked "'?>> 

                                            Box

                                            </input>
                                            
                                        </td>
                                    </tr>
                                </table--> 

                                            <br>
                                            <br>


                <input id="layout_grid" name="layout" type="radio"  

                    value="grid" <?php if ( $item['layout'] == 'grid' ) echo 'checked ="checked "'?>> 

                        Grid

                </input><br><br>

                 <select name="layout_grid_col" style="width: 95%" id="layout_grid_col">

                    <option value="one" <?php if ( $item['layout_grid_col'] == 'one' ) echo 'selected="selected"'?> >

                                  1
                    </option>   

                    <option value="two" <?php if ( $item['layout_grid_col'] == 'two' ) echo 'selected="selected"'?>selected>

                                  2
                    </option>

                    <option value="three" <?php if ( $item['layout_grid_col'] == 'three' ) echo 'selected="selected"'?>>

                                  3
                    </option>   

                    <option value="four" <?php if ( $item['layout_grid_col'] == 'four' ) echo 'selected="selected"'?>>

                                  4
                    </option>


                </select>  

            </td>



        </tr>

       <tr class="form-field"  id="card_style_tr">

            <th valign="top" scope="row">

                <label for="card_style" id="lbl_card_style">
                
                    <?php _e('Card Style', 'mstoreapp_app')?>
                    
                </label>

            </th>

            <td>
                <select name="card_style" style="width: 95%" id="card_style">

                    <option value="card_with_shadow" <?php if ( $item['card_style'] == 'card_with_shadow' ) echo 'selected="selected"'?>>

                                    Card With Shadow
                    </option>   

                    <option value="card_without_shadow" <?php if ( $item['card_style'] == 'card_without_shadow' ) echo 'selected="selected"'?>>

                                    Card Without Shadow 
                    </option>


                </select>  

            </td>

            <td>

        </tr>


        <tr class="form-field"  id="shape_tr">

            <th valign="top" scope="row">

                <label for="shape" id="lbl_shape">
                
                    <?php _e('Shape', 'mstoreapp_app')?>
                    
                </label>

            </th>

            <td>

                <select name="shape" style="width: 95%" id="shape">

                    <option value="square" <?php if ( $item['shape'] == 'square' ) echo 'selected="selected"'?>>

                                   Square
                    </option>   

                    <option value="circular" <?php if ( $item['shape'] == 'circular' ) echo 'selected="selected"'?>>

                                   Circular
                    </option>


                </select>  

            </td>

            <td>

        </tr>

        <tr class="form-field"  id="header_align_tr">

            <th valign="top" scope="row">

                <label for="header_align" id="lbl_header_align">
                
                    <?php _e('Header Text', 'mstoreapp_app')?>
                    
                </label>

            </th>

            <td>
                <select name="header_align" style="width: 95%" id="header_align">
                    <option value="none" <?php if ( $item['header_align'] == 'none' ) echo 'selected="selected"'?>>

                                    Hidden
                    </option>  

                    <option value="top_left" <?php if ( $item['header_align'] == 'top_left' ) echo 'selected="selected"'?>>

                                    Top Left 
                    </option>   

                    <option value="top_center" <?php if ( $item['header_align'] == 'top_center' ) echo 'selected="selected"'?>>

                                    Top Center 
                    </option>

                    <option value="top_right" <?php if ( $item['header_align'] == 'top_right' ) echo 'selected="selected"'?>>

                                     Top Right  
                    </option>

                    <option value="left_floating" <?php if ( $item['header_align'] == 'left_floating' ) echo 'selected="selected"'?>>

                                    Left Floating 
                    </option>

                </select>  

            </td>

            <td>

        </tr>



       <tr class="form-field" id="text_color_tr">

            <th valign="top" scope="row">

                <label for="text_color">

                    <?php _e('Text Color', 'mstoreapp_app')?>
                    
                </label>

            </th>

            <td>

                        <input type="text" class="text_color" autocomplete="off" id="text_color"  onchange="txt_color()" name="text_color" value="<?php echo esc_attr($item['text_color'])?>" />

             </td>
           
                        <script type='text/javascript'>
                            jQuery(document).ready(function($) {
                                $('.text_color').wpColorPicker();
                            });
                        </script>
            <script>

                function txt_color(){

                    var x = document.getElementById("text_color").value;
                    //document.getElementById("bg_change").innerHTML = '<b> selected color is: </b>' + x ;

                }    

            </script>

        </tr>

        <tr class="form-field" id="bg_color_tr">

            <th valign="top" scope="row">

                <label for="bg_color">

                    <?php _e('Background Color', 'mstoreapp_app')?>
                    
                </label>

            </th>

            <td>

                <!--input id="bg_color"  onchange="myFunction()" name="bg_color" type="color" style="background:#006799;width: 150px; height: 30px;" 

                    value="<!?php echo esc_attr($item['bg_color'])?>" size="50" class="code" data-default-color="#444"-->

                        <input type="text" autocomplete="off" class="bg_color" onchange="myFunction()" name="bg_color" value="<?php echo esc_attr($item['bg_color'])?>" />

             </td>
                        <script type='text/javascript'>
                            jQuery(document).ready(function($) {
                                $('.bg_color').wpColorPicker();
                            });
                        </script>
            <script>

                function myFunction(){

                    var x = document.getElementById("bg_color").value;
                    //document.getElementById("bg_change").innerHTML = '<b> selected color is: </b>' + x ;

                }    

            </script>

        </tr>

    </tbody>
    
    </table>

    <?php

    }

    /**
     * Simple function that validates data and retrieve bool on success
     * and error message(s) on error
     *
     * @param $item
     * @return bool|string
     */

    public function mstoreapp_app_validate_block($item)
    {

        $messages = array();

        if (empty($item['name'])) $messages[] = __('Name is required', 'mstoreapp_app');

        if (empty($messages)) return true;

        return implode('<br />', $messages);

    }

}
