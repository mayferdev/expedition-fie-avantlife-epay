<?php

// register ajax action
if ( current_user_can('editor') || current_user_can('administrator') ){
    add_action("wp_ajax_download_report", "bam_download_report", 10, 0);
    add_action("wp_ajax_nopriv_download_report", "bam_download_report", 10, 0);
}

$nonce = wp_create_nonce( '_validate_ibeacons_custom_download' );
            $download_url = add_query_arg(
                    array_merge(array(
                        'action'            =>  'download_report',
                        'bam_admin_action'  =>  $nonce
                    ), (array)$_GET)
                    ,
                    admin_url('admin-ajax.php')
                    );

/**
 * Download a Excel file with the statistics of the ibeacons
 * 
 */
function bam_download_report(){
    
    //if ( isset($_REQUEST['bam_admin_action']) && wp_verify_nonce( $_REQUEST['bam_admin_action'], '_validate_ibeacons_custom_download') ) {
        include( TEMPLATEPATH . '/includes/excel-export-report.php');
        
        export_to_excel();
        exit();
    //}
    
}

            
            



if(!class_exists('WP_List_Table')){
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if ( !defined('BAM_REPORTS_SLUG') ){
    define( "BAM_REPORTS_SLUG", "BAMREPORTS");
}

if ( !defined('BAM_REPORTS_NAME') ){
    define( "BAM_REPORTS_NAME", "Reportería de activaciones");
}

global $bam_reports_search_title;

/**
 * Display the information in @see BAM_DB
 * 
 */
class BAM_Admin_Reports_table extends WP_List_Table {
    
    public function search_box($text, $input_id) {
        parent::search_box($text, $input_id);
    }
    
    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct() {
        parent::__construct(array(
            'singular' => BAM_REPORTS_SLUG.'_at', //Singular label
            'plural' => BAM_REPORTS_SLUG.'_at', //plural label, also this well be one of the table css class
            'ajax' => false //We won't support Ajax for this table
        ));
    }

    /**
     * Define the columns that are going to be used in the table
     * @return array $columns, the array of columns to use with the table
     */
    function get_columns() {
        return $columns = array(
            'col_id' => __('Promo ID', BAM_REPORTS_SLUG),
            'col_date' => __('Fecha (d/m/Y H:m:s)', BAM_REPORTS_SLUG),
            'col_beacon_sn' => __('Beacon ID : S/N', BAM_REPORTS_SLUG),
            'col_response' => __('Object ID', BAM_REPORTS_SLUG)
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
            'col_date' => array('time'),
            'col_beacon_sn' => array('ibeacon_id'),
            'col_response' => array('object_id'),
        );
    }

    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    function prepare_items() {
        global $wpdb, $_wp_column_headers, $bam_reports_search_title;
        $screen = get_current_screen();
        $table = $wpdb->prefix . 'promo_displays';

        // Preparing your query
        $query = "SELECT * FROM $table";
        
        if ( isset($_GET['search_for']) && isset($_GET['search']) ):
            $query .= " WHERE ". $_GET['search_for'] . " = '" . $_GET['search']."'";
            $bam_reports_search_title = "Donde ".$_GET['search_for'] . " = " . $_GET['search'];
        else:
            $conditions = array();
            $conditions_ids = array();
            
            if ( isset($_GET['object_id']) && strlen($_GET['object_id']) > 9 ){
                $conditions["ObjectID"] = " object_id = '" . $_GET['object_id']."'";
                $conditions_ids["ObjectID"] = $_GET['object_id'];
            }
            
            if ( isset($_GET['promo_id']) && (int)$_GET['promo_id'] > 0 ){
                $conditions["Promo"] = " promo_id = '" . $_GET['promo_id']."'";
                $conditions_ids["Promo"] = $_GET['promo_id'];
            }
            
            if ( isset($_GET['ibeacon_id']) && (int)$_GET['ibeacon_id'] > 0 ){
                $conditions["iBeacon"] = " ibeacon_id = '" . $_GET['ibeacon_id']."'";
                $conditions_ids["iBeacon"] = $_GET['ibeacon_id'];
            }
            
            $conditions_str = "";
            if ( count($conditions)>0 ){
                $bam_reports_search_title = "Donde ";
                foreach ($conditions as $key => $condition) {
                    if ( $key == "Promo" || $key == "iBeacon" ){
                        $search_param_tite = get_the_title($conditions_ids[$key]);
                        $bam_reports_search_title = 
                                ( strlen($bam_reports_search_title)>10 ? $bam_reports_search_title. " y <br>" : $bam_reports_search_title. "") .
                                "<b>$key</b> = $search_param_tite ID:".$conditions_ids[$key];
                    }else{
                        $bam_reports_search_title = 
                                ( strlen($bam_reports_search_title)>10 ? $bam_reports_search_title. " y <br> " : $bam_reports_search_title."") .
                                "<b>$key</b> = ".$conditions_ids[$key];
                        
                    }
                }
                
                $conditions_str = " WHERE".implode( ' AND', $conditions);
            }
            
            $query .= $conditions_str;
            
        endif;
        
        if (  isset($data_to_request['notify_object_id']) && $data_to_request['notify_object_id'] == "YES"  ){
            $object_id = $data_to_request['object_id_to_send'];
            mail("estuardo@nadd.co", "Requested Object ID", "The Object ID sent is $object_id");
            unset( $data_to_request["notify_object_id"] );
            unset( $data_to_request["object_id_to_send"] );
        }

        // Ordering parameters 
        $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'time';
        $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'DESC';
        if (!empty($orderby) & !empty($order)) {
            $query.=' ORDER BY ' . $orderby . ' ' . $order;
        }else{
            $query .= " ORDER BY time DESC";
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

                //Open the line
                echo '<tr id="bam_error_list_' . $rec->id . '" >';
                foreach ($columns as $column_name => $column_display_name) {

                    //Style attributes for each col
                    
                    $class = "class='$column_name column-$column_name'";
                    $style = "";
                    if (in_array($column_name, $hidden))
                        $style = ' style="display:none;"';
                    $attributes = $class . $style;

                    //edit link
                    $editlink = '/wp-admin/link.php?action=edit&link_id=' . (int) $rec->promo_id;

                    
                    
                    
                    
                    
                    //Display the cell
                    switch ($column_name) {
                        
                        
                        case "col_id": echo '<td ' . $attributes . '>' . 
                                //'<a href="'.admin_url("/post.php?post={$rec->promo_id}&action=edit").'" target="_blank">Promo #'.
                                '<a href="'.add_query_arg(
                                            array(
                                                'promo_id' => $rec->promo_id, 
                                                'search_for'=> null, 
                                                'search'=>null)
                                            ).'">ID:'.
                                    $rec->promo_id .
                                ' <small>'. get_the_title($rec->promo_id) .'</small></a>'.
                                '</td>';
                            break;
                        case "col_date": echo '<td ' . $attributes . '>' . 
                                date('d/m/Y H:i:s', strtotime($rec->time) -(HOUR_IN_SECONDS*6) )
                                . '</td>';
                            break;
                        case "col_beacon_sn": echo '<td ' . $attributes . '>' .
                                //'<a href="'.admin_url("/post.php?post={$rec->ibeacon_id}&action=edit").'" target="_blank">ID #'.
                                '<a href="'.add_query_arg(
                                            array(
                                                'ibeacon_id' => $rec->ibeacon_id, 
                                                'search_for'=> null, 
                                                'search'=>null)
                                            ).'">ID:'.
                                    $rec->ibeacon_id .
                                ' <small>'. get_the_title($rec->ibeacon_id) .'</small></a>'.
                                '</td>';
                            break;
                        case "col_response": 
                            
                            
                            echo '<td ' . $attributes . '>'. 
                                    '<a href="'.add_query_arg(
                                            array(
                                                'object_id' => $rec->object_id, 
                                                'search_for'=> null, 
                                                'search'=>null)
                                            ).'">'.
                                        $rec->object_id .
                                    '</a>'.
                                '</td>';
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
    new BAM_Admin_Reports_table_Settings();
}


class BAM_Admin_Reports_table_Settings {

    public function __construct() {
        add_action('admin_menu', array(&$this, 'panel_pages'));
    }

    /**
     * We add a button below the settings section on the administrative backend
     */
    function panel_pages() {
        add_menu_page( BAM_REPORTS_NAME, BAM_REPORTS_NAME, 'publish_posts', BAM_REPORTS_SLUG. '-logs', array(&$this, 'bootstrap'), 'dashicons-tag', 18 );
        
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
        global $bam_reports_search_title;
        
        // current_user_can('editor') || current_user_can('administrator')
        
        if ( !current_user_can('editor') && !current_user_can('administrator') ) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        $admin_table = new BAM_Admin_Reports_table();
        $admin_table->prepare_items();
        
        ?>
        <div class="wrap">

            <?php
            $nonce = wp_create_nonce( '_validate_ibeacons_custom_download' );
            $download_url = add_query_arg(
                    array_merge(array(
                        'action'            =>  'download_report',
                        'bam_admin_action'  =>  $nonce
                    ), (array)$_GET)
                    ,
                    admin_url('admin-ajax.php')
                    );
            ?>
            <div id="icon-users" class="icon32"><br/></div>
            <h2>
                <?=  __('Reportería de envíos de push notifications', BAM_REPORTS_SLUG)  ?>
                <a class="button button-primary button-large" 
               target="_blank" id="download_promo" 
               title="Descargarás un archivo en formato .xlsx con los filtros actuales"
               href="<?=$download_url?>" >Descargar</a>
            </h2>
            
            <?php if ( strlen($bam_reports_search_title) >0 ): ?>
            <p>
                <?= $bam_reports_search_title ?>
            </p>
            <?php endif; ?>
            
            <hr>
            
            <form method="get" class="bam_search_form">
                <input type="hidden" name="page" value="<?=BAM_REPORTS_SLUG .'-logs'?>">
                <select name="search_for">
                    <option value="object_id" <?php selected( isset($_GET['search_for']) && $_GET['search_for'] == "object_id", true  ) ?>>ObjectID</option>
                    <option value="promo_id" <?php selected( isset($_GET['search_for']) && $_GET['search_for'] == "promo_id", true  ) ?>>Promo ID</option>
                    <option value="ibeacon_id" <?php selected( isset($_GET['search_for']) && $_GET['search_for'] == "ibeacon_id", true  ) ?>>Beacon ID</option>
                </select>

                <input class="bam_search_input" type="text" name="search" value="<?= isset( $_GET['search'] ) ? $_GET['search'] : '' ?>">

                <input type="submit" id="search-submit" class="button" value="Buscar">
            </form>
            
            <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
            <form id="log-filter" method="get">
                <!-- For plugins, we also need to ensure that the form posts back to our current page -->
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                <!-- Now we can render the completed list table -->
                <?php $admin_table->display(); ?>
            </form>
            
            <div class="detail_hover_bg"></div>
            <div class="detail_hover">
                
                <div class="cont">
                    <div class="close">X</div>
                    <div class='content'></div>
                </div>
            </div>
            
            <style type="text/css">
                .bam_search_form{margin-bottom: -38px;}
                .bam_search_input{width : 150px;}
                .bam_display_mobile{display: none;}
                @media  ( max-width: 782px ){
                    .bam_search_form{
                        margin-bottom: -15px; 
                        margin-left: auto; 
                        margin-right:auto;
                        width: 350px;
                    }
                }
                @media  ( max-width: 540px ){
                    .bam_search_form{width: 290px;}
                    .bam_search_input{width : 90px;}
                    .bam_display_mobile{display: block;}
                }
                
                .detail_hover_bg{
                    background: rgba(0,0,0,0.6);
                    position: fixed;
                    top :0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    z-index: 100;
                    display: none;
                }
                .detail_hover{
                    width: 80%;
                    position: absolute;
                    left: 10%;
                    top : 50px;
                    z-index: 101;
                    display: none;
                }
                .detail_hover .cont{
                    padding: 30px;
                    background: white;
                }
                .detail_hover pre{
                    word-wrap: break-word;
                }
                .detail_hover .close{
                    position: absolute;
                    right: -20px;
                    top : -20px;
                    width: 40px;
                    height: 40px;
                    line-height: 40px;
                    text-align: center;
                    background: black;
                    color: white;
                    border-radius: 50%;
                    cursor: pointer;
                }
                .detail_hover .close:hover{
                    background: #333;
                }
                .column-col_response .no_show{
                    display: block;
                }
                .column-col_response .show{
                    display: none;
                }
                .column-col_response .pointer{
                    cursor: pointer;
                }
            </style>
            <script type="text/javascript">
                
                jQuery(window).load(function(){
                    
                    function show_detail_content( html ){
                        jQuery('.detail_hover .content').html( html );
                        jQuery('.detail_hover_bg, .detail_hover').fadeIn();
                    }
                    
                    function hide_detail_content(){
                        jQuery('.detail_hover_bg, .detail_hover').fadeOut();
                    }
                    
                    jQuery('.detail_hover .close').click(function(e){
                        hide_detail_content();
                    });
                    
                    
                    jQuery('.column-col_response .no_show').click(function(e){
                        e.preventDefault();
                        //jQuery(this).parent().find('.show').slideDown();
                        //jQuery(this).slideUp();
                        
                        show_detail_content( jQuery(this).parent().find('.show.cont').html() );
                        
                        return false;
                    });
                    
                    jQuery('.column-col_response .show').click(function(e){
                        e.preventDefault();
                        //jQuery(this).parent().find('.no_show').slideDown();
                        //jQuery(this).parent().find('.show').slideUp();
                        
                        hide_detail_content();
                        
                        return false;
                    });
                    
                });
                
            </script>
        </div>
        <?php
    }
}


?>
