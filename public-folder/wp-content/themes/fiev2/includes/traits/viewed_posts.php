<?php

trait ViewedPosts{
    
    public static function getViewedPostsByUser($user_id) {
        global $wpdb;
        $sessions_table = VIEWED_POSTS_TABLE;
        
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM $sessions_table
			WHERE user_id = %d; 
		", $user_id ));
    }
    
    public static function getViewedPostCount($post_id) {
        global $wpdb;
        $sessions_table = VIEWED_POSTS_TABLE;
        return $wpdb->get_var($wpdb->prepare("
			SELECT count(id) FROM $sessions_table
			WHERE post_id = %d;
		", $post_id ));
    }
    
    public static function getViewedPostByUserAndPost($user_id, $post_id) {
        global $wpdb;
        $sessions_table = VIEWED_POSTS_TABLE;
        
        return $wpdb->get_row($wpdb->prepare("
			SELECT * FROM $sessions_table
			WHERE user_id = %d AND post_id = %d; 
		", $user_id, $post_id ));
    }
    
    public static function insertViewedPost($user_id, $post_id) {
        global $wpdb;
        $sessions_table = VIEWED_POSTS_TABLE;
        
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
    
    public static function deleteViewedPostecord($id) {
        global $wpdb;
        $sessions_table = VIEWED_POSTS_TABLE;
        
        return $wpdb->delete( $sessions_table, array( 
            'id' => $id
            ), array( '%d' ) );
    }
    
}