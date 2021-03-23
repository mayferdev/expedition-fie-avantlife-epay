<?php

trait PaymentsUserInfoFailed{
    
    /**
     * Returns list of records when user tried to add a credit card, but it failed
     * in cybersource
     * 
     * @param integer $doctor_id
     * @param integer $patient_id
     * @return array
     */
    public static function getPaymentsUserInfoFailed($patient_id) {
        global $wpdb;
        $payments_user_info_failed_table = $wpdb->prefix . 'payments_user_info_failed';
        
        $return = $wpdb->get_results($wpdb->prepare("
                    SELECT * FROM $payments_user_info_failed_table
                    WHERE patient_id = %d;
            ", $patient_id ));
        
        return $return;
    }
    
    /**
     * Get Payment user info of passed specific record $id
     * 
     * @param integer $patient_id annotation id to get
     * @return object
     * 
     */
    public static function getPaymentUserInfoFailed($id) {
        global $wpdb;
        $payments_user_info_failed_table = $wpdb->prefix . 'payments_user_info_failed';
        return $wpdb->get_row($wpdb->prepare("
			SELECT * FROM $payments_user_info_failed_table
			WHERE id = %d;
		", $id ));
    }
    
    /**
     * Inserts a record into payments_user_info_failed table
     * 
     * @global type $wpdb
     * @param type $data
     * @return type
     */
    public static function insertPaymentUserInfoFailedRecord($data) {
        global $wpdb;
        $payments_user_info_failed_table = $wpdb->prefix . 'payments_user_info_failed';
        
        return $wpdb->insert($payments_user_info_failed_table, [
                    //'id' => '', // auto
                    'patient_id' => $data['patient_id'],
                    'ws_response' => $data['ws_response'],
                    'nit' => $data['nit'],
                    'ip' => $data['ip'],
                    'fields' => $data['fields'],
                    'created_at' => current_time('mysql', 1)
                        ], ['%d', '%s', '%s', '%s', '%s', '%s']);
    }
    
}
