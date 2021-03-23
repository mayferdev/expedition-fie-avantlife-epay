<?php
 
if(!class_exists('WP_List_Table')){
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if ( !defined('EXPEDITION_TOUR_INVITATIONS') ){
    define( "EXPEDITION_TOUR_INVITATIONS", "expedition_tour_invitations");
}

if ( !defined('EXPEDITION_TOUR_INVITATIONS_TITLE') ){
    define( "EXPEDITION_TOUR_INVITATIONS_TITLE", __('Invitations', 'expedition'));
}

global $expedition_reports_search_title;

/**
 * Display the information in @see BAM_DB
 * 
 */
class ExpeditionAdminTourInvitationsTable extends WP_List_Table {
    
    public function search_box($text, $input_id) {
        parent::search_box($text, $input_id);
    }
    
    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct() {
        parent::__construct(array(
            'singular' => EXPEDITION_TOUR_INVITATIONS.'_at', //Singular label
            'plural' => EXPEDITION_TOUR_INVITATIONS.'_at', //plural label, also this well be one of the table css class
            'ajax' => false //We won't support Ajax for this table
        ));
    }

    /**
     * Define the columns that are going to be used in the table
     * @return array $columns, the array of columns to use with the table
     */
    function get_columns() {
        return $columns = array(
            
            'col_id' => __('ID', 'expedition'),
            'col_created_at' => __('Created','expedition'),
            // 'col_updated_at' => __('Updated','expedition'),
            'col_user_id' => __('User','expedition'),
            'col_owner_id' => __('Tour Owner','expedition'),
            'col_tour_id' => __('Tour','expedition'),
            'col_status' => __('Status','expedition'),
            
        );
    }

    /**
     * Add extra markup in the toolbars before or after the list
     * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
     */
    function extra_tablenav($which) {
        if ($which == "top") {
            //The code that goes before the table is here
            //echo"Hello, I'm before the table";
        }
        if ($which == "bottom") {
            //The code that goes after the table is there
            //echo"Hi, I'm after the table";
        }
    }

    /**
     * Decide which columns to activate the sorting functionality on
     * @return array $sortable, the array of columns that can be sorted by the user
     */
    public function get_sortable_columns() {
        return $sortable = array(
            'col_id' => array('id'),
            'col_created_at' => array('created_at'),
            // 'col_updated_at' => array('updated_at'),
            'col_user_id' => array('user_id'),
            'col_owner_id' => array('owner_id'),
            'col_tour_id' => array('tour_id'),
            'col_status' => array('status'),
        );
    }

    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    function prepare_items() {
        global $wpdb, $_wp_column_headers, $expedition_reports_search_title;
        $screen = get_current_screen();
        $table = USER_TOUR_INVITATIONS_TABLE;
        

        // Preparing your query
        $query = "SELECT * FROM $table WHERE 1=1";
        
        if ( isset($_GET['search_for']) && isset($_GET['search']) && strlen($_GET['search'])>0 ):
            $query .= " AND ". $_GET['search_for'] . " = '" . $_GET['search']."'";
            $expedition_reports_search_title = __('Where', 'expedition'). " ".$_GET['search_for'] . " = " . $_GET['search'];
        else:
            $conditions = array();
            
            if ( isset($_GET['id']) && strlen($_GET['id']) > 9 ){
                $conditions["id"] = " id = '" . $_GET['id']."'";
            }
            
            if ( isset($_GET['user_id']) && (int)$_GET['user_id'] > 0 ){
                $conditions["user_id"] = " user_id = '" . $_GET['user_id']."'";
            }
            
            if ( isset($_GET['owner_id']) && (int)$_GET['owner_id'] > 0 ){
                $conditions["owner_id"] = " owner_id = '" . $_GET['owner_id']."'";
            }
            
            if ( isset($_GET['tour_id']) && (int)$_GET['tour_id'] > 0 ){
                $conditions["tour_id"] = " tour_id = '" . $_GET['tour_id']."'";
            }
            
            $conditions_str = "";
            if ( count($conditions)>0 ){
                $conditions_str = " ".implode( ' AND', $conditions);
            }
            
            $query .= $conditions_str;
             
            
        endif;
        
        $status = false;
        $valid_statuses = array( 0,1,2);

        if ( isset($_GET['status']) && in_array($_GET['status'], $valid_statuses) ){
            $status = $_GET['status'];
            $query .= " AND status=$status";
        }
        
        
        global $current_user;
        if( is_admin() && !current_user_can('administrator') ) {
            $query .= " AND owner_id={$current_user->ID}";
        }

        // Ordering parameters 
        $orderby = !empty($_GET["orderby"]) ? mysql_escape_string($_GET["orderby"]) : 'created_at';
        $order = !empty($_GET["order"]) ? mysql_escape_string($_GET["order"]) : 'DESC';
        if (!empty($orderby) & !empty($order)) {
            $query.=' ORDER BY ' . $orderby . ' ' . $order;
        }else{
            $query .= " ORDER BY created_at DESC";
        }

        // Pagination parameters
        //Number of elements in your table?
        $totalitems = $wpdb->query($query); //return the total number of affected rows
        //How many to display per page?
        $perpage = 20;
        //Which page is this?
        $paged = isset($_GET["paged"]) && !empty($_GET["paged"]) ? (int)$_GET["paged"] : '';
        //Page Number
        if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
            $paged = 1;
        }
        //How many pages do we have in total?
        $totalpages = ceil($totalitems / $perpage);
        //adjust the query to take pagination into account
        if (!empty($paged) && !empty($perpage)) {
            $offset = ($paged - 1) * $perpage;
            $query.=' LIMIT ' . (int) $offset . ',' . (int) $perpage;
        }

        /* -- Register the pagination -- */
        $this->set_pagination_args(array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ));
        //The pagination links are automatically built according to those parameters

        /* -- Register the Columns -- */
        $columns = $this->get_columns();
        $_wp_column_headers[$screen->id] = $columns;

        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        /* -- Fetch the items -- */
        $this->items = $wpdb->get_results($query);
        return $this; // chaining
    }

    /**
     * Display the rows of records in the table
     * @return string, echo the markup of the rows
     */
    function display_rows() {

        //Get the records registered in the prepare_items method
        $records = $this->items;
        $hidden = get_hidden_columns( $this->screen );

        //Get the columns registered in the get_columns and get_sortable_columns methods
        list( $columns, $hidden ) = $this->get_column_info();

        //Loop for each record
        if (!empty($records)) {

            foreach ($records as $rec) {
                        
                $user_fullname = get_user_meta( (int)$rec->user_id, 'first_name', true ) . ' ' .get_user_meta( (int)$rec->user_id, 'last_name', true );
                $owner_fullname = get_user_meta( (int)$rec->owner_id, 'first_name', true ) . ' ' . get_user_meta( (int)$rec->owner_id, 'last_name', true );
                
                //Open the line
                echo '<tr id="expedition_consultation_list_' . $rec->id . '" >';
                foreach ($columns as $column_name => $column_display_name) {

                    //Style attributes for each col
                    
                    $class = "class='$column_name column-$column_name'";
                    $style = "";
                    if (in_array($column_name, $hidden))
                        $style = ' style="display:none;"';
                    $attributes = $class . $style;
                    
                    
                    //Display the cell
                    switch ($column_name) {
                        
                        
                        case "col_id": 
                            echo '<td ' . $attributes . '>'.
                                    $rec->id .
                                '</td>';
                            break;
                        
                        case "col_created_at": echo '<td ' . $attributes . '>' . 
                                Expedition_Helper::dateFromDB($rec->created_at, "d/m/Y"). "<br>".
                                Expedition_Helper::dateFromDB($rec->created_at, "H:i:s")
                                . '</td>';
                            break;
                        
                        case "col_updated_at": echo '<td ' . $attributes . '>' . 
                                Expedition_Helper::dateFromDB($rec->updated_at, "d/m/Y"). "<br>".
                                Expedition_Helper::dateFromDB($rec->updated_at, "H:i:s")
                                . '</td>';
                            break;
                        
                        case "col_owner_id":
                            
                            echo '<td ' . $attributes . '>';
                            echo '<a href="/wp-admin/user-edit.php?user_id='.$rec->owner_id.'&TB_iframe=true&width=600&height=650#profile-page" name="User '.$rec->owner_id.'" class="thickbox">'."ID $rec->owner_id <br/> Name : $owner_fullname" ."</a></li>";
                            echo '</td>';
                            break;
                            
                        case "col_user_id":
                            
                            echo '<td ' . $attributes . '>';
                            echo '<a href="/wp-admin/user-edit.php?user_id='.$rec->user_id.'&TB_iframe=true&width=600&height=650#profile-page" name="User '.$rec->user_id.'" class="thickbox">'."ID $rec->user_id <br/> Name : $user_fullname" ."</a></li>";
                            echo '</td>';
                            
                            break;
                        
                        case "col_tour_id":
                            
                            echo '<td ' . $attributes . '>';
                            echo '<a href="'. admin_url('post.php?post='.$rec->tour_id).'&action=edit">'.get_post_field('post_title', $rec->tour_id) ."</a></li>";
                            echo '</td>';
                            
                            break;
                        
                        case "col_status": echo '<td ' . $attributes . '>' . 
                                Expedition_Helper::getTourStatusFromCode($rec->status)
                                . '</td>';
                            break;
                        
                    }
                }

                //Close the line
                echo'</tr>';
            }
        }
    }

}
    

