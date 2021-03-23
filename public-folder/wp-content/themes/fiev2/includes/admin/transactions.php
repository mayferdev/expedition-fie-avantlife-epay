<?php
 
if(!class_exists('WP_List_Table')){
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if ( !defined('EXPEDITION_TRANSACTIONS') ){
    define( "EXPEDITION_TRANSACTIONS", "expedition_transactions");
}

if ( !defined('EXPEDITION_TRANSACTIONS_TITLE') ){
    define( "EXPEDITION_TRANSACTIONS_TITLE", __('Transacciones', 'expedition'));
}

global $expedition_reports_search_title;

/**
 * Display the information in @see BAM_DB
 * 
 */
class ExpeditionAdminTransactionsTable extends WP_List_Table {
    
    public function search_box($text, $input_id) {
        parent::search_box($text, $input_id);
    }
    
    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct() {
        parent::__construct(array(
            'singular' => EXPEDITION_TRANSACTIONS.'_at', //Singular label
            'plural' => EXPEDITION_TRANSACTIONS.'_at', //plural label, also this well be one of the table css class
            'ajax' => false //We won't support Ajax for this table
        ));
    }

    /**
     * Define the columns that are going to be used in the table
     * @return array $columns, the array of columns to use with the table
     */
    function get_columns() {
        $columns = array(
            'col_created_at' => __('Date','expedition'),
            'col_id' => __('ID', 'expedition'),
            // 'col_transaction_id' => __('ID inside Gateway', 'expedition'),
            'col_booking_id' => __('Booking','expedition'),
            'col_user_id' => __('User','expedition'),
            
            //'col_' => __('Meta','expedition'),
            
            'col_amount' => __('Amount','expedition'),
            //'col_final_amount' => __('Final Amount','expedition'),
            'col_gateway' => __('Gateway','expedition'),
            'col_meta' => __('Metadata','expedition'),
            'col_image' => __('Image','expedition'),
            // 'col_ws_response' => __('WS response','expedition'),
            
            'col_success' => __('Success','expedition'),  
        );
        
        if( is_admin() && !current_user_can('administrator') ) {
            unset( $columns['col_meta'] );
        }
        
        return $columns;
    }

    /**
     * Add extra markup in the toolbar before or after the list
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
    // public function get_sortable_columns() {
    //     return $sortable = array(
    //         'col_id' => array('id'),
    //         'col_created_at' => array('created_at'),
    //         'col_user_id' => array('user_id'),
    //         'col_gateway' => array('gateway'),
    //         'col_success' => array('success'),
    //         'col_booking_id' => array('booking_id'),
    //     );
    // }

    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    function prepare_items() {
        global $wpdb, $_wp_column_headers, $expedition_reports_search_title;
        $screen = get_current_screen();
        $table = $wpdb->prefix . 'transactions';

        // Preparing your query
        $query = "SELECT * FROM $table";
        
        if ( isset($_GET['search_for']) && isset($_GET['search']) ):
            $query .= " WHERE ". $_GET['search_for'] . " LIKE '%" . trim($_GET['search']) ."%'";
            $expedition_reports_search_title = __('Where', 'expedition'). " ".$_GET['search_for'] . " = " . $_GET['search'];
        else:
            $conditions = array();
            $conditions_ids = array();
            
            if ( isset($_GET['id']) && strlen($_GET['id']) > 9 ){
                $conditions["id"] = " id = '" . $_GET['id']."'";
                $conditions_ids["id"] = $_GET['id'];
            }
            
            if ( isset($_GET['patient_id']) && (int)$_GET['patient_id'] > 0 ){
                $conditions["patient_id"] = " patient_i = '" . $_GET['patient_id']."'";
                $conditions_ids["Patient id"] = $_GET['patient_id'];
            }
            
            $conditions_str = "";
            if ( count($conditions)>0 ){
                $conditions_str = " WHERE".implode( ' AND', $conditions);
            }
            
            $query .= $conditions_str;
            
        endif;
        
        global $current_user;
        if( is_admin() && !current_user_can('administrator') ) {
            if (strpos($query, 'WHERE') === false ){
                $query .= ' WHERE 1=1';
            }
            $query .= " AND owner_id = {$current_user->ID} ";
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
//        var_dump_pre($query);
//        var_dump_pre($this->items);
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
                
                
                
                
                
                
                //Open the line
                echo '<tr id="expedition_payment_info_failed_list_' . $rec->id . '" >';
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
                            
                            echo '<td ' . $attributes . '>' .
                                    $rec->id .
                                '</td>';
                            break;
                        case "col_transaction_id": 
                            
                            echo '<td ' . $attributes . '>';
                                    if ( $rec->ws_response ){
                                        $ws_response = unserialize($rec->ws_response);
                                        if ( isset($ws_response['transaction_id']) ){
                                            echo $ws_response['transaction_id'];
                                        }
                                    }
                                echo '</td>';
                            break;
                        
                        case "col_booking_id": 
                            
                            $link = '<a href="'. admin_url('admin.php?page=expedition_tour_bookings&search_for=id&search='.$rec->booking_id).'" target="_blank">View</a>';
                            
                            echo '<td ' . $attributes . '>'. 
                                     "ID {$rec->booking_id} $link" .
                                '</td>';
                            
                            
                            break;
                        
                        case "col_user_id": 
                            $patient_paid_full_name = get_user_meta( (int)$rec->user_id, 'first_name', true );
                            $patient_link = admin_url("user-edit.php?user_id={$rec->user_id}");
                            echo '<td ' . $attributes . '>' . 
                                "<a href='".$patient_link."' target='_blank'>"."ID $rec->user_id - Name : $patient_paid_full_name" ."</a>".
                                '</td>';
                            break;
                        
                        case "col_success": echo '<td ' . $attributes . '>' . 
                                //TRANSACTION_PENDING
                                Expedition_Helper::getTransactionStatusFromCode($rec->success)
                                . '</td>';
                            break;
                        
                        case "col_currency": echo '<td ' . $attributes . '>' . 
                                $rec->currency
                                . '</td>';
                            break;
                        
                        case "col_amount": echo '<td ' . $attributes . '>' . 
                                $rec->currency.' '.$rec->amount
                                . '</td>';
                            break;
                            
                        
                        
                         //case "col_final_amount": echo '<td ' . $attributes . '>' . 
                           //     $rec->currency.' '.$rec->amount
                           //        . '</td>';
                          //  break;    
                        
                        case "col_gateway": echo '<td ' . $attributes . '>' . 
                                ( $rec->gateway == 'pagalocard' ? 'Credit/Debit Card' : $rec->gateway )
                                . '</td>';
                            break;
                        
                        case "col_image": 
                            $meta = json_decode($rec->meta);
                            $_html = isset($meta->attachment_url) ? "<a href='$meta->attachment_url' target='_blank'><img width='80' height='auto' src='{$meta->attachment_url}'/></a>" : '';
                            
                            echo '<td ' . $attributes . '>' . 
                                $_html
                                . '</td>';
                            break;
                        
                        case "col_created_at": echo '<td ' . $attributes . '>' . 
                                Expedition_Helper::dateFromDB($rec->created_at, "d/m/Y"). "<br>".
                                Expedition_Helper::dateFromDB($rec->created_at, "H:i:s")
                                . '</td>';
                            break;
                        
                        case "col_ws_response": 
                            
                            ob_start();
                            
                            if ( substr($rec->ws_response, 0, 1 ) == "a" ){
                                echo '<pre>';
                                var_dump(unserialize($rec->ws_response));
                                echo '</pre>';
                            }else{
                                echo $rec->ws_response;
                            }
                            
                            $content = ob_get_clean();
                            
                            $detail_div = '<div id="payment_info_ws_response_'.$rec->id.'" style="display:none;">'.
                                '<div class="booking_container">'.
                                    $content.
                                '</div>'.
                            '</div>';
                            
                            $link = '<a href="#TB_inline?width=600&height=550&inlineId=payment_info_ws_response_'.$rec->id.'" name="Payment Info ID '.$rec->id.'" class="thickbox">'. __('View') .'</a>';
                            
                            echo '<td ' . $attributes . '>' . $detail_div .
                                $link
                                . '</td>';
                            break;
                        
                        case "col_nit": echo '<td ' . $attributes . '>' . 
                                $rec->nit
                                . '</td>';
                            break;
                        
                        case "col_ip": echo '<td ' . $attributes . '>' . 
                                $rec->ip
                                . '</td>';
                            break;
                        
                        case "col_meta":
                            
                            /*ob_start();
                            var_dump_pre( json_decode($rec->meta) );
                            $content = ob_get_contents();
                            ob_clean();
                            
                            $detail_div = '<div id="booking_info_'.$rec->id.'" style="display:none;">'.
                                '<div class="booking_container">'.
                                    $content .
                                '</div>'.
                            '</div>';
                            
                            $link = '<a href="#TB_inline?width=600&height=550&inlineId=booking_info_'.$rec->id.'" name="Booking ID '.$rec->id.'" class="thickbox">View</a>';
                            
                            echo '<td ' . $attributes . '>';
                                echo $link . $detail_div;
                            echo '</td>';*/
                            
                            $col_meta = json_decode($rec->meta);
                            echo '<td ' . $attributes . '>';
                                if ($col_meta && is_object($col_meta) ){
                                    foreach ($col_meta as $key => $value) {
                                        $key = ucfirst(str_replace(array('_'), array(' '), $key));
                                        if (strpos($value, 'https://') !== false ){
                                            $value = "<a href='$value' target='_blank'>".__('Visit', 'expedition')."</a>";
                                        }
                                        echo "<strong>$key</strong> : $value <br/>";
                                    }
                                }
                            echo '</td>';
                            
                            break;
                        
                        case "col_fields": 
                            
                            ob_start();
                            if (strlen($rec->fields) ){
                                echo '<pre>';
                                var_dump(unserialize($rec->fields));
                                echo '</pre>';
                            }else{
                                echo 'No fields sent';
                            }
                            $content = ob_get_clean();
                            
                            $detail_div = '<div id="payment_info_fields_'.$rec->id.'" style="display:none;">'.
                                '<div class="booking_container">'.
                                    $content.
                                '</div>'.
                            '</div>';
                            
                            $link = '<a href="#TB_inline?width=600&height=550&inlineId=payment_info_fields_'.$rec->id.'" name="Payment Info ID '.$rec->id.'" class="thickbox">'. __('View') .'</a>';
                            
                            echo '<td ' . $attributes . '>' . $detail_div .
                                $link
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
    new ExpeditionAdminTransactionsTable_Settings();
}


class ExpeditionAdminTransactionsTable_Settings {

    public function __construct() {
        add_action('admin_menu', array(&$this, 'panel_pages'));
    }

    /**
     * We add a button below the settings section on the administrative backend
     */
    function panel_pages() {
        add_menu_page( EXPEDITION_TRANSACTIONS_TITLE, EXPEDITION_TRANSACTIONS_TITLE, 'edit_tours', EXPEDITION_TRANSACTIONS, array(&$this, 'bootstrap'), 'dashicons-cart', 18 );
        
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
        
        $admin_table = new ExpeditionAdminTransactionsTable();
        $admin_table->prepare_items();
        
        ?>
        
        

        <div class="wrap">
            <?php add_thickbox(); ?>
            <div id="icon-users" class="icon32"><br/></div>
            <h2>
                <?=  __('Transactions list', 'expedition')  ?>
            </h2>
            
            <?php if ( strlen($expedition_reports_search_title) >0 ): ?>
            <p>
                <?= $expedition_reports_search_title ?>
            </p>
            <?php endif; ?>
            
            <hr>
            
            <form method="get" class="expedition_search_form">
                <input type="hidden" name="page" value="<?=EXPEDITION_TRANSACTIONS?>">
                <select name="search_for">
                    <option value="id" <?php selected( isset($_GET['search_for']) && $_GET['search_for'] == "id", true  ) ?>>Transaction ID</option>
                    <option value="user_id" <?php selected( isset($_GET['search_for']) && $_GET['search_for'] == "user_id", true  ) ?>>User ID</option>
                    <option value="booking_id" <?php selected( isset($_GET['search_for']) && $_GET['search_for'] == "booking_id", true  ) ?>>Booking ID</option>
                    <option value="gateway" <?php selected( isset($_GET['search_for']) && $_GET['search_for'] == "gateway", true  ) ?>>Gateway</option>
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
            
            <style type="text/css">
                #col_id{width : 80px}
                #col_amount{width : 80px}
                #col_transaction_id{width : 190px}
                #col_booking_id{width : 120px}
                #col_ws_response{width : 120px}
                #col_gateway{width : 90px}
                #col_success{width : 90px}
                #col_created_at{width : 100px}
            </style>
        </div>
        <?php
    }
}


?>
