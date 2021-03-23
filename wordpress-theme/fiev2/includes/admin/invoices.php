<?php
 
if(!class_exists('WP_List_Table')){
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if ( !defined('DOCTOFY_INVOICES') ){
    define( "DOCTOFY_INVOICES", "expedition_invoices");
}

if ( !defined('DOCTOFY_INVOICES_TITLE') ){
    define( "DOCTOFY_INVOICES_TITLE", __('Invoices', 'expedition'));
}

global $expedition_reports_search_title;

/**
 * Display the information in @see BAM_DB
 * 
 */
class ExpeditionAdminInvoicesTable extends WP_List_Table {
    
    public function search_box($text, $input_id) {
        parent::search_box($text, $input_id);
    }
    
    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct() {
        parent::__construct(array(
            'singular' => DOCTOFY_INVOICES.'_at', //Singular label
            'plural' => DOCTOFY_INVOICES.'_at', //plural label, also this well be one of the table css class
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
            'col_consultation_id' => __('Consultation','expedition'),
            'col_transaction_id' => __('Transaction','expedition'),
            'col_payer_patient_id' => __('Patient who paid','expedition'),
            
//            'col_currency' => __('Currency','expedition'),
            'col_amount' => __('Amount','expedition'),
            'col_gateway' => __('Gateway','expedition'),
            
            'col_ws_sent' => __('WS Sent','expedition'),
            'col_ws_response' => __('WS response','expedition'),
            
            'col_success' => __('Success','expedition'),
            
            'col_created_at' => __('Date','expedition'),
            
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
            'col_payer_patient_id' => array('payer_patient_id'),
            'col_gateway' => array('gateway'),
            'col_consultation_id' => array('consultation_id'),
        );
    }

    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    function prepare_items() {
        global $wpdb, $_wp_column_headers, $expedition_reports_search_title;
        $screen = get_current_screen();
        $table = $wpdb->prefix . 'invoices';

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
                
                $consultation = Expedition_Helper::getConsultation($rec->consultation_id);
                
                
                
                
                Expedition_Helper::fillWpFieldsFromFirebase($consultation->patient_id);
                $patient_fullname = get_user_meta( (int)$consultation->patient_id, 'first_name', true );
                $patient_link = admin_url("user-edit.php?user_id={$consultation->patient_id}");
                $patient_name_link = "<a href='".$patient_link."' target='_blank'>"."ID $consultation->patient_id - Name : $patient_fullname" ."</a></li>";
                /***********/
                
                $doctor_link = admin_url("user-edit.php?user_id={$consultation->doctor_id}");
                $doctor_fullname = Expedition_Helper::getDoctorName($consultation->doctor_id);
                $doctor_name_link = "<a href='".$doctor_link."' target='_blank'>"."ID $consultation->doctor_id - Name : $doctor_fullname" ."</a></li>";
                
                if ( $consultation->started_by == $consultation->patient_id ){
                    $started_by_name_link = $patient_name_link;
                }else{
                    $started_by_name_link = $doctor_name_link;
                }
                
                if ( $consultation->finished_by == $consultation->patient_id ){
                    $finished_by_name_link = $patient_name_link;
                }else if ( $consultation->finished_by == $consultation->doctor_id ){
                    $finished_by_name_link = $doctor_name_link;
                }else{
                    $finished_by_name_link = __('Open consultation', 'expedition');
                }
                
                if ( (int)$consultation->status > 0 ){
                    $finished_at = Expedition_Helper::dateFromDB($consultation->finished_at, "d/m/Y H:i:s");
                    
                    $finished_at_two_lines =
                                Expedition_Helper::dateFromDB($consultation->finished_at, "d/m/Y"). "<br>".
                                Expedition_Helper::dateFromDB($consultation->finished_at, "H:i:s");
                }else{
                    $finished_at = $finished_at_two_lines = __('Open consultation', 'expedition');
                }
                
                $started_at = Expedition_Helper::dateFromDB($consultation->created_at, "d/m/Y H:i:s");
                $status = Expedition_Helper::getStatusFromCode($consultation->status);
                
                $prescription_aurl = "";
                $prescription_div = __("None",'expedition');
                if ( (int)$consultation->prescription_aid > 0 ){
                    $prescription_aurl = wp_get_attachment_url($consultation->prescription_aid);
                    $prescription_div = '<a href="'. admin_url('upload.php?item='.$consultation->prescription_aid) .'" target="_blank"><img src="'.$prescription_aurl.'" class="prescription_img"/></a>';
                }
                
                
                
                
                
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
                        
                        case "col_consultation_id": 
                            
                            $detail_div = '<div id="payment_info_'.$rec->id.'" style="display:none;">'.
                                '<div class="consultation_container">'.
                                    
                                    "<ul>".
                                        "<li><strong>Patient</strong> : $patient_name_link".
                                        "<li><strong>Doctor</strong> : $doctor_name_link".

                                        "<li><strong>Started by</strong> : $started_by_name_link".
                                        "<li><strong>Started at</strong> : $started_at".
                                        "<li><strong>Finished by</strong> : $finished_by_name_link".
                                        "<li><strong>Finished at</strong> : $finished_at".
                                        "<li><strong>Status</strong> : $status".
                                        
                                        "<li><strong>Amount charged</strong> : $$rec->charge_usd".
                                        "<li><strong>Currency code</strong> : $rec->charge_currency_code".
                                        "<li><strong>Aprox local charged</strong> : {$rec->charge_currency_symbol}$rec->charge_local".
                                        "<li><strong>Aprox currency rate</strong> : $rec->currency_rate".
                                                
                                        "<li><strong>Doctor fee</strong> : $$rec->doctor_fee".
                                        "<li><strong>Expedition fee</strong> : $$rec->expedition_fee".
                                        
                                        "<li><strong>Diagnostic</strong> : {$rec->diagnostic}".
                                        "<li><strong>Prescription</strong> : {$rec->diagnostic}".
                                        "<li><strong>Prescription attachment</strong> : $prescription_div".
                                        "<li><strong>Annotations</strong> : {$rec->annotations}".
                                            
                                    "</ul>".
                                            
                                '</div>'.
                            '</div>';
                            
                            $link = '<a href="#TB_inline?width=600&height=550&inlineId=payment_info_'.$rec->id.'" name="Consultation ID '.$consultation->id.'" class="thickbox">View</a>';
                            
                            /*echo '<td ' . $attributes . '>' . $detail_div. 
                                     "<br>$link" .
                                '</td>';*/
                            $link = '<a href="'.admin_url("admin.php?page=expedition_consultations&search_for=id&search={$consultation->id}").'" target="_blank">View</a>';
                            
                            echo '<td ' . $attributes . '>' . $detail_div. 
                                     "{$consultation->id} $link" .
                                '</td>';
                            
                            
                            break;
                        
                        case "col_payer_patient_id": 
                            $patient_paid_full_name = get_user_meta( (int)$rec->payer_patient_id, 'first_name', true );
                            $patient_link = admin_url("user-edit.php?user_id={$rec->payer_patient_id}");
                            echo '<td ' . $attributes . '>' . 
                                "<a href='".$patient_link."' target='_blank'>"."ID $rec->payer_patient_id - Name : $patient_paid_full_name" ."</a>".
                                '</td>';
                            break;
                        
                        case "col_success": echo '<td ' . $attributes . '>' . 
                                ( $rec->status ? 'YES' : 'NO' )
                                . '</td>';
                            break;
                        
                        case "col_currency": echo '<td ' . $attributes . '>' . 
                                $rec->currency
                                . '</td>';
                            break;
                        
                        case "col_amount": echo '<td ' . $attributes . '>' . 
                                $rec->currency.' '.$rec->total
                                . '</td>';
                            break;
                        
                        case "col_gateway": echo '<td ' . $attributes . '>' . 
                                $rec->gateway
                                . '</td>';
                            break;
                        
                        case "col_created_at": echo '<td ' . $attributes . '>' . 
                                Expedition_Helper::dateFromDB($rec->created_at, "d/m/Y"). "<br>".
                                Expedition_Helper::dateFromDB($rec->created_at, "H:i:s")
                                . '</td>';
                            break;
                        
                        case "col_ws_sent": 
                            
                            ob_start();
                            
                            if ( substr($rec->ws_response, 0, 1 ) == "a" ){
                                echo '<pre><code class="language-xml">';
                                var_dump(simplexml_load_string($rec->ws_sent));
                                echo '</code></pre>';
                            }else{
                                echo '<pre>';
                                var_dump(simplexml_load_string($rec->ws_sent));
                                echo '</pre>';
                            }
                            
                            $content = ob_get_clean();
                            
                            $detail_div = '<div id="payment_info_ws_sent_'.$rec->id.'" style="display:none;">'.
                                '<div class="consultation_container">'.
                                    $content.
                                '</div>'.
                            '</div>';
                            
                            $link = '<a href="#TB_inline?width=600&height=550&inlineId=payment_info_ws_sent_'.$rec->id.'" name="Invoice Info ID '.$rec->id.'" class="thickbox">'. __('View') .'</a>';
                            
                            echo '<td ' . $attributes . '>' . $detail_div .
                                $link
                                . '</td>';
                            break;
                            
                        case "col_ws_response": 
                            
                            ob_start();
                            
                            if ( substr($rec->ws_response, 0, 1 ) == "a" ){
                                echo '<pre><code class="language-xml">';
                                var_dump(simplexml_load_string($rec->ws_response));
                                echo '</code></pre>';
                            }else{
                                echo '<pre>';
                                var_dump(simplexml_load_string($rec->ws_response));
                                echo '</pre>';
                            }
                            
                            $content = ob_get_clean();
                            
                            $detail_div = '<div id="payment_info_ws_response_'.$rec->id.'" style="display:none;">'.
                                '<div class="consultation_container">'.
                                    $content.
                                '</div>'.
                            '</div>';
                            
                            $link = '<a href="#TB_inline?width=600&height=550&inlineId=payment_info_ws_response_'.$rec->id.'" name="Invoice Info ID '.$rec->id.'" class="thickbox">'. __('View') .'</a>';
                            
                            echo '<td ' . $attributes . '>' . $detail_div .
                                $link
                                . '</td>';
                            break;
                        
                        case "col_nit": echo '<td ' . $attributes . '>' . 
                                $rec->nit
                                . '</td>';
                            break;
                        case "col_transaction_id": echo '<td ' . $attributes . '>';
                                $consultation = Expedition_Helper::getConsultation($rec->consultation_id);
                                $transaction_link = admin_url("admin.php?page=expedition_transactions&search_for=id&search={$consultation->transaction_id}");
                                echo "<a href='".$transaction_link."' target='_blank'>"."ID $consultation->transaction_id" ."</a>";
                                echo  '</td>';
                            break;
                        
                        case "col_ip": echo '<td ' . $attributes . '>' . 
                                $rec->ip
                                . '</td>';
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
                                '<div class="consultation_container">'.
                                    $content.
                                '</div>'.
                            '</div>';
                            
                            $link = '<a href="#TB_inline?width=600&height=550&inlineId=payment_info_fields_'.$rec->id.'" name="Payment Info ID '.$rec->id.'" class="thickbox">'. __('View') .'</a>';
                            
                            echo '<td ' . $attributes . '>' . $detail_div .
                                $link
                                . '</td>';
                            break;
                        
                        case "col_doctor_id":
                            
                            //echo '<td ' . $attributes . '><a href="'.$doctor_link.'" target="_blank">'. 
                            //    "ID $rec->doctor_id <br> $doctor_fullname"
                            //    . '</a></td>';
                            echo '<td ' . $attributes . '>';
                            echo '<a href="/wp-admin/user-edit.php?user_id='.$rec->doctor_id.'&TB_iframe=true&width=600&height=550#profile-page" name="Doctor '.$rec->doctor_id.'" class="thickbox">'."ID $rec->doctor_id <br/> Name : $doctor_fullname" ."</a></li>";
                            echo '</td>';
                            break;
                            
                        case "col_patient_id":
                            
                            //echo '<td ' . $attributes . '><a href="'.$patient_link.'" target="_blank">'. 
                            //    "ID $rec->patient_id <br> $patient_fullname"
                            //    . '</a></td>';
                            
                            echo '<td ' . $attributes . '>';
                            echo '<a href="/wp-admin/user-edit.php?user_id='.$rec->patient_id.'&TB_iframe=true&width=600&height=550#profile-page" name="Patient '.$rec->patient_id.'" class="thickbox">'."ID $rec->patient_id <br/> Name : $patient_fullname" ."</a></li>";
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
if( current_user_can('editor') || current_user_can('administrator') ){
    new ExpeditionAdminInvoicesTable_Settings();
}


class ExpeditionAdminInvoicesTable_Settings {

    public function __construct() {
        add_action('admin_menu', array(&$this, 'panel_pages'));
    }

    /**
     * We add a button below the settings section on the administrative backend
     */
    function panel_pages() {
        add_menu_page( DOCTOFY_INVOICES_TITLE, DOCTOFY_INVOICES_TITLE, 'activate_plugins', DOCTOFY_INVOICES, array(&$this, 'bootstrap'), 'dashicons-media-spreadsheet', 18 );
        
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
        
        if ( !current_user_can('editor') && !current_user_can('administrator') ) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        $admin_table = new ExpeditionAdminInvoicesTable();
        $admin_table->prepare_items();
        
        ?>
        
        

        <div class="wrap">
            <?php add_thickbox(); ?>
            <div id="icon-users" class="icon32"><br/></div>
            <h2>
                <?=  __('Invoices list', 'expedition')  ?>
            </h2>
            
            <?php if ( strlen($expedition_reports_search_title) >0 ): ?>
            <p>
                <?= $expedition_reports_search_title ?>
            </p>
            <?php endif; ?>
            
            <hr>
            
            <form method="get" class="expedition_search_form">
                <input type="hidden" name="page" value="<?=DOCTOFY_INVOICES?>">
                <select name="search_for">
                    <option value="id" <?php selected( isset($_GET['search_for']) && $_GET['search_for'] == "id", true  ) ?>>ID</option>
                    <option value="patient_id" <?php selected( isset($_GET['search_for']) && $_GET['search_for'] == "payer_patient_id", true  ) ?>>Patient who paid</option>
                    <option value="nit" <?php selected( isset($_GET['search_for']) && $_GET['search_for'] == "consultation_id", true  ) ?>>Consultation</option>
                    <option value="ip" <?php selected( isset($_GET['search_for']) && $_GET['search_for'] == "gateway", true  ) ?>>Gateway</option>
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
                #col_payer_patient_id{width: 200px}
                
            </style>
        </div>
        <?php
    }
}


?>
