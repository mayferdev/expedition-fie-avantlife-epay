<?php

trait Transactions{
    
    /**
     * Get transactions between passed $first_day_prev_month_date and $last_day_prev_month_date
     * 
     * @global type $wpdb
     * @param type $first_day_prev_month_date
     * @param type $last_day_prev_month_date
     * @return type
     */
    public static function getTransactionsBetween($first_day_prev_month_date, $last_day_prev_month_date, $owner_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM ".TRANSACTIONS_TABLE."
			WHERE created_at > %s AND created_at < %s AND owner_id = %d;
		", $first_day_prev_month_date, $last_day_prev_month_date, $owner_id ));
    }
    
    /**
     * Get transactions between passed $first_day_prev_month_date and $last_day_prev_month_date
     * 
     * @global type $wpdb
     * @param type $first_day_prev_month_date
     * @param type $last_day_prev_month_date
     * @return type
     */
    public static function getTransactionsBetweenForAdmin($first_day_prev_month_date, $last_day_prev_month_date) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM ".TRANSACTIONS_TABLE."
			WHERE created_at > %s AND created_at < %s;
		", $first_day_prev_month_date, $last_day_prev_month_date ));
    }
    
    /**
     * Get transactions of passed patient_id 
     * 
     * @param integer $patient_id
     * @return array
     * 
     */
    public static function getTransactions($patient_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM ".TRANSACTIONS_TABLE."
			WHERE user_id = %d;
		", $patient_id ));
    }
    
    /**
     * Get transactions of passed booking_id 
     * 
     * @param integer $booking_id
     * @return array
     * 
     */
    public static function getTransactionsByBooking($booking_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM ".TRANSACTIONS_TABLE."
			WHERE booking_id = %d;
		", $booking_id ));
    }
    
    /**
     * Get specific transaction $id
     * 
     * @param integer $id transaction id to get
     * @return object
     * 
     */
    public static function getTransaction($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("
			SELECT * FROM ".TRANSACTIONS_TABLE."
			WHERE id = %d;
		", $id ));
    }
    
    /**
     * Inserts a record into transactions table
     * 
     * @global type $wpdb
     * @param type $data
     * @return type
     */
    public static function insertTransactionRecord($data) {
        global $wpdb;
        $insert_response = $wpdb->insert(TRANSACTIONS_TABLE, [
            //'id' => '', // auto
            'booking_id' => $data['booking_id'],
            'user_id' => $data['user_id'],
            'owner_id' => $data['owner_id'],
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'gateway' => $data['gateway'],
            'meta' => $data['meta'],
            
            'ws_sent' => $data['ws_sent'],
            'ws_response' => $data['ws_response'],
            'booking' => is_object($data['booking']) || is_array($data['booking']) ? json_encode($data['booking']) : '',
            'success' => $data['success'],
            
            'created_at' => current_time('mysql', 1),
            'updated_at' => current_time('mysql', 1)
                ], ['%d', '%d', '%d', '%f', '%s', '%s', '%s',    
                    '%s', '%s', '%s', '%d',    
                    '%s', '%s']);
        
        if ( $insert_response ){
            $transaction_id = $wpdb->insert_id;
            return self::getTransaction($transaction_id);
        }
        
        return $insert_response;
        
    }
    
    /**
     * Modify a record into transactions table
     * 
     * @global type $wpdb
     * @param type $data
     * @return type
     */
    public static function modifyTransactionRecord($data, $transaction) {
        global $wpdb;
        $modified_response = $wpdb->update(
                TRANSACTIONS_TABLE, 
                [
                    //'id' => '', // auto
                    'consultation_id' => isset($data['consultation_id']) ? $data['consultation_id'] : $transaction->consultation_id,
                    'payer_patient_id' => isset($data['payer_patient_id']) ? $data['payer_patient_id'] : $transaction->payer_patient_id,
                    'amount' => isset($data['amount']) ? $data['amount'] : $transaction->amount,
                    'gateway' => isset($data['gateway']) ? $data['gateway'] : $transaction->gateway,
                    'currency' => isset($data['currency']) ? $data['currency'] : $transaction->currency,
                    
                    'ws_response' => isset($data['ws_response']) ? $data['ws_response'] : $transaction->ws_response,
                    'success' => (isset($data['success']) ? $data['success'] : $transaction->success) ? 1 : 0,
                    
                    'updated_at' => current_time('mysql', 1)
                        ], 
                
                array('id' => (int)(isset($data['id']) ? $data['id'] : $transaction->id) ),
                
                ['%d', '%d', '%f', '%s', '%s', '%s', '%d', '%s']);
        
        if ( $modified_response ){
            $transaction_id = (int)(isset($data['id']) ? $data['id'] : $transaction->id);
            return self::getTransaction($transaction_id);
        }
        
        return $modified_response;
    }
    
}
