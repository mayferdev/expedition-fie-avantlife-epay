<?php

trait PaymentsUserInfo{
    
    /**
     * Get Payment user info of passed $patient_id
     * 
     * @param integer $patient_id annotation id to get
     * @return array
     * 
     */
    public static function getPaymentUserInfo($patient_id) {
        global $wpdb;
        
        $payments_user_info_table = $wpdb->prefix . 'payments_user_info';
        
        $toreturn = $wpdb->get_row($wpdb->prepare("
			SELECT * FROM $payments_user_info_table
			WHERE patient_id = %d;
		", $patient_id ));
        
        if ( $toreturn ){
            $toreturn->nit_full_name = (string)$toreturn->nit_full_name;
            $toreturn->nit_address = (string)$toreturn->nit_address;
        }
        
        return $toreturn;
    }
    
    /**
     * Inserts a record into payments_user_info table
     * 
     * @global type $wpdb
     * @param type $data
     * @return type
     */
    public static function insertPaymentUserInfoRecord($data) {
        global $wpdb;
        $payments_user_info_table = $wpdb->prefix . 'payments_user_info';
        
        return $wpdb->insert($payments_user_info_table, [
                    //'id' => '', // auto
                    'patient_id' => $data['patient_id'],
                    'card_number' => $data['card_number'] ? $data['card_number'] : '',
                    'card_expiry_date' => $data['card_expiry_date'] ? $data['card_expiry_date'] : '',
                    'credit_card_holder_name' => $data['credit_card_holder_name'] ? $data['credit_card_holder_name'] : '',
                    'nit' => $data['nit'] ? $data['nit'] : '',
                    'nit_full_name' => $data['nit_full_name'] ? $data['nit_full_name'] : '',
                    'nit_address' => $data['nit_address'] ? $data['nit_address'] : '',
                    
                    'payment_token' => $data['payment_token'] ? $data['payment_token'] : '',
                    'created_at' => current_time('mysql', 1),
                    'updated_at' => current_time('mysql', 1)
                        ], ['%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s']);
    }
    
    /**
     * Modify a record into payments_user_info table
     * 
     * @global type $wpdb
     * @param type $data
     * @param type $current_data
     * @return type
     */
    public static function modifyPaymentUserInfoRecord($data, $current_data) {
        global $wpdb;
        $payments_user_info_table = $wpdb->prefix . 'payments_user_info';
        
        return $wpdb->update(
                $payments_user_info_table, 
                [
                    //'id' => '', // auto
                    'card_number' => $data['card_number'] ? $data['card_number'] : ( $current_data->card_number ? $current_data->card_number : '' ),
                    'card_expiry_date' => $data['card_expiry_date'] ? $data['card_expiry_date'] : ( $current_data->card_expiry_date ? $current_data->card_expiry_date : '' ),
                    'credit_card_holder_name' => $data['credit_card_holder_name'] ? $data['credit_card_holder_name'] : ( $current_data->credit_card_holder_name ? $current_data->credit_card_holder_name : '' ),
                    'nit' => $data['nit'] ? $data['nit'] : ( $current_data->nit ? $current_data->nit : '' ),
                    'nit_full_name' => $data['nit_full_name'] ? $data['nit_full_name'] : ( $current_data->nit_full_name ? $current_data->nit_full_name : '' ),
                    'nit_address' => $data['nit_address'] ? $data['nit_address'] : ( $current_data->nit_address ? $current_data->nit_address : '' ),
                    
                    'payment_token' => $data['payment_token'] ? $data['payment_token'] : ( $current_data->payment_token ? $current_data->payment_token : '' ),
                    'updated_at' => current_time('mysql', 1)
                        ], 
                
                array('patient_id' => $data['patient_id']),
                
                ['%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s']);
    }
    
    /**
     * Modify a record into payments_user_info table
     * 
     * @global type $wpdb
     * @param type $data
     * @return type
     */
    public static function insertOrUpdatePaymentUserInfoRecord($data) {
        
        $exist = self::getPaymentUserInfo($data['patient_id']);
        if ( $exist ){
            $response = self::modifyPaymentUserInfoRecord($data, $exist);
        }else{
            $response = self::insertPaymentUserInfoRecord($data);
        }
        
        if ( $response ){
            $response = self::getPaymentUserInfo($data['patient_id']);
        }
        
        return $response;
    }
    
}
