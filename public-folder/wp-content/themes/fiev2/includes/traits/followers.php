<?php

trait Followers{
    
    /**
     * Get follow record by passed $id
     * 
     * @param integer $id id to get
     * @return array
     * 
     */
    public static function getFollowRecord($id) {
        global $wpdb;
        $table = FOLLOWERS_TABLE;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d;", $id ));
    }
    
    public static function getFollowRecordByUsers($follower_id, $following_id) {
        global $wpdb;
        $table = FOLLOWERS_TABLE;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE follower_id = %d AND following_id = %d AND deleted = 0;", $follower_id, $following_id ));
    }
    
    public static function getFollowersCountOfUser($following_id) {
        global $wpdb;
        $table = FOLLOWERS_TABLE;
        return (int)$wpdb->get_var($wpdb->prepare("SELECT count(id) FROM $table WHERE following_id = %d AND deleted = 0;", $following_id ));
    }
    
    public static function getFollowsCountOfUser($follower_id) {
        global $wpdb;
        $table = FOLLOWERS_TABLE;
        return (int)$wpdb->get_var($wpdb->prepare("SELECT count(id) FROM $table WHERE follower_id = %d AND deleted = 0;", $follower_id ));
    }
    
    /**
     * Inserts a record into FOLLOWERS_TABLE table
     * 
     * @global type $wpdb
     * @param type $place_id
     * @param type $google_result
     * @return type
     */
    public static function insertFollowRecord($follower_id, $following_id) {
        global $wpdb;
        $table = FOLLOWERS_TABLE;
        
        return $wpdb->insert($table, [
            //'id' => '', // auto
            'follower_id' => $follower_id,
            'following_id' => $following_id,
            'deleted' => 0,
            
            'created_at' => current_time('mysql', 1),
            'updated_at' => current_time('mysql', 1)
                ], ['%d','%d','%d',     '%s', '%s',]);
    }
    
    /**
     * Modify a record into FOLLOWERS_TABLE table
     * 
     * @global type $wpdb
     * @param type $id
     * @param type $place_id
     * @param type $google_result
     * @return type
     */
    public static function modifyFollowRecord($id, $deleted) {
        global $wpdb;
        $table = FOLLOWERS_TABLE;
        
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