// settings page instance
//if ( is_admin() )
if( current_user_can('edit_tours') ){
    new ExpeditionAdminTourInvitationsTable_Settings();
}


class ExpeditionAdminTourInvitationsTable_Settings {

    public function __construct() {
        add_action('admin_menu', array(&$this, 'panel_pages'));
    }

    /**
     * We add a button below the settings section on the administrative backend
     */
    function panel_pages() {
        add_menu_page( EXPEDITION_TOUR_INVITATIONS_TITLE, EXPEDITION_TOUR_INVITATIONS_TITLE, 'edit_tours', EXPEDITION_TOUR_INVITATIONS, array(&$this, 'bootstrap'), 'dashicons-format-aside', 18 );
        
        $option = 'per_page';
        $args = array(
            'label'=> 'Por página',
            'default' => 20,
            'option' => 'send_log_per_page'
        );
        
        add_screen_option($option, $args);
    }

    /**
     * Main Backend Bootstrap
     */
    public function bootstrap() {
        global $expedition_reports_search_title;
        
        if ( !current_user_can('edit_tours') ) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        $admin_table = new ExpeditionAdminTourInvitationsTable();
        $admin_table->prepare_items();
        
        ?>
        
        

        <div class="wrap">
            <?php add_thickbox(); ?>
            <div id="icon-users" class="icon32"><br/></div>
            <h2>
                <?=  __('Invitations list', 'expedition')  ?>
            </h2>
            
            <?php if ( strlen($expedition_reports_search_title) >0 ): ?>
            <p>
                <?= $expedition_reports_search_title ?>
            </p>
            <?php endif; ?>
            
            <hr>
            
            <form method="get" class="expedition_search_form">
                <input type="hidden" name="page" value="<?=EXPEDITION_TOUR_INVITATIONS?>">
                <select name="search_for">
                    <option value="id" <?php selected( isset($_GET['search_for']) && $_GET['search_for'] == "id", true  ) ?>>ID</option>
                    <option value="user_id" <?php selected( isset($_GET['search_for']) && $_GET['search_for'] == "user_id", true  ) ?>>User ID</option>
                    <option value="owner_id" <?php selected( isset($_GET['search_for']) && $_GET['search_for'] == "owner_id", true  ) ?>>Owner ID</option>
                    <option value="owner_id" <?php selected( isset($_GET['search_for']) && $_GET['search_for'] == "tour_id", true  ) ?>>Tour ID</option>
                </select>
                
                <input class="expedition_search_input" type="text" name="search" value="<?= isset( $_GET['search'] ) ? $_GET['search'] : '' ?>">

                <input type="submit" id="search-submit" class="button" value="Buscar">
            </form>
            
            
            
            <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
            <form id="log-filter" method="get">
                <!-- For plugins, we also need to ensure that the form posts back to our current page -->
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                <!-- Now we can render the completed list table -->
                <?php $admin_table->display(); ?>
            </form>
            
        </div>
            <style type="text/css">
                #col_id{width : 75px;}
                #col_created_at{width : 110px;}
                #col_updated_at{width : 110px;}
                #col_status{width : 90px;}
            </style>
        <?php
    }
}


?>
