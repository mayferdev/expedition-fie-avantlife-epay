<?php
 
if(!class_exists('WP_List_Table')){
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if ( !defined('EXPEDITION_BILLS') ){
    define( "EXPEDITION_BILLS", "expedition_bills");
}

if ( !defined('EXPEDITION_BILLS_TITLE') ){
    define( "EXPEDITION_BILLS_TITLE", __('Bills', 'expedition'));
}

global $expedition_reports_search_title;

/**
 * Display the information in @see BAM_DB
 * 
 */
class ExpeditionAdminBillssTable extends WP_List_Table {
    
    public function search_box($text, $input_id) {
        parent::search_box($text, $input_id);
    }
    
    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct() {
        parent::__construct(array(
            'singular' => EXPEDITION_BILLS.'_at', //Singular label
            'plural' => EXPEDITION_BILLS.'_at', //plural label, also this well be one of the table css class
            'ajax' => false //We won't support Ajax for this table
        ));
    }

    /**
     * Define the columns that are going to be used in the table
     * @return array $columns, the array of columns to use with the table
     */
    function get_columns() {
        $columns = array(
            
            'col_id' => __('ID', 'expedition'),
            //'col_updated_at' => __('Updated','expedition'),
            'col_user_id' => __('User','expedition'),
            'col_booking_id' => __('Booking','expedition'),
            'col_transaction_id' => __('Transaction','expedition'),
            'col_amount' => __('Amount','expedition'),
            'col_created_at' => __('Created','expedition'),
        );
        
        if( is_admin() && !current_user_can('administrator') ) {
//            unset( $columns['col_meta'] );
        }
        
        if( is_admin() && !current_user_can('administrator') && !current_user_can('editor') ) {
//            unset( $columns['col_owner_id'] );
        }
        
        return $columns;
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
            //'col_updated_at' => array('updated_at'),
            'col_user_id' => array('user_id'),
            'col_booking_id' => array('booking_id'),
            'col_transaction_id' => array('transaction_id'),
            'col_amount' => array('amount'),
            'col_created_at' => array('created_at'),
        );
    }

    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    function prepare_items() {
        global $wpdb, $_wp_column_headers, $expedition_reports_search_title;
        $screen = get_current_screen();
        $table = BILLS_TABLE;
        

        // Preparing your query
        $query = "SELECT * FROM $table WHERE 1=1";
        
        if ( isset($_GET['search_for']) && isset($_GET['search']) && strlen($_GET['search'])>0 ):
            $query .= " AND ". $_GET['search_for'] . " = '" . $_GET['search']."'";
            $expedition_reports_search_title = __('Where', 'expedition'). " ".$_GET['search_for'] . " = " . $_GET['search'];
        else:
            $conditions = array();
            
            if ( isset($_GET['id']) && strlen(@$_GET['id']) > 9 ){
                $conditions["id"] = " id = '" . $_GET['id']."'";
            }
            
            if ( isset($_GET['user_id']) && (int)$_GET['user_id'] > 0 ){
                $conditions["user_id"] = " user_id = '" . $_GET['user_id']."'";
            }
            
            if ( isset($_GET['tour_id']) && (int)$_GET['tour_id'] > 0 ){
                $conditions["tour_id"] = " tour_id = '" . $_GET['tour_id']."'";
            }
            
            if ( isset($_GET['source']) && (int)$_GET['source'] > 0 ){
                $conditions["source"] = " source = '" . $_GET['source']."'";
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
        if( is_admin() && !current_user_can('administrator') && !current_user_can('editor') ) {
//            $query .= " AND owner_id={$current_user->ID}";
        }

        // Ordering parameters 
        $orderby = !empty(@$_GET["orderby"]) ? mysql_escape_string(@$_GET["orderby"]) : 'created_at';
        $order = !empty(@$_GET["order"]) ? mysql_escape_string(@$_GET["order"]) : 'DESC';
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
                // $owner_fullname = get_user_meta( (int)$rec->owner_id, 'first_name', true ) . ' ' . get_user_meta( (int)$rec->owner_id, 'last_name', true );
                
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
                            
                        case "col_user_id":
                            
                            $user = get_user_by('ID', $rec->user_id);
                            $email = $user->user_email ? $user->user_email : '';
                            
                            echo '<td ' . $attributes . '>';
                            echo '<a href="/wp-admin/user-edit.php?user_id='.$rec->user_id.'&TB_iframe=true&width=600&height=650#profile-page" name="User '.$rec->user_id.'" class="thickbox">'
                                    ."ID $rec->user_id <br/> Name : $user_fullname" 
                                    ."<br/>Email : $email" 
                                    ."</a></li>";
                            echo '</td>';
                            
                            break;
                        
                        case "col_tour_id":
                            
                            echo '<td ' . $attributes . '>';
                            echo '<a href="'. admin_url('post.php?post='.$rec->tour_id).'&action=edit">'.get_post_field('post_title', $rec->tour_id) ."</a></li>";
                            echo '</td>';
                            
                            break;
                        
                        case "col_source":
                            
                            echo '<td ' . $attributes . '>';
                            echo $rec->source;
                            echo '</td>';
                            
                            break;
                        
                        case "col_meta":
                            
                            /*
                            ob_start();
                            var_dump_pre( json_decode($rec->tour_meta) );
                            $content = ob_get_contents();
                            ob_clean();
                            
                            $detail_div = '<div id="booking_info_'.$rec->id.'" style="display:none;">'.
                                '<div class="booking_container">'.
                                    $content .
                                '</div>'.
                            '</div>';*/
                            
                            $detail_div = '<div id="booking_info_'.$rec->id.'" style="display:none;">'.
                                '<div class="booking_container">';
                            ob_start();
                                $tour_meta = json_decode($rec->tour_meta);
                                if ( $tour_meta && is_array($tour_meta) && count($tour_meta)>0 ){
                                    ?>
                                    <table class="wf-striped-table wf-fixed-table">
                                        <thead>
                                            <tr>
                                                <th><?= __('Title', 'expedition') ?></th>
                                                <th><?= __('Price', 'expedition') ?></th>
                                                <th><?= __('QTY', 'expedition') ?></th>
                                                <th><?= __('Total', 'expedition') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                    $total = 0;
                                    foreach ($tour_meta as $key => $meta) {
                                        $total+=$meta->total;
                                        ?>
                                        <tr <?= $key % 2 == 0 ? 'class="odd"' : 'class="even"' ?>>
                                            <td><?=$meta->title?></td>
                                            <td><?=$meta->price?></td>
                                            <td><?=$meta->qty?></td>
                                            <td><?=$meta->total?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                        </tbody>
                                    </table>
                                    <p style="text-align:right; margin-top: 20px;">
                                        <strong>Total Q<?= number_format($total,2) ?></strong>
                                    </p>
                                    <?php
                                }else{
                                    echo '<p>'. __('No data found!', 'expedition') .'</p>';
                                }
                            $content = ob_get_contents();
                            ob_clean();
                                    
                            $detail_div .= $content.'</div>'.
                            '</div>';
                            
                            
                            $link = '<a href="#TB_inline?width=600&height=550&inlineId=booking_info_'.$rec->id.'" name="Booking ID '.$rec->id.'" class="thickbox">View</a>';
                            
                            echo '<td ' . $attributes . '>';
                                echo $link . $detail_div;
                            echo '</td>';
                            
                            break;
                            
                        case "col_expeditioners":
                            
                            $rec->id;
                            //$data = (array)Expedition_Helper::getTourExpeditionersByTour($rec->tour_id);
                            //$data = (array)Expedition_Helper::getTourExpeditioners($rec->tour_id);
                            $data = (array)Expedition_Helper::getTourExpeditioners($rec->tour_id, $rec->user_id);
                            
                            $detail_div = '<div id="expeditioners_info_'.$rec->id.'" style="display:none;">'.
                                '<div class="expeiditioners_container">';
                            ob_start();
                                $tour_meta = json_decode($rec->tour_meta);
                                if ( $data && is_array($data) && count($data)>0 ){
                                    ?>
                                    <table class="wf-striped-table wf-fixed-table">
                                        <thead>
                                            <tr>
                                                <th><?= __('First Name', 'expedition') ?></th>
                                                <th><?= __('Last Name', 'expedition') ?></th>
                                                <th><?= __('Age', 'expedition') ?></th>
                                                <th><?= __('DPI/Passport', 'expedition') ?></th>
                                                <th><?= __('Phone', 'expedition') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                    foreach ($data as $key => $meta) {
                                        ?>
                                        <tr <?= $key % 2 == 0 ? 'class="odd"' : 'class="even"' ?>>
                                            <td><?=$meta->first_name?></td>
                                            <td><?=$meta->last_name?></td>
                                            <td><?=$meta->age?></td>
                                            <td><?=$meta->dpi_passport?></td>
                                            <td><?=$meta->phone?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                        </tbody>
                                    </table>
                                    <?php
                                }else{
                                    echo '<p>'. __('No data found!', 'expedition') .'</p>';
                                }
                            $content = ob_get_contents();
                            ob_clean();
                                    
                            $detail_div .= $content.'</div>'.
                            '</div>';
                            
                            
                            $link = '<a href="#TB_inline?width=600&height=550&inlineId=expeditioners_info_'.$rec->id.'" name="Expeditioners for Booking ID '.$rec->id.'" class="thickbox">View</a>';
                            
                            echo '<td ' . $attributes . '>';
                                echo $link . $detail_div;
                            echo '</td>';
                            
                            break;
                        
                        case "col_status": echo '<td ' . $attributes . '>';
                                echo '<small title="'.Expedition_Helper::getTourBookingStatusInfoFromCode($rec->status).'">'.Expedition_Helper::getTourBookingStatusFromCode($rec->status).'</small>';
                                
                                $nonce = wp_create_nonce("confirm_single_booking");
                                
                                if ( $rec->status == BOOKING_PENDING_CONFIRM ){
                                    echo '<p class="buttons"><a href="#" '
                                    .'data-nonce="' . $nonce . '" '
                                    .'data-booking_id="' . $rec->id . '" '
                                    . 'class="confirm_single_booking button button-small">'.__('Confirm booking', 'expedition' ).'</a></p>';
                                }
                                
                                echo '</td>';
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
    new ExpeditionAdminBillssTable_Settings();
}


class ExpeditionAdminBillssTable_Settings {

    public function __construct() {
        add_action('admin_menu', array(&$this, 'panel_pages'));
    }

    /**
     * We add a button below the settings section on the administrative backend
     */
    function panel_pages() {
        add_menu_page( EXPEDITION_BILLS_TITLE, EXPEDITION_BILLS_TITLE, 'edit_tours', EXPEDITION_BILLS, array(&$this, 'bootstrap'), 'dashicons-format-aside', 18 );
        
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
        
        // current_user_can('editor') || current_user_can('administrator')
        
        if ( !current_user_can('edit_tours') ) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        $admin_table = new ExpeditionAdminBillssTable();
        $admin_table->prepare_items();
        
        ?>
        
        

        <div class="wrap">
            <?php add_thickbox(); ?>
            <div id="icon-users" class="icon32"><br/></div>
            <h2>
                <?=  __('Bills list', 'expedition')  ?>
            </h2>
            
            <?php if ( strlen($expedition_reports_search_title) >0 ): ?>
            <p>
                <?= $expedition_reports_search_title ?>
            </p>
            <?php endif; ?>
            
            <hr>
            
            <form method="get" class="expedition_search_form">
                <input type="hidden" name="page" value="<?=EXPEDITION_BILLS?>">
                <select name="search_for">
                    <option value="id" <?php selected( isset($_GET['search_for']) && $_GET['search_for'] == "id", true  ) ?>>ID</option>
                    <option value="user_id" <?php selected( isset($_GET['search_for']) && $_GET['search_for'] == "user_id", true  ) ?>>User ID</option>
                    <option value="tour_id" <?php selected( isset($_GET['search_for']) && $_GET['search_for'] == "tour_id", true  ) ?>>Tour ID</option>
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
                #col_status{width : 140px;}
            </style>
        <?php
    }
}