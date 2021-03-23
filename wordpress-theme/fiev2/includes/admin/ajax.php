<?php

add_action("wp_ajax_program_confirm", "program_confirm_hook"); // only authenticated users
function program_confirm_hook() {
    global $current_user;
    if ( !$current_user ) {
        exit("Incident reported");
    }
    $program_id = (int)$_REQUEST["program_id"];
    $booking_id = (int)$_REQUEST["booking_id"];
    $expeditioner_id = (int)$_REQUEST["expeditioner_id"];
    
    $user_id = (int)$current_user->data->ID;
    $program = get_post($program_id);
    $role = expedition_get_user_role();
    
    if ( !$program ){
        $response = ['code'=>'invalid_program', 'message'=> 'Invalid program', 'status'=>609 ];
        return wp_send_json($response);
    }
    // is a privated program
    if ( get_field('type', $program_id) != 'public' ){
        $response = ['code'=>'invalid_program', 'message'=> 'Invalid type of program', 'status'=>609 ];
        return wp_send_json($response);
    }

    $booking = Expedition_Helper::getUserBooking( $booking_id );

    // is previously booked same program
    if ( !$booking ){
        $response = ['code'=>'program_previously_no_reserved', 'message'=> "You didn't book this program", 'status'=>611 ];
        return wp_send_json($response);
    }

    if ( $booking->id != $booking_id ){
        $response = ['code'=>'program_previously_no_reserved', 'message'=> "You didn't book this program", 'status'=>611 ];
        return wp_send_json($response);
    }



    $owner_id = (int)get_field('owner', $program_id);
    // is a privated program
    if ( $owner_id != $user_id && $role != 'administrator' ){
        $response = ['code'=>'invalid_program', 'message'=> "You have no permissions to handle the checkin", 'status'=>649 ];
        return wp_send_json($response);
    }

    if ( $booking->status == BOOKING_CHECKED_IN ){
        $response = ['code'=>'invalid_program', 'message'=> "The booking has been checked in previously", 'status'=>651 ];
        return wp_send_json($response);
    }

    if ( $booking->status != BOOKING_CONFIRMED ){
        $response = ['code'=>'invalid_program', 'message'=> "The booking's status, is not valid to make checkin", 'status'=>651 ];
        return wp_send_json($response);
    }

    Expedition_Helper::modifyUserBooking(array('status'=> BOOKING_CHECKED_IN, 'id'=> $booking->id ));
    $one_signal_id = get_field( 'one_signal_id', 'user_'.$expeditioner_id);
    if ( $one_signal_id ){
        Notifications::sendPushToUser($one_signal_id, "Successful Check in!", "Booking", array('notification'=>'checked_in', 'program_id', $program_id ) );
    }

    wp_send_json(
            array(
                'success'=>true, 
                'booking'=> Expedition_Helper::getUserBooking( $booking->id  ),
                'message'=> 'Confirm added succesfully',
                )
            );
}
        
add_action("wp_ajax_change_membership_level", "change_membership_level_hook"); // only authenticated users

function change_membership_level_hook() {
    global $current_user;
    if ( !$current_user ) {
        exit("Incident reported");
    }
    
    /**************************************************************************/
    
    $user_name = $current_user->data->display_name;
    $user_email = $current_user->data->user_email;
    $message= "<p>The company $user_name is interested in change of membership level, please contact to $user_email</p>";
    $sent = Notifications::send(array('admin@expeditionguate.com', 'estuardoeg@gmail.com'), 'A company is interested in change membership level ', $message);
    
    wp_send_json(
            array(
                'success'=>true, 
                'result' => $sent,
                'html' => 'Notification sent to administrator!'
                )
            );
}

add_action("wp_ajax_export_transactions_report_of_month", "expedition_export_transactions_report_of_month_hook"); // only authenticated users
function expedition_export_transactions_report_of_month_hook() {
    
    if ( !wp_verify_nonce( $_REQUEST['nonce'], "export_transactions_report_of_month")) {
        exit("Incident reported");
    }
    
    $month = $_REQUEST["month"];
    // @link http://www.php.net/manual/en/class.datetime.php
    $d1 = new DateTime($month);
    $d2 = new DateTime();
    // @link http://www.php.net/manual/en/class.dateinterval.php
    $interval = $d2->diff($d1);
    $months_ago = $interval->format('-%m');
    
    // include_once './excel-report.php';
    get_template_part('includes/admin/excel-report');
    expedition_export_transactions_to_excel($months_ago);
    exit();
    wp_send_json( array( 'success' => true, 'html'=> null,  ) );
    die();   
}

