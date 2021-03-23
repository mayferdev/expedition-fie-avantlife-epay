<?php

/**
 * I try to store helpers functions this
 * 
 */
class Expedition_Helper {
    
    use UserTourInvitations, 
        UserBookings,
        Transactions,
        WhatToBring,
        
        // PaymentsUserInfo,
        // PaymentsUserInfoFailed,
        Sessions,
        Invoices,
        GooglePlaces,
        TourExpeditioners,
        UserCards,
        Followers,
        Activities,
        SavedPosts,
        LikedPosts,
        ViewedPosts
            ;
    
    
    public static function fillPlacesForAtachment( $attachment_id ){
        $placeID = get_post_meta($attachment_id, 'placeID', true);
        if ( !$placeID ){
            return;
        }

        $google_result1 = Expedition_Helper::getGooglePlace($placeID);

        if (!$google_result1){
            $map_detail_url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=$placeID&key=".EXPEDITION_GOOGLE_MAPS_API_KEY;
            $response = wp_remote_get($map_detail_url);
            if ( is_array( $response ) ) {
                // $header = $response['headers']; // array of http header lines
                $body_raw = $response['body']; // use the content
                // var_dump($map_detail_url, $body_raw);
                // exit();
                $body = json_decode($body_raw);
                if ( isset($body->result) ){
                    Expedition_Helper::insertGooglePlaceRecord($placeID, json_encode($body->result) );
                    $google_result = $body->result;
                }
            }
        }else{
            $google_result = json_decode( $google_result1->google_result );
        }

        $google_result->geometry;
        $google_result->geometry->location;
        $google_result->geometry->location->lat;
        $google_result->geometry->location->lng;

        $comps = $google_result->address_components;
        //$country = $comps[1]->long_name;
        //$administrative_area_level_1 = $comps[2]->long_name;

        if (is_array($comps) && count( $comps ) >0 ){
            foreach ($comps as $comp) {
                if ( $comp->types[0] == 'administrative_area_level_1' ){
                    $administrative_area_level_1 = $comp->long_name;
                }
                if ( $comp->types[0] == 'country' ){
                    $country = $comp->long_name;
                }
            }
        }

        $administrative_area_level_1 = str_replace(array(' Department'), array(''), $administrative_area_level_1);

        // var_dump_pre( $country, $administrative_area_level_1, $google_result->formatted_address, $google_result->name, $placeID, $attachment_id, $google_result);
        // exit();
        // 
        // update_post_meta($attachment_id, 'latitude', $latitude);
        // update_post_meta($attachment_id, 'longitude', $longitude);
        // update_post_meta($attachment_id, 'placeID', $placeID);
        
        
        /*update_field('country', $country, $attachment_id);
        update_field('administrative_area_level_1', $administrative_area_level_1, $attachment_id);
        update_field('address', $google_result->formatted_address, $attachment_id);
        update_field('placeName', $google_result->name, $attachment_id);*/
        
        update_post_meta($attachment_id, 'country', $country.'');
        update_post_meta($attachment_id, 'administrative_area_level_1', $administrative_area_level_1);
        update_post_meta($attachment_id, 'address', $google_result->formatted_address);
        update_post_meta($attachment_id, 'placeName', $google_result->name);

        // var_dump_pre("Modified $attachment_id with $country - $administrative_area_level_1 - $google_result->formatted_address - $google_result->name");
    }
    
    
    /**
     * Returns the number of tours available per month for user of type business
     * 
     * @global type $current_user
     * @return int
     */
    public static function getBusinessAvailableToursByMonth(){
        global $current_user;
        
        $user_level = get_user_meta($current_user->ID, 'membership_level', true);
        
        $levels_qty = get_option('options_membership_levels');
        $levels = array();
        if ( $levels_qty && (int)$levels_qty> 0 ){
            for ($index = 0; $index < $levels_qty; $index++) {
                $levels[] = array('name'=> get_option("options_membership_levels_{$index}_name") , 'tours_by_month'=>get_option("options_membership_levels_{$index}_tours_by_month"));
            }
        }
        
        foreach( $levels as $level ){
            if ( $user_level == $level['name'] ){
                return $level['tours_by_month'];
            }
        }
        return 0;
    }
    
    public static function isCurrentUserAdmin(){
        $current_user_role = expedition_get_user_role();
        if ( $current_user_role ){
            if ( $current_user_role == 'administrator' ){
                return true;
            }
        }
        return false;
    }
    
