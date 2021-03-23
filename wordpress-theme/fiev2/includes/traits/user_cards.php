<?php

trait UserCards{
    
    /**
     * Get card  of passed $card_id
     * 
     * @param integer $card_id card id to get
     * @return array
     * 
     */
    public static function getUserCard($card_id) {
        global $wpdb;
        $table = USER_CARDS_TABLE;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE card_id = %s;", $card_id ));
    }
    
    /**
     * Get cards of passed $user_id
     * 
     * @param integer $user_id user id to get cards
     * @return array
     * 
     */
    public static function getActiveUserCardsByUser($user_id) {
        global $wpdb;
        $table = USER_CARDS_TABLE;
        return $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE user_id = %s AND status=1;", $user_id ));
    }
    
    /**
     * Get cards of passed $user_id
     * 
     * @param integer $user_id user id to get cards
     * @return array
     * 
     */
    public static function getUserCardsByUser($user_id) {
        global $wpdb;
        $table = USER_CARDS_TABLE;
        return $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE user_id = %s;", $user_id ));
    }
    
    /**
     * Get card by booking Id
     * 
     * @param integer $booking_id  to get cards
     * @return array
     * 
     */
    public static function getUserCardByBookingId($booking_id, $user_id) {
        global $wpdb;
        $table = USER_CARDS_TABLE;
        return $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE user_id = %s ORDER BY id DESC LIMIT 1;", $user_id ));

    }
    
    
    
    /**
     * Inserts a record into user cards table
     * 
     * @global type $wpdb
     * @param type $card_id
     * @param type $google_result
     * @return type
     */
    public static function insertUserCardRecord( $data ) {
        global $wpdb;
        extract($data);
        
        return $wpdb->insert(USER_CARDS_TABLE, [
            //'id' => '', // auto
            'user_id'           => $user_id,
            'alias'             => $alias,
            'credit_card_name'  => $credit_card_name,
            'credit_card_expiry'=> $credit_card_expiry,
            'credit_card_brand' => $credit_card_brand,
            'credit_card_last4' => $credit_card_last4,
            
            'afiliation' => $afiliation_epay,
            'audit_num' => $audit_num,
            'reference_num' => $reference_num,
            'auth_num' => $auth_num,
            'amount' => $amount,
            'booking_id' => $booking_id,
            
            'gateway' => $gateway,
            'token' => $token,
            'status' => $status,
            
            'created_at' => current_time('mysql', 1),
            'updated_at' => current_time('mysql', 1)
                ], ['%d',
                    '%s','%s','%s','%s','%s',
                    '%s','%s','%s','%s','%s', '%d',
                    '%s','%s', '%d',    '%s', '%s',]);
    }
    
    /**
     * Modify a record into google place table
     * 
     * @global type $wpdb
     * @param type $id
     * @param type $place_id
     * @param type $google_result
     * @return type
     */
    public static function modifyUserCardRecord($data) {
        global $wpdb;
        $table = USER_CARDS_TABLE;
        extract($data);
        
        return $wpdb->update(
                $table, 
                [
                'user_id'           => $user_id,
                'alias'             => $alias,
                'credit_card_name'  => $credit_card_name,
                'credit_card_expiry'=> $credit_card_expiry,
                'credit_card_brand' => $credit_card_brand,
                'credit_card_last4' => $credit_card_last4,
                'gateway'           => $gateway,
                'token'             => $token,
                'status' => $status,
                
                'updated_at' => current_time('mysql', 1),
                        ], 
                
                array('id' => $id),
                
                [ '%d', '%s','%s','%s','%s','%s','%s', '%s','%d', '%s' ]);
    }
    
    /**
     * Deletes a record into user cards table
     * 
     * @global type $wpdb
     * @param int $id the ID to delete
     * @return type
     */
    public static function deleteUserCardRecord($id) {
        global $wpdb;
        
        return $wpdb->delete( USER_CARDS_TABLE, array( 
            'id' => $id
            ), array( '%d' ) );
    }
    
}