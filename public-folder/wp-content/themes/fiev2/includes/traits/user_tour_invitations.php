<?php

/**
 * Helpers for user_tour_invitations table
 */
trait UserTourInvitations{
    
    /**
     * Deletes the passed invitation id
     * 
     * @param integer $id The invitation to delete
     * @return boolean
     * 
     */
    public static function deleteTourInvitationByID($id) {
        global $wpdb;
        return $wpdb->delete( USER_TOUR_INVITATIONS_TABLE, array( 'id' => $id ), array( '%d' ) );
    }
    
    /**
     * Inserts a record into user tour invitation table
     * 
     * @global type $wpdb
     * @param type $data
     * @return type
     */
    public static function insertUserTourInvitation($data) {
        global $wpdb;
        
        $insert_response = $wpdb->insert(USER_TOUR_INVITATIONS_TABLE, [
            //'id' => '', // auto
            'user_id' => $data['user_id'],
            'owner_id' => $data['owner_id'],
            'tour_id' => $data['tour_id'],
            'status' => $data['status'],
            
            'created_at' => current_time('mysql', 1),
            'updated_at' => current_time('mysql', 1)
                ], ['%d', '%d', '%d', '%d', '%s', '%s']);
        
        if ( $insert_response ){
            $record_id = $wpdb->insert_id;
            $response = self::getTourInvitation($record_id);
            do_action('expedition_user_invited_to_tour', $record_id, $response );
            return $response;
        }
        
        return $insert_response;
        
    }
    
    /**
     * Get specific invitation $id
     * 
     * @param integer $id invitation id to get
     * @return object
     * 
     */
    public static function getTourInvitation($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("
			SELECT * FROM ".USER_TOUR_INVITATIONS_TABLE."
			WHERE id = %d;
		", $id ));
    }
    
    
    /**
     * Get list of invitations to the passed $user_id
     * 
     * @param integer $user_id The user to find invitations to
     * @return array
     * 
     */
    public static function getTourInvitationsByInvited($user_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM ".USER_TOUR_INVITATIONS_TABLE."
			WHERE user_id = %d "./*AND status = 1*/";", $user_id ));
    }
    
    /**
     * Get single invitation to $user_id for passed $tour_id
     * 
     * @param integer $user_id The user to find invitations to
     * @return array
     * 
     */
    public static function getTourInvitationByTourAndInvited($tour_id, $user_id) {
        global $wpdb;
        
        return $wpdb->get_row($wpdb->prepare("
			SELECT * FROM ".USER_TOUR_INVITATIONS_TABLE."
			WHERE user_id = %d AND tour_id = %d;", $user_id, $tour_id ));
    }
    
    /**
     * Get invitations of passed $tour_id
     * 
     * @param integer $tour_id The tour to find invitations
     * @return array
     * 
     */
    public static function getTourInvitationByTour($tour_id) {
        global $wpdb;
        
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM ".USER_TOUR_INVITATIONS_TABLE."
			WHERE tour_id = %d;", $tour_id ));
    }
    
    /**
     * Get list of invitations from the passed $user_id
     * 
     * @param integer $user_id The user to find invitations from
     * @return array
     * 
     */
    public static function getTourInvitationsByOwner($user_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM ".USER_TOUR_INVITATIONS_TABLE."
			WHERE owner_id = %d "./*AND status = 1*/";", $user_id ));
    }
    
    /**
     * Modify the status for a tour invite
     * 
     * @global type $wpdb
     * @param type $data
     * @return type
     */
    public static function modifyTourInvitation($data) {
        global $wpdb;
        
        $response = $wpdb->update( USER_TOUR_INVITATIONS_TABLE, 
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
            return self::getTourInvitation($data['id']);
        }
        
        return $response;
        
    }
    
}