add_action("wp_ajax_export_program_report", "expedition_export_program_report"); // only authenticated users
function expedition_export_program_report() {
    
    if ( !wp_verify_nonce( $_REQUEST['nonce'], "export_program_report")) {
        exit("Incident reported");
    }
    
    $program_id = (int)$_REQUEST["program_id"];
    
    get_template_part('includes/admin/excel-report');
    expedition_export_transactions_to_excel(0, $program_id);
    exit();
}

add_action("wp_ajax_confirm_single_booking", "confirm_single_booking_hook"); // only authenticated users

function confirm_single_booking_hook() {
    $booking_id = (int)$_REQUEST["booking_id"];
    
    if ( !$booking_id || !wp_verify_nonce( $_REQUEST['nonce'], "confirm_single_booking")) {
        exit("Incident reported");
    }
    
    /**************************************************************************/
    
    $response = Expedition_Helper::modifyUserBooking(array('status'=> BOOKING_CONFIRMED, 'id'=> $booking_id ));
    
    wp_send_json(
            array(
                'success'=>true, 
                'booking_id'=> $booking_id, 
                'text' => Expedition_Helper::getTourBookingStatusFromCode(@$response->status),
                'object' => $response
                )
            );
}


function cancel_transaction_epay($transaction, $booking) {
        
        $transaction_response = json_decode($transaction->ws_response);
        $transaction_sent = json_decode($transaction->ws_sent);
        
        $transaction_sent = $transaction_sent[0]->AuthorizationRequest;
        
        if(!$transaction_sent->shopperIP){
            $shopperIP = "67.205.167.98";
        }  else {
            $shopperIP = $transaction_sent->shopperIP;
        }
        //return  $transaction_response;
        // $booking = Expedition_Helper::getUserBookingByUserAndTour($tour_id, $user_id);
        
        // building params for request
        // epay variables
        $epay_url = "";
        
        // true -> pruebas | false -> produccion 
        $test_epay = true;

        if($test_epay){
            $epay_url = "https://epaytestvisanet.com.gt/paymentcommerce.asmx?WSDL";
            $afiliation_epay = "00575123";
            $param=array(
            	'posEntryMode' => "012"	                       //Metodo de entrada
            	,'paymentgwIP' => "190.149.69.135"	           //IP WebService Visanet
            	,'shopperIP' => $shopperIP   //IP Cliente que realiza la compra
            	,'merchantServerIP' => "67.205.167.98"	       //IP Comercio integrado a VisaNet
            	,'merchantUser' => "76B925EF7BEC821780B4B21479CE6482EA415896CF43006050B1DAD101669921" //Usuario
            	,'merchantPasswd' => "DD1791DB5B28DDE6FBC2B9951DFED4D97B82EFD622B411F1FC16B88B052232C7" //Password
            	,'terminalId' => "77788881"			                //Terminal
            	,'merchant' => $afiliation_epay			            //Afiliacion
            	,'messageType' => "0202"			                //Mensaje de anulacion
            	,'auditNumber' => $transaction_sent->auditNumber    //Correlativo ciclico de transaccion de 000001 hasta 999999 de la transaccion existente
            );
            
            
        } else {
            $epay_url = "https://epayvisanet.com.gt/paymentcommerce.asmx?WSDL";
            $afiliation_epay = "035250001";
            $param=array(
            	'posEntryMode' => "012"	                        //Metodo de entrada
            	,'paymentgwIP' => "190.111.1.198"	            //IP WebService Visanet
            	,'shopperIP' => $transaction_sent->shopperIP	//IP Cliente que realiza la compra
            	,'merchantServerIP' => "162.0.237.247"	        //IP Comercio integrado a VisaNet
            	,'merchantUser' => "44BC7ABEA713FDE5D02A755161168156260B9125A4852E6345C29A6799A29CCF" //Usuario
            	,'merchantPasswd' => "6D4448F5233D7FF5ED12503892803EF040AFA16FA47C356A8B51E1BCFAB60BB7" //Password
            	,'terminalId' => "99543999"			                //Terminal
            	,'merchant' => $afiliation_epay			            //Afiliacion
            	,'messageType' => "0202"			                //Mensaje de anulacion
            	,'auditNumber' => $transaction_sent->auditNumber    //Correlativo ciclico de transaccion de 000001 hasta 999999
            );
        }
        
        $params = array(array('AuthorizationRequest' => $param));
       
        ini_set("default_socket_timeout", 10);
        $client = new SoapClient($epay_url, array('connection_timeout' => 10));//Tiempo en segundos para disparar reversa automatica
         
         try 
        	{	
        		$result  = $client->__soapCall('AuthorizationRequest',$params);
        	    
        	
        		if($result->response->responseCode === '00'){

                    

                    $reference_num = $result->response->referenceNumber;
                    $auth_num = $result->response->authorizationNumber;
                   
                    //  making up response
                    $response = array(
                        'success'=>true,
        		        'code'=>'epay_success',
        		        'message'=> 'Anulacion éxitosa',
        		        'status'=>200,
        		        'epay_response_code'=>$result->response->responseCode,
        		        'audit_number'=>$result->response->auditNumber,
        		        'reference_number'=>$result->response->referenceNumber,
        		        'authorization_number'=>$result->response->authorizationNumber,
        		        'message_type'=>$result->response->messageType
        		    );
      		    
                    return $response;
                    
        		} else {
        		    
                    return $response;
        		}
        		
                
                
        		
        	}
        catch(SoapFault $e) 
        	{
                
                return $e;
        
        	}

}

