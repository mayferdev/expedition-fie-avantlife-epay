<?php

trait GooglePlaces{
    
    /**
     * Get google place of passed $place_id
     * 
     * @param integer $place_id gogle place id to get
     * @return array
     * 
     */
    public static function getGooglePlace($place_id) {
        global $wpdb;
        $table = GOOGLE_PLACES_TABLE;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE place_id='%s'; ", $place_id ));
    }
    
    /**
     * Inserts a record into google place table
     * 
     * @global type $wpdb
     * @param type $place_id
     * @param type $google_result
     * @return type
     */
    public static function insertGooglePlaceRecord($place_id, $google_result) {
        global $wpdb;
        $table = GOOGLE_PLACES_TABLE;
        
        return $wpdb->insert($table, [
            //'id' => '', // auto
            'place_id' => $place_id,
            'google_result' => $google_result,
            
            'created_at' => current_time('mysql', 1),
            'updated_at' => current_time('mysql', 1)
                ], ['%s', '%s',     '%s', '%s',]);
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
    public static function modifyGooglePlaceRecord($id, $place_id, $google_result) {
        global $wpdb;
        $table = GOOGLE_PLACES_TABLE;
        
        return $wpdb->update(
                $table, 
                [
                'place_id' => $place_id,
                'google_result' => $google_result,
                
                'updated_at' => current_time('mysql', 1),
                        ], 
                
                array('id' => $id),
                
                ['%s', '%s',   '%s']);
    }
    
}