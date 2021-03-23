<?php

trait Sessions{
    
    /**
     * Get sessions 
     * 
     * @param integer $user_id
     * @return array
     * 
     */
    public static function getSessions($user_id) {
        global $wpdb;
        $sessions_table = $wpdb->prefix . 'sessions';
        
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM $sessions_table
			WHERE user_id = %d;
		", $user_id ));
    }
    
    /**
     * Get session of passed id
     * 
     * @param integer $id annotation id to get
     * @return array
     * 
     */
    public static function getSession($id) {
        global $wpdb;
        $sessions_table = $wpdb->prefix . 'sessions';
        return $wpdb->get_row($wpdb->prepare("
			SELECT * FROM $sessions_table
			WHERE id = %d;
		", $id ));
    }
    
    /**
     * Get session by token
     * 
     * @param string $token
     * @return object
     * 
     */
    public static function getSessionByToken($token) {
        global $wpdb;
        $sessions_table = $wpdb->prefix . 'sessions';
        return $wpdb->get_row($wpdb->prepare("
			SELECT * FROM $sessions_table
			WHERE token = %s;
		", $token ));
    }
    
    /**
     * Inserts a record into sessions table
     * 
     * @global type $wpdb
     * @param type $data
     * @return type
     */
    public static function insertSessionRecord($data) {
        global $wpdb;
        $sessions_table = $wpdb->prefix . 'sessions';
        
        return $wpdb->insert($sessions_table, [
            //'id' => '', // auto
            
            'token' => @$data['token'],
            'sl_token' => @$data['sl_token'],
            'user_id' => @$data['user_id'],
            'role' => @$data['role'],
            
            'status' => @$data['status'],
            
            'created_at' => current_time('mysql', 1),
            'updated_at' => current_time('mysql', 1)
                ], [
                    '%s', '%s', '%d', '%s',
                    '%d',
                    '%s', '%s',
                    ]);
    }
    
    /**
     * Modify a record into sessions table
     * 
     * @global type $wpdb
     * @param type $data
     * @return type
     */
    public static function modifySessionRecord($data) {
        global $wpdb;
        $sessions_table = $wpdb->prefix . 'sessions';
        
        return $wpdb->update(
                $sessions_table, 
                [
                //'id' => '', // auto
                
                'status' => @$data['status'],
                'updated_at' => current_time('mysql', 1)
                        ], 
                
                array('id' => $data['id']),
                
                ['%d','%s',]);
    }
    
    /**
     * Deletes a record into sessions table
     * 
     * @global type $wpdb
     * @param int $id the ID to delete
     * @return type
     */
    public static function deleteSessionRecord($id) {
        global $wpdb;
        $sessions_table = $wpdb->prefix . 'sessions';
        
        return $wpdb->delete( $sessions_table, array( 
            'id' => $id
            ), array( '%d' ) );
    }
    
}