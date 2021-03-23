<?php 
/** Error reporting */
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);

function expedition_export_transactions_to_excel($months_ago, $program_id = 0){
    /** Include PHPExcel */
    get_template_part('includes/admin/PHPExcel');
    
    $letters = array("A", "B", "C", "D", "E", "F", "G", 'H', "I", "J", "K", "L", "M", "N",
        "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
        
        "AA", "AB", "AC", "AD", "AE", "AF", "AG", 'AH', "AI", "AJ", "AK", "AL", "AM", "AN",
        "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ",
        
        "BA", "BB", "BC", "BD", "BE", "BF", "BG", 'BH', "BI", "BJ", "BK", "BL", "BM", "BN",
        "BO", "BP", "BQ", "BR", "BS", "BT", "BU", "BV", "BW", "BX", "BY", "BZ",
        
        "CA", "CB", "CC", "CD", "CE", "CF", "CG", 'CH', "CI", "CJ", "CK", "CL", "CM", "CN",
        "CO", "CP", "CQ", "CR", "CS", "CT", "CU", "CV", "CW", "CX", "CY", "CZ",
        
        );
    
    $all_fields = array(
        'date'=>'Fecha Reserva',
        'tour_id'=>'ID Evento',
        'tour_name'=>'Nombre del Evento',
        'booking_id'=>'ID reserva',
        'transaction_id'=> 'ID transacción',
        'amount'=>'Total',
        'discount_code'=>'Código de descuento',
        'total_discount'=>'Total descuento',
        'total_charged'=>'Total pagado',
        'booking_status'=>'Estado de reserva',
        'receipt_num'=> 'NIT',
        'receipt_name'=>'Nombre Recibo',
        'user_id'=>'ID de cliente',
        'user_name'=>'Nombre Participante',
        'Correo_voluntario' => 'Correo',
        'phone' => 'No. Celular',
        'sex'=>'Sexo',
        'age'=>'Edad',
        'Nationality'=>'Nacionalidad',
        'Occupation'=>'Ocupación',
        'day' => 'Categoría',
        'booking_data'=>'Tipo de boleto',
        'ex_field_01'=>'Pregunta extra 1',
        
        
    );
    $all_fields2 = array(
        'date'=>'Fecha Reserva',
        'tour_id'=>'ID Evento',
        'tour_name'=>'Nombre del Evento',
        'booking_id'=>'ID reserva',
        'gateway'=>'Gateway',
        'transaction_id'=> 'ID transacción',
        'amount'=>'Total',
        'discount_code'=>'Código de descuento',
        'total_discount'=>'Total descuento',
        'total_charged'=>'Total pagado',
        'booking_status'=>'Estado de reserva',
        'receipt_num'=> 'NIT',
        'receipt_name'=>'Nombre Recibo',
        'user_id'=>'ID de cliente',
        'user_name'=>'Nombre Participante',
        'Correo_voluntario' => 'Correo',
        'phone' => 'No. Celular',
        'sex'=>'Sexo',
        'age'=>'Edad',
        'Nationality'=>'Nacionalidad',
        'Occupation'=>'Ocupación',
        'day' => 'Categoría',
        'booking_data'=>'Tipo de boleto',
        'ex_field_01'=>'Pregunta extra 1',
        
    );
    
    if (current_user_can('administrator') || current_user_can('editor')){
        $fields = $all_fields;
    }else if (current_user_can('business')){
        $fields = $all_fields2;
        
        // $fields = array();
        // $business_fields = get_field('report_fields', 'options');
        // foreach ($business_fields as $business_field) {
        //     $fields[$business_field['value']] = $all_fields[$business_field['value']];
        // }
    }
    
    $column_titles = array();
    foreach ($fields as $key => $title) {
        $column_titles[] = $title;
    }
    // $column_titles = array('ID', 'Started', 'Finished', 'Doctor', 'Patient', 'Transaction', 'Charge amount', 'Doctor Fee', 'Expedition Fee', 'Paid to doctor?', 'Last update' );
    
    $max_letter_for_page = $letters[ count($column_titles)-1 ];
    $letter_for_logo = "D";
    
    
    define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
    date_default_timezone_set('America/Guatemala');
    
    
    /****************************************/
    /************* DOC META DATA ************/
    /****************************************/
    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    
    // Set document properties
    $objPHPExcel->getProperties()->setCreator("AvantLife")
                                ->setLastModifiedBy("AvantLife")
                                ->setTitle("AvantLife - Evento")
                                ->setSubject("AvantLife - Evento")
                                ->setDescription("Reporte generado automaticamente")
                                ->setKeywords("AvantLife - Evento")
                                ->setCategory("AvantLife - Evento");
    
    $objPHPExcel->setActiveSheetIndex( 0 );
    $objPHPExcel->getActiveSheet()->setTitle( __("Transactions", 'expedition') );
    /****************************************/
    /************* DOC META DATA ************/
    /****************************************/
    
    
    
    
    /****************************************/
    /****************** MISC ****************/
    /****************************************/
    $current_month = $months_ago;
    $first_day_prev_month = date("Y-m-d", strtotime("first day of $current_month month"));
    $last_day_prev_month = date("Y-m-d", strtotime("last day of $current_month month"));
    $first_day_prev_month_date = "$first_day_prev_month:00:00:00";
    $last_day_prev_month_date = "$last_day_prev_month:23:59:59";

    global $current_user;
    if ( $program_id ){
        $transactions = Expedition_Helper::getUserBookingByTour($program_id);
    }else{
        if (current_user_can('administrator') || current_user_can('editor')){
            // $transactions = Expedition_Helper::getTransactionsBetweenForAdmin($first_day_prev_month_date, $last_day_prev_month_date );
            $transactions = Expedition_Helper::getBookingsBetweenForAdmin($first_day_prev_month_date, $last_day_prev_month_date);
        }else{
            // $transactions = Expedition_Helper::getTransactionsBetween($first_day_prev_month_date, $last_day_prev_month_date, $current_user->ID);
            $transactions = Expedition_Helper::getBookingsBetween($first_day_prev_month_date, $last_day_prev_month_date, $current_user->ID);
        }
    }
    /****************************************/
    /****************** MISC ****************/
    /****************************************/
    
    
    
    
    
    /****************************************/
    /*************** PAGE HEADER ************/
    /****************************************/
    
    // set headers height
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(75);
    $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(35);
    $objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(35);
    $objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(30);
    
    // black bar for the logo
    $objPHPExcel->getActiveSheet()->mergeCells('A1:'.$max_letter_for_page.'1');
    $objPHPExcel->getActiveSheet()->getStyle('A1:'.$max_letter_for_page.'1')->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '1c2f4b')
            ),
            'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => 'FFFFFF'),
                    'size'  => 44,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            )
        )
    );
    
    $objPHPExcel->getActiveSheet()->setCellValue("A1", 'Reporte Evento - AvantLife');
    
    // Period range
    $objPHPExcel->getActiveSheet()->mergeCells('A2:'.$max_letter_for_page.'2');
    $objPHPExcel->getActiveSheet()->mergeCells('A3:'.$max_letter_for_page.'3');
    $objPHPExcel->getActiveSheet()->getStyle('A2:'.$max_letter_for_page.'3')->applyFromArray(
        array(
            /*'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),*/
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '1c2f4b')
            ),
            'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => 'FFFFFF'),
                    'size'  => 24,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'wrapText' => true
        )
    );
    
    $objPHPExcel->getActiveSheet()->getStyle('A3:'.$max_letter_for_page.'3')->applyFromArray(
        array(
            'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => 'FFFFFF'),
                    'size'  => 16,
            ),
        )
    );
    
    $objPHPExcel->getActiveSheet()->getStyle('A4:'.$max_letter_for_page.''.(4) )->applyFromArray(
        array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '333333')
            ),
            'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => 'FFFFFF'),
                    'size'  => 14,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'wrapText' => true
        )
    );
    
    $letter = '';
    $index = 0;
    while ($letter != $max_letter_for_page) {
        $letter = $letters[$index];
        $objPHPExcel->getActiveSheet()->getColumnDimension($letter)->setWidth(30);
        $index++;
    }
        
    foreach ($column_titles as $index => $title) {
        $objPHPExcel->getActiveSheet()->setCellValue($letters[$index]."4", $title );
    }
    
    $period_title = $program_id? get_the_title($program_id) : "Period : ".date("F", strtotime("first day of $current_month month"));
    $objPHPExcel->getActiveSheet()->setCellValue("A2", $period_title);
    $period_subtitle = $program_id ? '' : date("d/m/Y", strtotime("first day of $current_month month"))
    . " - " . date("d/m/Y", strtotime("last day of $current_month month"));
    $objPHPExcel->getActiveSheet()->setCellValue("A3", $period_subtitle );
    
    /****************************************/
    /*************** PAGE HEADER ************/
    /****************************************/
    
    
    
    /****************************************/
    /***************** CONTENT **************/
    /****************************************/
    
    
    
    $objPHPExcel->getActiveSheet()->getStyle('A5:'.$max_letter_for_page.''.(count($transactions)+4) )->applyFromArray(
        array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => 'CCCCCC')
                )
            ),
            /*'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'eceef4')
            ),*/
            'font' => array(
                    //'bold' => true,
                    //'color' => array('rgb' => 'FFFFFF'),
                    'size'  => 11,
            ),
            'alignment' => array(
                //'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'wrapText' => true
        )
    );
    
    $row = 0;
    foreach ($transactions as $key => $transaction) {
        
        // error_log(print_r("booking ".$transaction, true));
        $row = $key+5;
        $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(30);
        
        $letter_key = 0;
        
        foreach ($fields as $field_key => $title) {
            
            $val = '';
            switch ($field_key) {
                case 'transaction_id':
                    //$objPHPExcel->getActiveSheet()->setCellValue($letters[$letter_key]."$row", $transaction->id );
                    $val = $transaction->id;
                    break;
                case 'date':
                    $val = Expedition_Helper::dateFromDB($transaction->created_at, "d/m/Y H:i:s");
                    break;
                case 'gateway':
                    $val = $transaction->seats;
                    break;
                case 'booking_id':
                    $val = $transaction->id;
                    break;
                case 'user_id':
                    $val = (int)$transaction->user_id;
                    break;
                case 'user_name':
                    $val = get_user_meta( (int)$transaction->user_id, 'first_name', true ) . ' ' .get_user_meta( (int)$transaction->user_id, 'last_name', true );
                    break;
                case 'amount':
                    //$val = $transaction->currency.$transaction->amount;
                    $val = $transaction->amount;
                    $objPHPExcel->getActiveSheet()->getStyle($letters[$letter_key]."$row")->getNumberFormat()->setFormatCode('_("GTQ"* #,##0.00_);_("GTQ"* \(#,##0.00\);_("GTQ"* "-"??_);_(@_)');
                    break;
                case 'discount_code':
                    $val = $transaction->discount_code;
                    break;  
                case 'total_discount':
                    $val = $transaction->total_discount;
                    break;
                case 'total_charged':
                    $val = $transaction->total_charged;
                    break;      
                // case 'deposit_receipt':
                //     $meta = json_decode($transaction->meta);
                //     $val = '';
                //     if ( isset($meta->receipt) ){
                //         $val = $meta->receipt;
                //     }
                //     break;
                case 'booking_status':
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    $val = Expedition_Helper::getTourBookingStatusFromCode($booking->status);
                    break;
                
                case 'receipt_num':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->receipt_num;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'receipt_name':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->receipt_name;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'direccion_nit':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->direccion_nit;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                case 'tour_id':
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    $val = $booking->tour_id;
                    break;
                case 'tour_name':
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    $val = get_the_title($booking->tour_id);
                    break;
                case 'tour_operator':
                    $val = get_user_meta( (int)$transaction->owner_id, 'first_name', true ) . ' ' .get_user_meta( (int)$transaction->owner_id, 'last_name', true );
                    break;
                    
                case 'day':
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    $tour_meta = json_decode($booking->tour_meta);
                    $val = "";
                    if ( $tour_meta && is_array($tour_meta) && count($tour_meta)>0 ){
                        //$val = $booking->tour_meta;
                        // $total = 0;
                        foreach ($tour_meta as $key => $meta) {
                            // $total+=$meta->total;
                            $meta->title;
                            $meta->price;
                            $meta->qty;
                            $meta->total;
                            // $val .= ($key > 0 ? " | " : "") . "$meta->title * $meta->qty = ". number_format($meta->total, 2);
                            $val = $meta->category_name;
                        }
                        // number_format($total,2)
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    
                    break;
                case 'booking_data':
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    $tour_meta = json_decode($booking->tour_meta);
                    $val = "";
                    if ( $tour_meta && is_array($tour_meta) && count($tour_meta)>0 ){
                        //$val = $booking->tour_meta;
                        // $total = 0;
                        foreach ($tour_meta as $key => $meta) {
                            // $total+=$meta->total;
                            $meta->title;
                            $meta->price;
                            $meta->qty;
                            $meta->total;
                            // $val .= ($key > 0 ? " | " : "") . "$meta->title * $meta->qty = ". number_format($meta->total, 2);
                            $val = $meta->title;
                        }
                        // number_format($total,2)
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    
                    break;
                    
                    case 'phone':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->phone;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'sex':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->sex;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'age':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->age;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'Nationality':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->nationality;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'Occupation':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->occupation;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'ex_field_01':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->ex_field_01;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'emergency_contact':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->emergency_contact;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'no_emer_contact':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->emergency_contact_number;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'referido':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->reference_name;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'Contact_ref':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->reference_phone;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'allergy_agg':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->allergy_agg;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'food_allergy_agg':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->food_allergy_agg;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'lesion_agg':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->lesion_agg;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'diet_agg':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->diet_agg;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'blood_type':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->blood_type;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'park_sel':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->park_sel;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'transport1':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->transport1;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'transport2':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->transport2;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'ex_negocios':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->ex_negocios;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'ex_fut':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->ex_fut;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'ex_region':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->ex_region;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'ex_talentos':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->ex_talentos;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'address':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->booking_id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val = $meta->address;
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    break;
                    
                    case 'Correo_voluntario':
                    $user_data = get_userdata($transaction->user_id);
                    $val = $user_data->user_email;
                    break;
                    
                    case 'Custom_field_0':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val2 = $meta->expeditioner_meta;
                            
                            $meta2 = json_decode($val2);
                            $val = '';
                            if ( isset($meta2->custom_input_0_value) ){
                             $val = $meta2->custom_input_0_value;
                         }
                            
                            
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                     case 'Custom_field_1':
                    // ---
                    $booking = Expedition_Helper::getUserBooking($transaction->id);
                    
                    // getting current tour id.
                    $curr_tour_id = $booking->tour_id;
                    
                    // getting current user id
                    $curr_user_id = $booking->user_id;
                    
                    $data = (array)Expedition_Helper::getTourExpeditioners($curr_tour_id, $curr_user_id);
                    //$tour_meta = json_decode($booking->tour_meta);
            
                    $val = "";
                    
                    if ( $data && is_array($data) && count($data)>0 ){
                        error_log(print_r("in data: ", true));
                        
                        foreach ($data as $key => $meta) {
                            $val2 = $meta->expeditioner_meta;
                            
                            $meta2 = json_decode($val2);
                            $val = '';
                            if ( isset($meta2->custom_input_1_value) ){
                             $val = $meta2->custom_input_1_value;
                         }
                            
                            
                        }
                        
                    }else{
                        $val = __('No data found!', 'expedition');
                    }
                    
                     
                

                default:
                    break;
            }
            $objPHPExcel->getActiveSheet()->setCellValue($letters[$letter_key]."$row", $val );
            $letter_key++;
        }
          
    }
    $row++;    
    
    $objPHPExcel->getActiveSheet()->getStyle("A$row:".$max_letter_for_page.''.$row )->applyFromArray(
        array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '333333')
            ),
            'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => 'FFFFFF'),
                    'size'  => 14,
            ),
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'wrapText' => true
        )
    );
    
    /****************************************/
    /***************** CONTENT **************/
    /****************************************/
    
    
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

    // Set print headers
    $objPHPExcel->getActiveSheet()
        ->getHeaderFooter()->setOddHeader('&C&24&K0000FF&B&U&A');
    $objPHPExcel->getActiveSheet()
        ->getHeaderFooter()->setEvenHeader('&C&24&K0000FF&B&U&A');

    // Set print footers
    $objPHPExcel->getActiveSheet()
        ->getHeaderFooter()->setOddFooter('&R&D &T&C&F&LPage &P / &N');
    $objPHPExcel->getActiveSheet()
        ->getHeaderFooter()->setEvenFooter('&L&D &T&C&F&RPage &P / &N');


    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // $objWriter->save( get_template_directory(). '/includes/export.xlsx' );
    
    // var_dump_pre( $objWriter );
    // exit();
    
    
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename=Avantlife_Evento '.date('Y:m:d H:i:s').'.xlsx'); 
    
    $objWriter->save('php://output');
    
}