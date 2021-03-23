<?php

/**
 * Helpers for what_to_bring table
 */
trait WhatToBring{
    
    /**
     * Deletes the passed record id
     * 
     * @param integer $id The invitation to delete
     * @return boolean
     * 
     */
    public static function deleteWhatToBringByID($id) {
        global $wpdb;
        return $wpdb->delete( WHAT_TO_BRING_TABLE, array( 'id' => $id ), array( '%d' ) );
    }
    
    /**
     * Inserts or updates a record into WhatToBring table
     * 
     * @global type $wpdb
     * @param type $data
     * @return type
     */
    public static function insertOrUpdateWhatToBring($data) {
        global $wpdb;
        
        $exists = self::getWhatToBringByUserAndAcf($data['user_id'], $data['acf_id']);
        
        if ( !$exists ){
            return self::insertWhatToBring($data);
        }
        
        $response = $wpdb->update( WHAT_TO_BRING_TABLE, 
                array( 
                    'user_id' => (int)$data['user_id'],
                    'tour_id' => (int)$data['tour_id'],
                    'acf_id' => (string)$data['acf_id'],
                    'status' => (int)$data['status'],
                    'updated_at' => current_time('mysql', 1)
                ), 
                array( 
                    'id' => (int)$exists->id,
                    ), 
                array( '%d', '%d', '%s', '%d', '%s' ), 
                array( '%d') 
        );
        
        if ( $response ){
            return self::getWhatToBringRecord($exists->id);
        }
        
        return $response;
        
    }
    
    /**
     * Inserts a record into WhatToBring table
     * 
     * @global type $wpdb
     * @param type $data
     * @return type
     */
    public static function insertWhatToBring($data) {
        global $wpdb;
        
        $insert_response = $wpdb->insert(WHAT_TO_BRING_TABLE, [
            //'id' => '', // auto
            'user_id' => $data['user_id'],
            'tour_id' => $data['tour_id'],
            'acf_id' => $data['acf_id'],
            'status' => $data['status'],
            
            'created_at' => current_time('mysql', 1),
            'updated_at' => current_time('mysql', 1)
                ], ['%d', '%d', '%s', '%d', '%s', '%s']);
        
        if ( $insert_response ){
            $record_id = $wpdb->insert_id;
            return self::getWhatToBringRecord($record_id);
        }
        
        return $insert_response;
        
    }
    
    /**
     * Get specific WhatToBring $id
     * 
     * @param integer $id to get
     * @return object
     * 
     */
    public static function getWhatToBringRecord($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("
			SELECT * FROM ".WHAT_TO_BRING_TABLE."
			WHERE id = %d;
		", $id ));
    }
    
    
    /**
     * Get list of records from the passed $user_id
     * 
     * @param integer $user_id The user to find WhatToBring
     * @return array
     * 
     */
    public static function getWhatToBringByUser($user_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM ".WHAT_TO_BRING_TABLE."
			WHERE user_id = %d "./*AND status = 1*/";", $user_id ));
    }
    
    /**
     * Get record from the passed $user_id and ACF id
     * 
     * @param integer $user_id The user to find WhatToBring
     * @param integer $acf_id The ACF id to find WhatToBring
     * @return array
     * 
     */
    public static function getWhatToBringByUserAndAcf($user_id, $acf_id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("
			SELECT * FROM ".WHAT_TO_BRING_TABLE."
			WHERE user_id = %d AND acf_id = %s;", $user_id, $acf_id ));
    }
    
    /**
     * Get WhatToBring from $user_id and passed $tour_id
     * 
     * @param integer $user_id The user to find WhatToBring
     * @return array
     * 
     */
    public static function getWhatToBringByTourAndUser($tour_id, $user_id) {
        global $wpdb;
        
        return $wpdb->get_row($wpdb->prepare("
			SELECT * FROM ".WHAT_TO_BRING_TABLE."
			WHERE user_id = %d AND tour_id = %d;", $user_id, $tour_id ));
    }
    
    /**
     * Get WhatToBring from $user_id and passed $tour_id
     * 
     * @param integer $user_id The user to find WhatToBring
     * @return array
     * 
     */
    public static function getArrayWhatToBringByTourAndUser($tour_id, $user_id) {
        global $wpdb;
        
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM ".WHAT_TO_BRING_TABLE."
			WHERE user_id = %d AND tour_id = %d;", $user_id, $tour_id ));
    }
    
    /**
     * Modify the status for a record in WhatToBring table
     * 
     * @global type $wpdb
     * @param type $data
     * @return type
     */
    public static function modifyWhatToBring($data) {
        global $wpdb;
        
        $response = $wpdb->update( WHAT_TO_BRING_TABLE, 
                array( 
                    'status' => $data['status'],
                    'updated_at' => current_time('mysql', 1)
                ), 
                array( 
                    'id' => $data['id'],
                    ), 
                array( '%d', '%s' ), 
                array( '%d') 
        );
        
        if ( $response ){
            return self::getWhatToBringRecord($data['id']);
        }
        
        return $response;
        
    }
    
}