// Cancel single booking Hook
add_action("wp_ajax_cancel_single_booking", "cancel_single_booking_hook"); // only authenticated users

function cancel_single_booking_hook() {
    $booking_id = (int)$_REQUEST["booking_id"];
    
    if ( !$booking_id || !wp_verify_nonce( $_REQUEST['nonce'], "cancel_single_booking")) {
        print_r("incident");
        exit("Incident reported");
        
    }
    
    /**************************************************************************/
    $response = Expedition_Helper::modifyUserBooking(array('status'=> BOOKING_CANCELLED, 'id'=> $booking_id ));
    
    $booking = Expedition_Helper::getUserBooking( $booking_id );
    $transactions = (array) Expedition_Helper::getTransactionsByBooking($booking_id);
    $transaction_sent = json_decode($transactions[0]->ws_sent);
    $transaction_response = json_decode($transactions[0]->ws_response);
    
    
    
    if($transaction_sent && $transaction_response ){
        
        $cancel_response = cancel_transaction_epay($transactions[0], $booking);
        
        wp_send_json(
            array(
                'success'=>true, 
                'booking_id'=> $booking_id, 
                'text' => Expedition_Helper::getTourBookingStatusFromCode(@$response->status),
                'object' => $response,
                'booking' => $booking,
                'transactions' => $transactions,
                'epay_response' => $cancel_response
                )
            );
    } else {
        wp_send_json(
            array(
                'success'=>true, 
                'booking_id'=> $booking_id, 
                'text' => Expedition_Helper::getTourBookingStatusFromCode(@$response->status),
                'object' => $response,
                'booking' => $booking,
                'transactions' => $transactions,
                'epay_response' => 'No transaction to cancel - not a a valid epay transaction'
                )
            );
    }
    
}



add_action("wp_ajax_get_financial_of_month", "expedition_get_financial_of_month_hook"); // only authenticated users
function expedition_get_financial_of_month_hook() {
    
    if ( !wp_verify_nonce( $_REQUEST['nonce'], "get_balance_month")) {
        exit("Incident reported");
    }
    
    $month = $_REQUEST["month"];
    // @link http://www.php.net/manual/en/class.datetime.php
    $d1 = new DateTime($month);
    $d2 = new DateTime();
    // @link http://www.php.net/manual/en/class.dateinterval.php
    $interval = $d2->diff($d1);
    $diff = $interval->format('-%m');
    
    $content = Expedition_Helper::getFinanciaOfMonth( $diff );
    
    wp_send_json( array( 'success' => true, 'html'=> $content,  ) );
    die();   
}