    function numberToRomanRepresentation($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
    
    
    /**
     * Returns the number of tours remain of current month for user of type business
     * 
     * @global type $current_user
     * @return int
     */
    public static function getBusinessRemainToursByMonth(){
        global $current_user, $wpdb;
        
        $by_month = (int)Expedition_Helper::getBusinessAvailableToursByMonth();
        $user_id = $current_user->ID;
        $published = $wpdb->get_var($wpdb->prepare("
                    SELECT COUNT(*) FROM ".$wpdb->prefix.'posts'."
                    WHERE post_author = %d AND post_type = %s AND post_status = %s ".
                    "AND MONTH(post_date) = MONTH(CURRENT_DATE()) AND YEAR(post_date) = YEAR(CURRENT_DATE()) ".
                    // "AND MONTH(post_date) = 04 AND YEAR(post_date) = 2018 ".
                    "ORDER BY post_date DESC", $user_id, 'tour', 'publish' ));
        // echo "published $published ";
        // echo "by_month $by_month ";
        if ( $by_month > 0 ){
            return $by_month - $published;
        }
        
        return 0;
    }
    
    
    /**
     * Returns the USER object
     * 
     * @param type $id
     */
    public static function getUser( $id, $basic = false, $user = false, $third = false ){
        $wp_user = $user ? $user : get_user_by('id', $id);
        $status = (int)get_user_meta($wp_user->ID, 'status', true);
        if ( $status > 2 ){
            $status = 2;
        }
        if ( $status < 0 ){
            $status = 0;
        }

        $user = array(
            //'user_login' => $wp_user->get('user_login'),
            'full_name' => $wp_user->get('first_name').' '.$wp_user->get('last_name'),
            'first_name' => $wp_user->get('first_name'),
            'last_name' => $wp_user->get('last_name'),
            //'user_image' => $wp_user->get('user_url'),
            'role' => $wp_user->roles[0],
            'email' => $wp_user->get('user_email'),
            'status' => (int)$status,
            'ID' => $wp_user->ID,
            'dob' => get_field( 'dob', 'user_'.$wp_user->ID ),
            'gender' => get_field( 'gender', 'user_'.$wp_user->ID ),
            'main_picture' => Expedition_Helper::getAllImageSizes( get_field('main_picture', 'user_'.$wp_user->ID) ),
            'profile_picture' => Expedition_Helper::getAllImageSizes( get_field('profile_picture', 'user_'.$wp_user->ID) ),
            
            'nit' => get_field( 'nit', 'user_'.$wp_user->ID ),
            'nit_name' => get_field( 'nit_name', 'user_'.$wp_user->ID ),
            'nit_address' => get_field( 'nit_address', 'user_'.$wp_user->ID ),
            'dpi' => get_field( 'dpi', 'user_'.$wp_user->ID ),
            'phone' => get_field( 'phone', 'user_'.$wp_user->ID ),
            //'fields' => get_fields('user_'.$wp_user->ID, false)
        );
        
        if ( isset($_GET['language']) && isset($_GET['platform']) ){
            // $user['full_name'] .= $_GET['language'] .' - '.$_GET['platform'];
        }
        
        if ( !$basic ){
            $gallery_ids = get_field( 'gallery', 'user_'.$wp_user->ID, false );
            $gallery = array();
            if ( is_array($gallery_ids) && count($gallery_ids)>0 ){
                $query = new WP_Query( array( 'post_type' => 'attachment', 'post_status'=>'inherit', 'post__in' => $gallery_ids, 'orderby'=> 'date', 'order' => 'DESC', 'posts_per_page'=> 1000 ) );
                if ( $query->have_posts() ) {
                    while ( $query->have_posts() ) {
                        $query->the_post();
                        $img = Expedition_Helper::getAllImageSizes(get_the_ID());
                        if ( isset($img['medium']['url']) ){
                            $gallery[] = $img;
                        }
                    }
                    wp_reset_postdata(); // Restore original Post Data
                }
                //usort($gallery, 'galleryDateCompare');
            }
            $user['gallery'] = $gallery;
            if (!$third){
                $user_tokens        = Expedition_Helper::getActiveUserCardsByUser($id);
                $user_token         = false;
                if ( $user_tokens ){
                    $user_token = $user_tokens[0];
                }
                $user['cc'] = $user_token;
            }
            
        }
        $places = array();
        if ( isset($user['gallery']) && $user['gallery']> 0 ){
            foreach ($user['gallery'] as $item) {
                $places[ $item['placeID'] ] = $item['placeID'];
            }
        }
        $user['places'] = count($places);
        $user['followers'] = Expedition_Helper::getFollowersCountOfUser($id);
        $user['following'] = Expedition_Helper::getFollowsCountOfUser($id);
        
        return $user;
        
    }
    
    /**
     * Creates the recents instances in Firebase, 
     * 1. for tour owner
     * 2. for user interested in tour
     * 
     * @global type $firebase
     * @param int $tour_id
     * @param array $expeditioners
     * @return int
     */
    public static function createPrivateChatForTour( $tour_id, $expeditioners ){
        global $firebase;
        
        $tour = get_post($tour_id);
        $owner_id = (int)get_field('owner', $tour_id );
        $image = '';
        $gallery = get_field('gallery', $tour_id);
        if ( $gallery ){
            $image = $gallery[0]['sizes']['medium'];
        }
        $users = array();
        $users_names = array();
        
        if ( !in_array($owner_id, $expeditioners) ){
            $expeditioners[] = $owner_id;
        }
        
        foreach ($expeditioners as $expeditioner_id) {
            $other_user = get_userdata($expeditioner_id);
            $other_fullname = $other_user->first_name . ' '. $other_user->last_name ;
        
            $users[$expeditioner_id] = 1;
            $users_names[$expeditioner_id] = $other_fullname;
        }
        
        $recent = array();
        foreach ($expeditioners as $loop_user) {
            $recent_id = "tour_{$tour_id}_{$loop_user}";
            $recent= array(
                'id'    =>  $recent_id,
                'tour_id'    =>  $tour_id,
                'type' => 'private_tour',
                'name'  =>  $tour->post_title,
                'image' =>  $image,
                'user' => (int)$loop_user,
                'users' => $users,
                'unread' => 0,
                'users_names' => $users_names,
                'last_message' => 'Welcome to '.$tour->post_title,
                'last_message_by' => 0,
                'last_message_by_name' => 'Expeditioners',
                'last_message_by_image' => $image,
                'last_message_timestamp' => time()*1000,
            );
            $firebase->set($recent, "recents/$recent_id");
        }
        $firebase->set($users, "tours/tour_$tour_id");
        
        return $recent;
    }
    
    
    /**
     * Creates the recents instances in Firebase, 
     * 1. for tour owner
     * 2. for user interested in tour
     * 
     * @global type $firebase
     * @param type $tour_id
     * @param type $user_id
     * @return int
     */
    public static function createPublicChatForTour( $tour_id, $user_id, $recent_for_owner = false ){
        global $firebase;
        
        $image = '';
        $gallery = get_field('gallery', $tour_id);
        if ( $gallery ){
            $image = $gallery[0]['sizes']['medium'];
        }
        
        $tour = get_post($tour_id);
        $owner_id = (int)get_field('owner', $tour_id );
        $owner_user = get_userdata($owner_id);
        $owner_fullname = $owner_user->first_name . ' '. $owner_user->last_name ;
        
        $other_user = get_userdata($user_id);
        $other_fullname = $other_user->first_name . ' '. $other_user->last_name ;
        
        $users = array(
            $owner_id => 1,
            $user_id    => 1
        );
        $users_keys= array($owner_id, $user_id);
        $users_names = array(
            $owner_id => $owner_fullname,
            $user_id    => $other_fullname
        );
        $recent = array();
        $recent_id = "tour_{$tour_id}_{$user_id}_{$owner_id}";
        foreach ($users_keys as $key => $loop_user) {
            $from = $key == 0 ? $users_keys[1] : $users_keys[0];
            $to = $loop_user;
            $recent_id_ = "tour_{$tour_id}_{$from}_{$to}";
            
//            $recent_id_ = "tour_{$tour_id}_{$loop_user}";
            $recent= array(
                'id'    =>  $recent_id,
                'tour_id'    =>  $tour_id,
                'type' => 'public_tour',
                'name'  =>  (  $owner_id == $loop_user ? "$other_fullname - " : '' ) . $tour->post_title,
                'image' =>  $image,
                'user' => (int)$loop_user,
                'users' => $users,
                'unread' => 0,
                'users_names' => $users_names,
                'last_message' => 'Welcome to '.$tour->post_title,
                'last_message_by' => 0,
                'last_message_by_name' => 'Expeditioners',
                'last_message_by_image' => $image,
                'last_message_timestamp' => time()*1000,
            );
            $firebase->set($recent, "recents/$recent_id_");
        }
        $firebase->set($users, "tours/$recent_id");
        if ($recent_for_owner){
            $recent_id = "tour_{$tour_id}_{$owner_id}_{$user_id}";
        }
        
        
        return $recent;
    }
    
    
    /**
     * Returns the USER object minified to be user in the list of expeditioners
     * 
     * @param type $id
     */
    public static function getUserExpeditioner( $id){
        $wp_user = get_user_by('id', $id);
        $status = (int)get_user_meta($wp_user->ID, 'status', true);
        if ( $status > 2 ){
            $status = 2;
        }
        if ( $status < 0 ){
            $status = 0;
        }
        
        $main_picture = null;
        $main_picture_id = get_field('main_picture', 'user_'.$wp_user->ID);
        if ( $main_picture_id ){
            $img = wp_get_attachment_image_src($main_picture_id, 'thumbnail');
            $main_picture = $img[0];
        }
        
        $user = [
            'full_name' => $wp_user->get('first_name').' '.$wp_user->get('last_name'),
            'first_name' => $wp_user->get('first_name'),
            'last_name' => $wp_user->get('last_name'),
            'role' => $wp_user->roles[0],
            // 'email' => $wp_user->get('user_email'),
            // 'status' => (int)$status,
            'ID' => $wp_user->ID,
            // 'dob' => get_field( 'dob', 'user_'.$wp_user->ID ),
            // 'gender' => get_field( 'gender', 'user_'.$wp_user->ID ),
            'main_picture' => $main_picture,
            'one_signal_id' => get_field( 'one_signal_id', 'user_'.$wp_user->ID)
        ];
        
        return $user;
        
    }
    
    /**
     * Returns the USER object
     * 
     * @param type $id
     */
    public static function formatTour( $tour ){
        $wp_user = get_user_by('id', $id);
        $status = (int)get_user_meta($wp_user->ID, 'status', true);
        if ( $status > 2 ){
            $status = 2;
        }
        if ( $status < 0 ){
            $status = 0;
        }
        $gallery_ids = get_field( 'gallery', 'user_'.$wp_user->ID, false );
        $gallery = array();
        if ( count($gallery_ids)>0 ){
            foreach ($gallery_ids as $id) {
                $gallery[] = self::getAllImageSizes( $id );
            }
        }
        $user = [
            //'user_login' => $wp_user->get('user_login'),
            //'display_name' => $wp_user->get('display_name'),
            //'first_name' => $wp_user->get('first_name'),
            //'last_name' => $wp_user->get('last_name'),
            //'user_image' => $wp_user->get('user_url'),
            'role' => $wp_user->roles[0],
            'email' => $wp_user->get('user_email'),
            'status' => (int)$status,
            'ID' => $wp_user->ID,
            'dob' => get_field( 'dob', 'user_'.$wp_user->ID ),
            'gender' => get_field( 'gender', 'user_'.$wp_user->ID ),
            'main_picture' => self::getAllImageSizes( get_field('main_picture', 'user_'.$wp_user->ID) ),
            'gallery' => $gallery,
            //'fields' => get_fields('user_'.$wp_user->ID, false)
        ];
        
        return $user;
        
    }
    
    /**
     * Return formatted tour data
     * 
     * @param type $pid
     * @return type
     */
    public static function formatTravelNote($pid, $user_id){
        $post = get_post($pid);
        // $travel_note = get_fields( $pid );
        if (!$post){
            return false;
        }
        $travel_note['id'] = $pid;
        $travel_note['title'] = (string) get_post_field('post_title', $pid);
        $travel_note['excerpt'] = (string) strip_tags(get_the_excerpt($pid));
        $travel_note['content'] = (string) strip_tags(apply_filters('the_content', $post->post_content));
        $travel_note['thumbnail'] = Expedition_Helper::getAllImageSizes( get_field('thumbnail', $pid, false) );
        $travel_note['image'] = Expedition_Helper::getAllImageSizes( get_field('image', $pid, false) );
        
        $gallery_ids = get_field( 'images', $pid, false );
        $gallery = array();
        if ( is_array($gallery_ids) && count($gallery_ids)>0 ){
            $query = new WP_Query( array( 'post_type' => 'attachment', 'post_status'=>'inherit', 'post__in' => $gallery_ids, 'orderby'=> 'date', 'order' => 'DESC', 'posts_per_page'=> 10 ) );
            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $img = Expedition_Helper::getAllImageSizes(get_the_ID());
                    if ( isset($img['medium']['url']) ){
                        $gallery[] = $img;
                    }
                }
                wp_reset_postdata(); // Restore original Post Data
            }
            //usort($gallery, 'galleryDateCompare');
        }
        $travel_note['images'] = $gallery;
        $travel_note['images_ids'] = get_field( 'images', $pid );
        
        
        $travel_note['views'] = (int)Expedition_Helper::getViewedPostCount($pid);
        $travel_note['likes'] = (int)Expedition_Helper::getLikedPostCount($pid);
        $viewed = Expedition_Helper::getSavedPostByUserAndPost($user_id, $pid);
        $travel_note['saved'] = (BOOL)$viewed;
        $liked = Expedition_Helper::getLikedPostByUserAndPost($user_id, $pid);
        $travel_note['liked'] = (BOOL)$liked;
        $travel_note['author'] = Expedition_Helper::getUser($post->post_author, true);
        $travel_note['date'] = $post->post_date_gmt;
        $travel_note['date_timestamp'] = strtotime($post->post_date_gmt);
        
