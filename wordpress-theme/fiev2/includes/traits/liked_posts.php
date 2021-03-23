<?php

trait LikedPosts{
    
    public static function getLikedPostsByUser($user_id) {
        global $wpdb;
        $sessions_table = LIKED_POSTS_TABLE;
        
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM $sessions_table
			WHERE user_id = %d; 
		", $user_id ));
    }
    
    public static function getLikedPostsByPost($user_id) {
        global $wpdb;
        $sessions_table = SAVED_POSTS_TABLE;
        
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM $sessions_table
			WHERE post_id = %d;
		", $user_id ));
    }
    
    public static function getLikedPostCount($post_id) {
        global $wpdb;
        $sessions_table = LIKED_POSTS_TABLE;
        return $wpdb->get_var($wpdb->prepare("
			SELECT count(id) FROM $sessions_table
			WHERE post_id = %d;
		", $post_id ));
    }
    
    public static function getLikedPostByUserAndPost($user_id, $post_id) {
        global $wpdb;
        $sessions_table = LIKED_POSTS_TABLE;
        
        return $wpdb->get_row($wpdb->prepare("
			SELECT * FROM $sessions_table
			WHERE user_id = %d AND post_id = %d; 
		", $user_id, $post_id ));
    }
    
    public static function insertLikedPost($user_id, $post_id) {
        global $wpdb;
        $sessions_table = LIKED_POSTS_TABLE;
        
        return $wpdb->insert($sessions_table, [
            //'id' => '', // auto
            
            'user_id' => $user_id,
            'post_id' => $post_id,
            
            'created_at' => current_time('mysql', 1),
            'updated_at' => current_time('mysql', 1)
                ], [
                    '%d', '%d', 
                    '%s', '%s',
                    ]);
    }
    
    public static function deleteLikedPostRecord($id) {
        global $wpdb;
        $sessions_table = LIKED_POSTS_TABLE;
        
        return $wpdb->delete( $sessions_table, array( 
            'id' => $id
            ), array( '%d' ) );
    }
    
}