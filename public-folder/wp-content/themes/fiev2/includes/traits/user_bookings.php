<?php

/**
 * Helpers for user_bookings table
 */
trait UserBookings{
    
    /**
     * Get User Bookings Between date1 - date2
     * 
     * 
     *Params: first_day_prev_month_date, last_day_prev_month_date, owner_id
     * 
     */
     
     public static function getBookingsBetween($first_day_prev_month_date, $last_day_prev_month_date, $owner_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM ".USER_BOOKINGS_TABLE."
			WHERE created_at > %s AND created_at < %s AND owner_id = %d;
		", $first_day_prev_month_date, $last_day_prev_month_date, $owner_id ));
    }
    
    // For admin users
    
    public static function getBookingsBetweenForAdmin($first_day_prev_month_date, $last_day_prev_month_date) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM ".USER_BOOKINGS_TABLE."
			WHERE created_at > %s AND created_at < %s;
		", $first_day_prev_month_date, $last_day_prev_month_date ));
    }
    
    
    /**
     * Inserts a record into user bookings table
     * 
     * @global type $wpdb
     * @param type $data
     * @return type
     */
    public static function insertUserBooking($data) {
        global $wpdb;
        
        $insert_response = $wpdb->insert(USER_BOOKINGS_TABLE, [
            //'id' => '', // auto
            'user_id' => $data['user_id'],
            'owner_id' => $data['owner_id'],
            'tour_id' => $data['tour_id'],
            'tour_meta' => $data['tour_meta'],
            'seats' => $data['seats'],
            'amount' => @$data['amount'],
            'source' => @$data['source'],
            'status' => $data['status'],
            'total_discount' => $data['total_discount'],
            'discount_code' => $data['discount_code'],
            'total_charged' => $data['total_charged'],

            
            'created_at' => current_time('mysql', 1),
            'updated_at' => current_time('mysql', 1)
                ], ['%d', '%d', '%d', '%s', '%d', '%f', '%s', '%d', '%f', '%s', '%f', '%s', '%s']);
        
        if ( $insert_response ){
            $record_id = $wpdb->insert_id;
            return self::getUserBooking($record_id);
        }
        
        return $insert_response;
        
    }
    
    /**
     * Get specific booking $id
     * 
     * @param integer $id booking id to get
     * @return object
     * 
     */
    public static function getUserBooking($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("
			SELECT * FROM ".USER_BOOKINGS_TABLE."
			WHERE id = %d;
		", $id ));
    }
    
    /**
     * Get list of bookings of the passed $user_id as owner
     * 
     * @param integer $user_id The owner to find bookings
     * @return array
     * 
     */
    public static function getUserBookingByOwner($user_id, $limit = 1000) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM ".USER_BOOKINGS_TABLE."
			WHERE owner_id = %d "./*AND status = 1*/" LIMIT %d", $user_id, $limit ));
    }
    
    /**
     * Get list of bookings of the passed $user_id as owner
     * 
     * @param integer $limit The number of bookings to find
     * @return array
     * 
     */
    public static function getAllUserBooking($limit = 1000) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM ".USER_BOOKINGS_TABLE."
			WHERE 1 = 1 "./*AND status = 1*/" LIMIT %d", $limit ));
    }
    
    /**
     * Get list of bookings of the passed $user_id
     * 
     * @param integer $user_id The user to find bookings
     * @return array
     * 
     */
    public static function getUserBookingByUser($user_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM ".USER_BOOKINGS_TABLE."
			WHERE user_id = %d "./*AND status = 1*/";", $user_id ));
    }
    
    /**
     * Get number of valid bookings for $tour_id
     * 
     * @param integer $tour_id The tour to find bookings
     * @return array
     * 
     */
    public static function getNumberOfValidUserBookingsByTour($tour_id) {
        global $wpdb;
        
        /*return $wpdb->get_row($wpdb->prepare("
			SELECT * FROM ".USER_BOOKINGS_TABLE."
			WHERE status != %d AND tour_id = %d;", BOOKING_REJECTED, $tour_id ));*/
        
        /*return (int)$wpdb->get_var($wpdb->prepare("
			SELECT COUNT(*) FROM ".USER_BOOKINGS_TABLE."
			WHERE status != %d AND tour_id = %d;", BOOKING_REJECTED, $tour_id ));*/
        
        return (int)$wpdb->get_var($wpdb->prepare("
			SELECT SUM(seats) as seats FROM ".USER_BOOKINGS_TABLE."
			WHERE status != %d AND tour_id = %d;", BOOKING_REJECTED, $tour_id ));
    }
    
    /**
     * Get list of bookings of the passed $tour_id
     * 
     * @param integer $tour_id The tour to find bookings
     * @return array
     * 
     */
    public static function getUserBookingByTour($tour_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM ".USER_BOOKINGS_TABLE."
			WHERE tour_id = %d "./*AND status = 1*/";", $tour_id ));
    }
    
    /**
     * Get single booking to $user_id for passed $tour_id
     * 
     * @param integer $user_id The user to find bookings
     * @return array
     * 
     */
    public static function getUserBookingByUserAndTour($tour_id, $user_id) {
        global $wpdb;
        
        return $wpdb->get_row($wpdb->prepare("
			SELECT * FROM ".USER_BOOKINGS_TABLE."
			WHERE user_id = %d AND tour_id = %d;", $user_id, $tour_id ));
    }
    
    /**
     * Get list of bookings from the passed $owner_id
     * 
     * @param integer $owner_id The user to find bookings
     * @return array
     * 
     */
    public static function getBookingsByOwner($owner_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM ".USER_BOOKINGS_TABLE."
			WHERE owner_id = %d "./*AND status = 1*/";", $owner_id ));
    }
    
    /**
     * Modify the status for a booking
     * 
     * @global type $wpdb
     * @param type $data
     * @return type
     */
    public static function modifyUserBooking($data) {
        global $wpdb;
        
        $response = $wpdb->update( USER_BOOKINGS_TABLE, 
                array( 
                    'status' => $data['status'],
                    'updated_at' => current_time('mysql', 1)
                ), 
                array( 
                    'id' => $data['id'],
                    ), 
                array( '%d', '%s' ), 
                array( '%d' )
        );
        
        if ( $response ){
            error_log( print_r( ">>>>MUB response: ", true ) );
            error_log( print_r( $response, true ) );
            $response = self::getUserBooking($data['id']);
            if ( $data['status'] == BOOKING_CONFIRMED ){
                do_action('expedition_booking_confirmed', $data['id'], $response );
            }else if ( $data['status'] == BOOKING_CHECKED_IN ){
                do_action('expedition_booking_checked_in', $data['id'], $response );
            }else if ( $data['status'] == BOOKING_PENDING_CONFIRM ){
                do_action('expedition_booking_pending_confirm', $data['id'], $response);
            }
            else if ( $data['status'] == BOOKING_CONFIRMED_CARD ){
                do_action('expedition_booking_confirmed_card', $data['id'], $response);
            }
            else if ( $data['status'] == BOOKING_CANCELLED ){
                do_action('expedition_booking_cancelled', $data['id'], $response);
            }    
        }
        
        return $response;
        
    }
    
    
    /**
     * Deletes a record from user bookings table
     * 
     * @global type $wpdb
     * @param int $id the ID to delete
     * @return type
     */
    public static function deleteUserBookingRecord($id) {
        global $wpdb;
        return $wpdb->delete( USER_BOOKINGS_TABLE, array( 
            'id' => $id
            ), array( '%d' ) );
    }
    
}