        return $travel_note;
    }
    
    /**
     * 
     * 
     * @param type $attachment_id
     * @return type
     */
    public static function getAllImageSizes($attachment_id = 0, $owner_info = false) {
        global $wpdb;
        
        $images = null;
        $sizes = array("thumbnail", "medium", "large", "full");
        // $sizes = get_intermediate_image_sizes();
        if ($attachment_id > 0) {
            $attachment = get_post($attachment_id);
            if ( !isset($attachment->post_date_gmt) ){
                return null;
            }
            $placeID = get_post_meta($attachment_id, 'placeID', true);
            $placeName = "";
            $map_detail_url = "";
            if ( $placeID ){
                $google_place = Expedition_Helper::getGooglePlace($placeID);
                if (!$google_place){
                    $map_detail_url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=$placeID&key=".EXPEDITION_GOOGLE_MAPS_API_KEY;
                    $response = wp_remote_get($map_detail_url);
                    if ( is_array( $response ) ) {
                        // $header = $response['headers']; // array of http header lines
                        $body_raw = $response['body']; // use the content
                        // var_dump($map_detail_url, $body_raw);
                        // exit();
                        $body = json_decode($body_raw);
                        if ( isset($body->result) ){
                            Expedition_Helper::insertGooglePlaceRecord($placeID, json_encode($body->result) );
                            $placeName = $body->result->name;
                        }
                    }
                }else{
                    $google_result = json_decode($google_place->google_result);
                    $placeName = @$google_result->name;
                }
            }
            
            $images = array();
            $images['id'] = (int)$attachment_id;
            $images['caption'] = (string) get_post_field('post_content', $attachment_id);
            
            $images['date'] = $attachment->post_date_gmt;
            $images['date_timestamp'] = strtotime($attachment->post_date_gmt);
            $images['placeID'] = $placeID;
            $images['placeName'] = $placeName;
            
            $images['note'] = (string)get_post_meta($attachment_id, 'note', true);
            $images['country'] = get_post_meta($attachment_id, 'country', true);
            $images['area'] = get_post_meta($attachment_id, 'administrative_area_level_1', true);
            $images['address'] = get_post_meta($attachment_id, 'address', true);
            $images['latitude'] = get_post_meta($attachment_id, 'latitude', true);
            $images['longitude'] = get_post_meta($attachment_id, 'longitude', true);
            $images['author_id'] = $attachment->post_author;
            
            if ( $owner_info ){
                $images['author'] = Expedition_Helper::getUser($attachment->post_author, true);
            }
                
            foreach ($sizes as $size) {
                
                $img = wp_get_attachment_image_src($attachment_id, $size);
                $images[$size]['url'] = $img[0];//str_replace("http://", "https://", $img[0]);
                $images[$size]['width'] = $img[1];
                $images[$size]['height'] = $img[2];
            }
        }
        return $images;
    }
    
    
        
    
    /**
     * Returns bool
     * 
     * @param array $expeditioners to create invitations to
     * @return string
     */
    public static function createTourExpeditionersInvitations($expeditioners, $post_id, $owner_id) {
        if ( count($expeditioners)>0 ){
            foreach ($expeditioners as $expeditioner) {
                Expedition_Helper::insertUserTourInvitation(array(
                    'user_id'=> (int)$expeditioner, 
                    'tour_id'=> (int)$post_id, 
                    'owner_id'=>(int)$owner_id, 
                    'status'=> $expeditioner == $owner_id ? TOUR_ACCEPTED : TOUR_PENDING ));
                
                Expedition_Helper::insertActivityRecord($expeditioner, 'booking_invitation', $post_id);
            }
        }
        Expedition_Helper::createPrivateChatForTour($post_id, $expeditioners);
    }
    
    /**
     * returns double with the total price of booking
     * 
     * @param type $code
     * @return type
     */
    public static function getTourBookingTotalAmount($booking) {
        $total = 0;
        if ( $booking && $booking->tour_meta ){
            $meta = json_decode($booking->tour_meta);
            foreach ($meta as $key => $metafield) {
                $total += $metafield->total;
            }
        }
        return $total;
    }
    
    /**
     * returns integer with the number of seats in bookings
     * 
     * @param type $code
     * @return type
     */
    public static function getTourBookingTotalSeats($booking) {
        $total = 0;
        if ( $booking && $booking->tour_meta ){
            $meta = json_decode($booking->tour_meta);
            foreach ($meta as $key => $metafield) {
                $total += $metafield->qty;
            }
        }
        return $total;
    }
    
    
    /**
     * returns string with the status from passed $code
     * 
     * @param type $code
     * @return type
     */
    public static function getTourBookingStatusFromCode($code) {
        
        switch ($code) {
            case BOOKING_PENDING:
                return __("Pending",'expedition');
                break;
            case BOOKING_PENDING_CONFIRM:
                return __("Pending Confirmation", 'expedition');
                break;
            case BOOKING_CONFIRMED:
                return __("Confirmed", 'expedition');
                break;
            case BOOKING_REJECTED:
                return __("Rejected", 'expedition');
                break;
            case BOOKING_CHECKED_IN:
                return __("Checked In", 'expedition');
                break;
            case BOOKING_CONFIRMED_CARD:
                return __("Pago con tarjeta", 'expedition');
                break;
            case BOOKING_CANCELLED:
                // before: CONFIRMED
                // Flai - after: SCHEDULED
                return __("Cancelada", 'expedition');
                break;    
            default:
                return "";
                break;
        }
    }
    
    /**
     * returns string with the status information from passed $code
     * 
     * @param type $code
     * @return type
     */
    public static function getTourBookingStatusInfoFromCode($code) {
        
        switch ($code) {
            case BOOKING_PENDING:
                return __("Represents a booking started from expeditioner, without paid info",'expedition');
                break;
            case BOOKING_PENDING_CONFIRM:
                return __("Represents a booking paid but without confirmed transaction", 'expedition');
                break;
            case BOOKING_CONFIRMED:
                return __("Represents a booking paid and transaction confirmed", 'expedition');
                break;
            case BOOKING_REJECTED:
                return __("Represents a booking with failed payment", 'expedition');
                break;
            case BOOKING_CHECKED_IN:
                return __("Represents a booking finished succesfully (scanned the QR code by sample)", 'expedition');
                break;
            case BOOKING_CONFIRMED_CARD:
                return __("Represents a booking paid with credit card", 'expedition');
                break; 
            case BOOKING_CANCELLED:
                return __("Represents a canceled booking", 'expedition');
                break;    
            default:
                return "";
                break;
        }
    }
    
    /**
     * returns string with the status from passed $code
     * 
     * @param type $code
     * @return type
     */
    public static function getTransactionStatusFromCode($code) {
        
        switch ($code) {
            case TRANSACTION_ERROR:
                return __("Error",'expedition');
                break;
            case TRANSACTION_PENDING:
                return __("Pending Confirm", 'expedition');
                break;
            case TRANSACTION_SUCCESS:
                return __("Success", 'expedition');
                break;
            default:
                return "";
                break;
        }
    }
    
    
    /**
     * returns string with the status from passed $code
     * 
     * @param type $code
     * @return type
     */
    public static function getTourStatusFromCode($code) {
        
        switch ($code) {
            case TOUR_PENDING:
                return __("Pending",'expedition');
                break;
            case TOUR_ACCEPTED:
                return __("Accepted", 'expedition');
                break;
            case TOUR_REJECTED:
                return __("Rejected", 'expedition');
                break;
            default:
                return "";
                break;
        }
    }
    
    
    
    /**
     * Returns string with the request status of passed $code
     * 
     * @param type $code
     * @return type
     */
    public static function getRequestStatusFromCode($code) {
        
        switch ($code) {
            case 0:
                return "Pending";
                break;
            case 1:
                return "Approved";
                break;
            case 2:
                return "Rejected";
                break;
            default:
                return "";
                break;
        }
    }
    
    /**
     * Fill most used fields in WP from Firebase DB
     * 
     * @param type $name
     * @return type
     */
    public static function dateFromDB($date, $format) {
        
        // date("d/m/Y", strtotime($rec->created_at) -(HOUR_IN_SECONDS*6) ).'<br>'.
        // date("H:i:s", strtotime($rec->created_at) -(HOUR_IN_SECONDS*6) )
        
        return get_date_from_gmt( $date, $format );
        //date("H:i:s", strtotime($date) - (HOUR_IN_SECONDS*6) );
        
    }
    
    
    /**
     * Fill most used fields in WP from Firebase DB
     * 
     * @param type $name
     * @return type
     */
    public static function fillWpFieldsFromFirebase($user_id) {
        //$user = get_user_by('id', $user_id);
        //
        //if ( !$user )
        //    return;
        
        $first_name = get_user_meta( $user_id, 'first_name', true ); 
        
        if ( strlen($first_name) < 1 && strlen($user_id)> 0 ){
            $fuser = FirebaseHelper::getSinglePathWithQuery("User", "objectId", (string)$user_id);
            if ( $fuser ){
                $fullname = $fuser['fullname'];
                wp_update_user( array( 'ID' => $user_id, 'first_name' => $fullname ) );
                //echo "updated $fullname $user_id from firebase <br>";
            }else{
                //echo "error updated $user_id from firebase <br>";
            }
            
        }else{
            //echo "cached $user_id - $first_name in WP <br>";
        }
        
    }
    
    
    /**
     * Get initials from fullname
     * 
     * @param type $name
     * @return type
     */
    public static function getInitials($name) {
        //split name using spaces
        $words = explode(" ", $name);
        $inits = '';
        $max = 2;
        $counter = 0;
        //loop through array extracting initial letters
        foreach ($words as $word) {
            $counter++;
            if ( $counter <= $max ){
                $inits .= strtoupper(substr($word, 0, 1));
            }
        }
        return $inits;
    }

    /**
     * Returns a FIRServerValue timestamp
     * 
     * @return long
     */
    public static function firebaseTimestamp(){
        $timeparts = explode(" ",microtime());
        $currenttime = bcadd(($timeparts[0]*1000),bcmul($timeparts[1],1000));
        return (int)$currenttime;
    }
    
    /**
     * Store the file passed $img_url and add it to WP media galley
     * 
     * @param type $img_url
     * @param type $name
     * @param type $file
     * @param type $post_id
     * @param type $author_id
     * @return type
     */
    public static function save_image_doc_from_url($img_url, $name, $file = false, $post_id = 0, $author_id = false){
        $thumb_url = $img_url;
        $author_id = $author_id ? $author_id : get_current_user_id();
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-includes/post.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        if ( $file === false ){

            $tmp = download_url( $thumb_url );

            // Set variables for storage
            // fix file filename for query strings
            $file_array = array();
            preg_match('/[^¥?]+¥.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG|pdf|PDF|doc|docx|DOC|DOCX)/', $thumb_url, $matches);
            $file_array['name'] = time().'_'. $author_id.'_123.jpg'; //basename($matches[0]);
            $file_array['tmp_name'] = $tmp;

            // If error storing temporarily, unlink
            if ( is_wp_error( $tmp ) ) {
                @unlink($file_array['tmp_name']);
                $file_array['tmp_name'] = '';
            }

        }else{
            $file_array = $file;
            $file_array['name'] = time().'_'.$file_array['name'];
        }

        // do the validation and storage stuff
        $thumb_id = media_handle_sideload( $file_array, $post_id );
        if ( $author_id && $thumb_id && !is_wp_error($thumb_id)){
            $the_attach = array();
            $the_attach['ID'] = $thumb_id;
            $the_attach['post_author'] = $author_id;
            wp_update_post( $the_attach );
        }

        // If error storing permanently, unlink
        if ( is_wp_error($thumb_id) ) {
            //@unlink($file_array['tmp_name']);
            return $thumb_id;
        }

        return $thumb_id;
        return wp_get_attachment_url($thumb_id);

    }

    
    /**
     * Returns an array with country fields, id, name, currency_code, etc...
     * 
     * @param type $id
     * @return type
     */
    public static function getCountryById($id){
        $countries = get_field('countries', 'options');
        foreach ($countries as $country) {
            if ( $country['id'] == $id ){
                return $country;
            }
        }
        return false;
    }
    
    
    /**
     * 
     * 
     * @param type $id
     * @return type
     */
    public static function getBloodTypeNameById($id){
        $blood_types = get_field('blood_types', 'options');
        foreach ($blood_types as $blood_type) {
            if ( $blood_type['id'] == $id ){
                return $blood_type['name'];
            }
        }
        return false;
    }
    
    /**
     * 
     * 
     * @param type $id
     * @return type
     */
    public static function getCountryNameById($id){
        $countries = get_field('countries', 'options');
        foreach ($countries as $country) {
            if ( $country['id'] == $id ){
                return $country['name'];
            }
        }
        return false;
    }
    
    /**
     * 
     * 
     * @param type $id
     * @return type
     */
    public static function getIDTypeNameById($id){
        $types = get_field('id_types', 'options');
        foreach ($types as $type) {
            if ( $type['id'] == $id ){
                return $type['name'];
            }
        }
        return false;
    }
    
    /**
     * 
     * @param type $source_currency
     * @param type $target_currency
     * @param type $amount
     * @return type
     */
    function currencyConverter($source_currency,$target_currency,$amount) {
	$get = file_get_contents("https://www.google.com/finance/converter?a=$amount&from=$source_currency&to=$target_currency");
	$get = explode("<span class=bld>",$get);
	$get = explode("</span>",$get[1]);  
	return preg_replace("/[^0-9¥.]/", null, $get[0]);
    }
    
    
    public static function is_numeric( $string, $request, $context ){
        
    }
    

    
    public static function count() {
        global $wpdb;
        return $wpdb->get_var("SELECT count(*) FROM $wpdb->posts WHERE post_type LIKE 'bills' AND post_status LIKE 'publish'");
    }
    
    
    /************************************************************/
    /************************************************************/
    /************************************************************/
    
    /**
     * Checks if exists the passed $user_id
     * 
     * @param type $user_id
     * @return boolean or integer of user id
     */
    function userIdExists( $user_id ) {
	if ( $user = get_user_by( 'id', $user_id ) ) {
		return $user->ID;
	}
	return false;
    }
    
    /************************************************************/
    /************************************************************/
    /************************************************************/

    /**
     * Return the contents of post url using CURL
     * 
     */
    public static function url_post_content($url, $fields) {
        if (!function_exists('curl_init')) {
            die('CURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    
    /**
     * Return the contents of a url using CURL
     * 
     */
    public static function url_get_content($url) {
        if (!function_exists('curl_init')) {
            die('CURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * Get content from passed $url and store in transient if is needed cache it
     * YOu can pass $cache to specify if you need cache. The cache is used to
     * prevent get contents every in every function call and improve performance
     * 
     * If the passed $url is down and expired the cache (transient) we return
     * a file store as second level cache
     * 
     * @param type $url
     * @param type $cache
     * @return type
     */
    public static function get_external_data($url, $cache = false) {

        $filename = md5($url);
        $file_path = TEMPLATEPATH . "/data/$filename";

        if ($cache) {

            $content = get_transient($filename);

            if ($content) {
                return $content;
            } else {

                $content = self::url_get_content($url);

                if ($content) {

                    set_transient($filename, $content, $cache);

                    $fp = fopen($file_path, 'w');
                    fwrite($fp, $content);
                    fclose($fp);

                    return $content;
                } else {
                    echo $filename;
                    return file_get_contents($file_path);
                }
            }
        } else {
            return self::url_get_content($url);
        }
    }
    
    /**
     * Return html with the passed $copy
     * 
     * @param string/html $copy
     * @return string
     */
    public static function get_html_notification($copy){
        return '<!doctype html>

                <html lang="en">
                <head><meta charset="shift_jis">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    
                    <title>Reset Password</title>
                </head>

                <body style="background: #0c3a4e; ">
                    <div style="max-width: 400px; margin : 20px auto;">
                        <div style="margin : 0 20px;font-family: Arial; color : #555; background: white;
                            text-align : center; padding-bottom : 40px; border-radius: 15px;">
                                <div style="background: #008AC6; color: rgba(255,255,255,0.8); text-transform: uppercase; text-align: center; padding: 0px 20px 10px; border-top: solid 15px #125473; margin-bottom: 40px;">
                                    <h1 style="margin-bottom: 0;"><a href="#">
                                    <img style="max-width: 90px; margin: 10px auto 10px; display: block;" src="https://app.expeditionguate.com/wp-content/uploads/2019/09/expedition_icon.png" alt="Expedition" />
                                    <img style="max-width: 190px; margin: 0 auto; display: block;" src="https://app.expeditionguate.com/wp-content/uploads/2019/09/expedition_logo_white@2x.png" alt="Expedition" />
                                    </a></h1>
                                </div>
                               '.$copy.'
                        </div>
                    </div>
                </body>
                </html>';
    }
    
    /**
     * Returns an object with the email and id of the user to notify, 
     * like $response->email and $response->id
     * 
     * @param int $user_id
     * @return array
     */
    public static function get_notification_email_and_id($user_id){
        $user = get_user_by('id', $user_id );
        
        $response = new stdClass();

        // If the current user is a side user, we have to notify to the main user,
        // this applies when a parent adds to their children, then we will notify
        // to the parent, not the children
        $main_user = Expedition_Helper::getAssociatedMainPatientRow($user_id);


        //&& !$user->data->user_email
        if ( $main_user && !$user->data->user_email ){
            $response->id = $main_user->main_user_id;
            $user = get_user_by('id', $response->id );
            $response->email = $user->data->user_email;
        }else{
            $response->id = $user_id;
            $response->email = $user->data->user_email;
        }
        
        return $response;
    }
    
    
    /**
     * Returns an string with the code (es|en) of the language of the passed
     * $user_id
     * 
     * @param int $user_id
     * @return string es|en
     */
    public static function get_notification_language($user_id){
        
        $user_language = get_user_meta($user_id, 'user_language', true);
        $valid_languages = array('en', 'es');
        if (!in_array($user_language, $valid_languages) ){
            $user_language = $valid_languages[0];
        }
        
        return $user_language;
    }
    
    
    
    /**
     * Returns the nit info in wp_remote_post response returned by COFIDI
     * 
     * @param string $nit
     * @return array
     */
    public static function get_nit_data($nit){
        $nit = str_replace(array('-', '_', ' '), '', $nit);
        $params = array(
                    "vNIT"=> $nit, 
                    "usuario"=> COFIDI_NIT_USER, 
                    "password"=> COFIDI_NIT_PASSWORD,
            );
        $url = COFIDI_NIT_URL;
        
        $response = wp_remote_post($url, array(
                'method' => 'POST',
                'body' => $params,
                'timeout' => 45,
            )
        );
        
        return $response;
    }
    
    /**
     * Check if rel_type exists in the catalogs
     * 
     * @param type $rel_type
     * @return boolean
     */
    public static function rel_type_exists($rel_type){
        
        $relationships = get_field('relationships', 'options');
        
        if (is_array($relationships) && count($relationships) ){
            foreach ($relationships as $rel) {
                if ( $rel['id'] == $rel_type ){
                    return true;
                }
            }
        }
        
        return false;
    }
    
    
    /**
     * Returns the client IP address
     * 
     * @return string
     */
    public static function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    /**
     * An instance of Curl emulating the funcionality of file_get_contetns
     *
     * @param type $url
     * @return type
     *
     */
    function file_get_contents_curl($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
    
    /**
    * Get real client IP
    * 
    * @return string
    */
   public static function getClientIp() {
       $ipaddress = '';
       if (getenv('HTTP_CLIENT_IP'))
           $ipaddress = getenv('HTTP_CLIENT_IP');
       else if(getenv('HTTP_X_FORWARDED_FOR'))
           $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
       else if(getenv('HTTP_X_FORWARDED'))
           $ipaddress = getenv('HTTP_X_FORWARDED');
       else if(getenv('HTTP_FORWARDED_FOR'))
           $ipaddress = getenv('HTTP_FORWARDED_FOR');
       else if(getenv('HTTP_FORWARDED'))
          $ipaddress = getenv('HTTP_FORWARDED');
       else if(getenv('REMOTE_ADDR'))
           $ipaddress = getenv('REMOTE_ADDR');
       else
           $ipaddress = 'UNKNOWN';
       return $ipaddress;
   }
    
    /**
    * Store the log for current request.
    * We include IP, request url base, received params and the passed $response
    * 
    * @global type $url_params
    * @global type $fields_raw
    * @param String $response the response for current request
    */
   public static function logMessage( $message, $file_name = false ){
       $client_ip = Expedition_Helper::getClientIp();
       
       $messageToLog = PHP_EOL."Remote IP : $client_ip ".PHP_EOL."".$message.PHP_EOL;
       
       self::logEvent($messageToLog, $file_name);

   }

   /**
    * Store in a file the requests logs.
    * 
    * @param string $message the message to log.
    */
   public static function logEvent($message, $file_name = false) {
       
       if ($message != '') {
           // Add a timestamp to the start of the $message
           $message = date("Y/m/d H:i:s").''.$message;
           //$fp = fopen('/path/to/log.txt', 'a');
           
           $fp = fopen(  WP_CONTENT_DIR."/uploads/logs/".($file_name ? $file_name : 'expedition_log.txt'), 'a');
           fwrite($fp, PHP_EOL.$message. PHP_EOL."-----------------------------".PHP_EOL);
           fclose($fp);
       }
   }
   
   
   public static function getCCType($cardnum) {

        /* Visa */
        if (preg_match("/^4(¥d{12}|¥d{15})$/", $cardnum)) {
            $type = '001';

            /* MasterCard */
        } else if (preg_match("/^5[1-5]¥d{14}$/", $cardnum)) {
            $type = '002';

            /* American Express */
        } else if (preg_match("/^3[47]¥d{13}$/", $cardnum)) {
            $type = '003';

            /* Discover */
        } else if (preg_match("/^6011¥d{12}$/", $cardnum)) {
            $type = '004'; // Discover

            /* Diners Club */
        } else if (preg_match("/^[300-305]¥d{11}$/", $cardnum) ||
                preg_match("/^3[68]¥d{12}$/", $cardnum)) {
            $type = '005';

            /* EnRoute */
        } else if (preg_match("/^2(014|149)¥d{11}$/", $cardnum)) {
            $type = '014';

            /* JCB */
        } else if (preg_match("/^3¥d{15}$/", $cardnum) ||
                preg_match("/^(2131|1800)¥d{11}$/", $cardnum)) {
            $type = '007';

            /* Maestro */
        } else if (preg_match("/^(?:5020|6¥¥d{3})¥¥d{12}$/", $cardnum)) {
            $type = '024';

            /* Visa Electron */
        } else if (preg_match("/^4(17500|917[0-9][0-9]|913[0-9][0-9]|508[0-9][0-9]|844[0-9][0-9])¥d{10}$/", $cardnum)) {
            $type = '033';

            /* Laser */
        } else if (preg_match("/^(6304|670[69]|6771)[0-9]{12,15}$/", $cardnum)) {
            $type = '035';

            /* Carte Blanche */
        } else if (preg_match("/^389[0-9]{11}$/", $cardnum)) {
            $type = '006';

            /* Dankort */
        } else if (preg_match("/^5019¥d{12}$/", $cardnum)) {
            $type = '034';
        } else {
            $type = '001';
        }


        return $type;
    }
    
    public static function reasonCode( $code ){
        if (!$code){
            return 'Unknown Error!';
        }

        $reason_codes = array();
        $reason_codes['100'] = __( "Successful transaction.", "woocommerce" );
        $reason_codes['101'] = __( "The request is missing one or more required fields. ", "woocommerce" );
        $reason_codes['102'] = __( "One or more fields in the request contains invalid data.", "woocommerce" );
        $reason_codes['104'] = __( "The merchant reference code for this authorization request matches the merchant reference code of another authorization request that you sent within the past 15 minutes.", "woocommerce" );
        $reason_codes['110'] = __( "Only a partial amount was approved.", "woocommerce" );
        $reason_codes['150'] = __( "General system failure. Recurring Billing or Secure Storage service is not enabled for the merchant please contact the support", "woocommerce" );
        $reason_codes['151'] = __( "The request was received but there was a server timeout. This error does not include timeouts between the client and the server.", "woocommerce" );
        $reason_codes['152'] = __( "The request was received, but a service did not finish running in time. ", "woocommerce" );
        $reason_codes['200'] = __( "The authorization request was approved by the issuing bank but declined by CyberSource because it did not pass the Address Verification System (AVS) check.", "woocommerce" );
        $reason_codes['201'] = __( "The issuing bank has questions about the request. You do not receive an authorization code programmatically, but you might receive one verbally by calling the processor.", "woocommerce" );
        $reason_codes['202'] = __( "Expired card. You might also receive this value if the expiration date you provided does not match the date the issuing bank has on file.", "woocommerce" );
        $reason_codes['203'] = __( "General decline of the card. No other information was provided by the issuing bank.", "woocommerce" );
        $reason_codes['204'] = __( "Insufficient funds in the account.", "woocommerce" );
        $reason_codes['205'] = __( "Stolen or lost card.", "woocommerce" );
        $reason_codes['207'] = __( "Issuing bank unavailable.", "woocommerce" );
        $reason_codes['208'] = __( "Inactive card or card not authorized for card-not-present transactions.", "woocommerce" );
        $reason_codes['209'] = __( "CVN did not match.", "woocommerce" );
        $reason_codes['210'] = __( "The card has reached the credit limit. ", "woocommerce" );
        $reason_codes['211'] = __( "Invalid CVN.", "woocommerce" );
        $reason_codes['221'] = __( "The customer matched an entry on the processorﾃ不 negative file. ", "woocommerce" );
        $reason_codes['230'] = __( "The authorization request was approved by the issuing bank but declined by CyberSorce because it did not pass the CVN check.", "woocommerce" );
        $reason_codes['231'] = __( "Invalid account number.", "woocommerce" );
        $reason_codes['232'] = __( "The card type is not accepted by the payment processor.", "woocommerce" );
        $reason_codes['233'] = __( "General decline by the processor.", "woocommerce" );
        $reason_codes['234'] = __( "There is a problem with the information in your CyberSource account.", "woocommerce" );
        $reason_codes['235'] = __( "The requested capture amount exceeds the originally authorized amount. ", "woocommerce" );
        $reason_codes['236'] = __( "Processor failure. ", "woocommerce" );
        $reason_codes['237'] = __( "The authorization has already been reversed.", "woocommerce" );
        $reason_codes['238'] = __( "The authorization has already been captured.", "woocommerce" );
        $reason_codes['239'] = __( "The requested transaction amount must match the previous transaction amount. ", "woocommerce" );
        $reason_codes['240'] = __( "The card type sent is invalid or does not correlate with the credit card number.", "woocommerce" );
        $reason_codes['241'] = __( "The request ID is invalid.", "woocommerce" );
        $reason_codes['242'] = __( "You requested a capture, but there is no corresponding, unused authorization record. Occurs if there was not a previously successful authorization request or if the previously successful authorization has already been used by another capture request.", "woocommerce" );
        $reason_codes['243'] = __( "The transaction has already been settled or reversed.", "woocommerce" );
        $reason_codes['246'] = __( "The capture or credit is not voidable because the capture or credit information has already been submitted to your processor. or You requested a void for a type of transaction that cannot be voided. Possible action: No action required.", "woocommerce" );
        $reason_codes['247'] = __( "You requested a credit for a capture that was previously voided.", "woocommerce" );
        $reason_codes['250'] = __( "The request was received, but there was a timeout at the payment processor.", "woocommerce" );
        $reason_codes['254'] = __( "Stand-alone credits are not allowed.", "woocommerce" );
        $reason_codes['490'] = __( "Your aggregator or acquirer is not accepting transactions from you at this time..", "woocommerce" );
        $reason_codes['491'] = __( "Your aggregator or acquirer is not accepting this transaction.", "woocommerce" );

        if( !empty( $code ) && isset($reason_codes[$code]) ){
                $result = $reason_codes[$code];
        }else{
                $result = 'Unknown Error!';
        }

        return $result;

}


}

/**
 * Is just an instance of var_dump() but adding <pre></pre> tag
 */
function var_dump_pre() {
    echo '<pre>';
    $args = func_get_args();
    foreach ($args as $arg) {
        var_dump($arg);
    }
    echo '</pre>';
}

/**
* Sort gallery by date
* 
* @param type $a
* @param type $b
* @return type
*/
function galleryDateCompare($a, $b){
    if ( !isset($a['date']) || !isset($b['date']) )
        return 0;
    
    $t1 = strtotime($a['date']);
    $t2 = strtotime($b['date']);
    return $t1 - $t2;
}


/**
*@author  Xu Ding
*@email   thedilab@gmail.com
*@website http://www.StarTutorial.com
**/
class Calendar {  
     
    /**
     * Constructor
     */
    public function __construct(){     
        $this->naviHref = htmlentities($_SERVER['PHP_SELF']);
    }
     
    /********************* PROPERTY ********************/  
    private $dayLabels = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
     
    private $currentYear=0;
     
    private $currentMonth=0;
     
    private $currentDay=0;
     
    private $currentDate=null;
     
    private $daysInMonth=0;
     
    private $naviHref= null;
     
    /********************* PUBLIC **********************/  
        
    /**
    * print out the calendar
    */
    public function show($year = null, $month = null, $dates = array() ) {
         
        if(null==$year&&isset($_GET['year'])){
 
            $year = $_GET['year'];
         
        }else if(null==$year){
 
            $year = date("Y",time());  
         
        }          
         
        if(null==$month&&isset($_GET['month'])){
 
            $month = $_GET['month'];
         
        }else if(null==$month){
 
            $month = date("m",time());
         
        }                  
         
        $this->currentYear=$year;
         
        $this->currentMonth=$month;
         
        $this->daysInMonth=$this->_daysInMonth($month,$year);  
         
        $content='<div id="calendar">'.
                        '<div class="box">'.
                        $this->_createNavi().
                        '</div>'.
                        '<div class="box-content">'.
                                '<ul class="label">'.$this->_createLabels().'</ul>';   
                                $content.='<div class="clear"></div>';     
                                $content.='<ul class="dates">';    
                                 
                                $weeksInMonth = $this->_weeksInMonth($month,$year);
                                // Create weeks in a month
                                for( $i=0; $i<$weeksInMonth; $i++ ){
                                     
                                    //Create days in a week
                                    for($j=1;$j<=7;$j++){
                                        $content.=$this->_showDay($i*7+$j, $dates);
                                    }
                                }
                                 
                                $content.='</ul>';
                                 
                                $content.='<div class="clear"></div>';     
             
                        $content.='</div>';
                 
        $content.='</div>';
        return $content;   
    }
     
    /********************* PRIVATE **********************/ 
    /**
    * create the li element for ul
    */
    private function _showDay($cellNumber, $dates){
         
        if($this->currentDay==0){
             
            $firstDayOfTheWeek = date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));
                     
            if(intval($cellNumber) == intval($firstDayOfTheWeek)){
                 
                $this->currentDay=1;
                 
            }
        }
         
        if( ($this->currentDay!=0)&&($this->currentDay<=$this->daysInMonth) ){
             
            $this->currentDate = date('Y-m-d',strtotime($this->currentYear.'-'.$this->currentMonth.'-'.($this->currentDay)));
             
            $cellContent = $this->currentDay;
             
            $this->currentDay++;   
             
        }else{
             
            $this->currentDate =null;
 
            $cellContent=null;
        }
        
        if ( isset( $dates[$this->currentDate] ) ){
            $cellContent = "<a href=\"{$dates[$this->currentDate]}\">$cellContent</a>";
        }
             
         
        return '<li id="li-'.$this->currentDate.'" class="'.($cellNumber%7==1?' start ':($cellNumber%7==0?' end ':' ')).
                ($cellContent==null?'mask':'').'">'.$cellContent.'</li>';
    }
     
    /**
    * create navigation
    */
    private function _createNavi(){
         
        $nextMonth = $this->currentMonth==12?1:intval($this->currentMonth)+1;
         
        $nextYear = $this->currentMonth==12?intval($this->currentYear)+1:$this->currentYear;
         
        $preMonth = $this->currentMonth==1?12:intval($this->currentMonth)-1;
         
        $preYear = $this->currentMonth==1?intval($this->currentYear)-1:$this->currentYear;
         
        return
            '<div class="header">'.
                '<a class="prev" href="'.$this->naviHref.'?month='.sprintf('%02d',$preMonth).'&year='.$preYear.'">Prev</a>'.
                    '<span class="title">'.date('F Y',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')).'</span>'.
                '<a class="next" href="'.$this->naviHref.'?month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'">Next</a>'.
            '</div>';
    }
         
    /**
    * create calendar week labels
    */
    private function _createLabels(){  
                 
        $content='';
         
        foreach($this->dayLabels as $index=>$label){
             
            $content.='<li class="'.($label==6?'end title':'start title').' title">'.$label.'</li>';
 
        }
         
        return $content;
    }
     
     
     
    /**
    * calculate number of weeks in a particular month
    */
    private function _weeksInMonth($month=null,$year=null){
         
        if( null==($year) ) {
            $year =  date("Y",time()); 
        }
         
        if(null==($month)) {
            $month = date("m",time());
        }
         
        // find number of days in this month
        $daysInMonths = $this->_daysInMonth($month,$year);
         
        $numOfweeks = ($daysInMonths%7==0?0:1) + intval($daysInMonths/7);
         
        $monthEndingDay= date('N',strtotime($year.'-'.$month.'-'.$daysInMonths));
         
        $monthStartDay = date('N',strtotime($year.'-'.$month.'-01'));
         
        if($monthEndingDay<$monthStartDay){
             
            $numOfweeks++;
         
        }
         
        return $numOfweeks;
    }
 
    /**
    * calculate number of days in a particular month
    */
    private function _daysInMonth($month=null,$year=null){
         
        if(null==($year))
            $year =  date("Y",time()); 
 
        if(null==($month))
            $month = date("m",time());
             
        return date('t',strtotime($year.'-'.$month.'-01'));
    }
     
}