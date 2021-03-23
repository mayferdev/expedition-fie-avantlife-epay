<?php

trait Activities{
    
    /**
     * Get follow record by passed $id
     * 
     * @param integer $id id to get
     * @return array
     * 
     */
    public static function getActivityRecord($id) {
        global $wpdb;
        $table = ACTIVITIES_TABLE;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d;", $id ));
    }
    
    public static function getActivityRecordsForUser($user_id) {
        global $wpdb;
        $table = ACTIVITIES_TABLE;
        return $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE "
                . " (user_id = %d AND deleted=0) "
                . " OR (ref_id = %d AND type='follow' AND deleted=0) ORDER BY created_at DESC "
                . "LIMIT 10;", $user_id, $user_id ));
    }
    
    /**
     * Inserts a record into ACTIVITIES_TABLE table
     * 
     * @global type $wpdb
     * @param type $user_id
     * @param type $type
     * @param type $ref_id
     * @return type
     */
    public static function insertActivityRecord($user_id, $type, $ref_id, $ref_id2 = 0) {
        global $wpdb;
        $table = ACTIVITIES_TABLE;
        
        return $wpdb->insert($table, [
            //'id' => '', // auto
            'user_id' => $user_id,
            'type' => $type,
            'ref_id' => $ref_id,
            'ref_id2' => (int)$ref_id2,
            'deleted' => 0,
            
            'created_at' => current_time('mysql', 1),
            'updated_at' => current_time('mysql', 1)
                ], ['%d','%s','%d','%d',     '%s', '%s',]);
    }
    
    /**
     * Modify a record into ACTIVITIES_TABLE table
     * 
     * @global type $wpdb
     * @param type $id
     * @param type $place_id
     * @param type $google_result
     * @return type
     */
    public static function modifyActivityRecord($id, $deleted) {
        global $wpdb;
        $table = ACTIVITIES_TABLE;
        
        return $wpdb->update(
                $table, 
                [
                'deleted' => $deleted,
                
                'updated_at' => current_time('mysql', 1),
                        ], 
                
                array('id' => $id),
                
                ['%d',  '%s']);
    }
    
}