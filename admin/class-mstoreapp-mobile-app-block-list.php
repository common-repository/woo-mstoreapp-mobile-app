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

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Custom_Table_app_List_Table extends WP_List_Table
{
    
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'block',
            'plural' => 'blocks',
        ));
    }

    /**
     * [REQUIRED] this is a default column renderer
     *
     * @param $item - row (key, value array)
     * @param $column_name - string (key)
     * @return HTML
     */
    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    /**
     * [OPTIONAL] this is app, how to render specific column
     *
     * method name must be like this: "column_[column_name]"
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_age($item)
    {
        return '<em>' . $item['link_id'] . '</em>';
    }

    /**
     * [OPTIONAL] this is app, how to render column with actions,
     * when you hover row "Edit | Delete" links showed
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_name($item)
    {
        // links going to /admin.php?page=[your_plugin_page][&other_params]
        // notice how we used $_REQUEST['page'], so action will be done on curren page
        // also notice how we use $this->_args['singular'] so in this app it will
        // be something like &block=2
        $actions = array(
            'edit' => sprintf('<a href="?page=mstoreapp_app_block_form&id=%s">%s</a>', $item['id'], __('Edit', 'mstoreapp_app')),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('Delete', 'mstoreapp_app')),
        );

        return sprintf('%s %s',
            $item['name'],
            $this->row_actions($actions)
        );
    }

    /**
     * [REQUIRED] this is how checkbox column renders
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

     function column_img($item)
    {
        return sprintf(
          '<img width="auto" height="50px" style="border: 2px solid #c4c4c4;" src="%s" />',
        $item['image_url']
        );
    }

    /**
     * [REQUIRED] This method return columns to display in table
     * you can skip columns that you do not want to show
     * like content, or description
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'name' => __('Name', 'mstoreapp_app'),
            'parent_name' =>__('Parent Name','mstoreapp_app'),
            'block_type'=> __('Block Type','mstoreapp_app'),
            'img' => __('Image','mstoreapp_app'),
            'description' => __('Description', 'mstoreapp_app'),
            'link_type' => __('Link Type', 'mstoreapp_app'),
            'link_id' => __('Link Id', 'mstoreapp_app'),
            'status' => __('Status', 'mstoreapp_app'),
            'sort_order' => __('Sort Order','mstoreapp_app')
        );
        return $columns;
    }

    /**
     * [OPTIONAL] This method return columns that may be used to sort table
     * all strings in array - is column names
     * notice that true on name column means that its default sort
     *
     * @return array
     */
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'name' => array('name', true),
            'parent_name' => array('parent_name',false),
            'block_type'=> array('block_type' , true ),
            'img' => array('<img/>'),
            'description' => array('description', false),
            'link_type' => array('link_type',false),
            'link_id' => array('link_id', false),
            'status' => array('status', false),
            'sort_order' => array('sort_order',false)
           
        );
        return $sortable_columns;
    }

    /**
     * [OPTIONAL] Return array of bult actions if has any
     *
     * @return array
     */
    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    /**
     * [OPTIONAL] This method processes bulk actions
     * it can be outside of class
     * it can not use wp_redirect coz there is output already
     * in this app we are processing delete action
     * message about successful deletion will be shown on page in next part
     */
    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mstoreapp_blocks'; // do not forget about tables prefix

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }
    /**
     * [REQUIRED] This is the most important method
     *
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'mstoreapp_blocks'; // do not forget about tables prefix

        $per_page = 20; // constant, how much records will be shown per page

        $columns = $this->get_columns();

        $hidden = array();

        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // will be used in pagination settings
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        // prepare query params, as usual current page, order by and order direction
       // $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 0) : 0;
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged'] -1) * 5) : 0;

        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'name';

        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
        $search = ( isset( $_REQUEST['s'] ) ) ? $_REQUEST['s'] : '';
        $this->items = $wpdb->

            get_results($wpdb->prepare(

                "SELECT 
                    t1.id,
                    t1.name,
                    t1.block_type,
                    t1.image_url,
                    t1.description,
                    t1.link_type,
                    t1.link_id,
                    t1.status,
                    t1.sort_order,
                    t2.name as parent_name
                FROM $table_name t1
                    LEFT JOIN $table_name t2 
                    ON t2.id=t1.parent_id
                    WHERE t1.name LIKE '%%%s%%'
                    ORDER BY $orderby $order 
                    LIMIT %d OFFSET %d", $search, $per_page, $paged), 
                    ARRAY_A);
              
        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(

            'total_items' => $total_items, // total items defined above

            'per_page' => $per_page, // per page constant defined at top of method

            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
}