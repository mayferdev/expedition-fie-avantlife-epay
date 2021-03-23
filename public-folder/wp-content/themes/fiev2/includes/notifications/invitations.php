<?php

/**
 * Is fired when a user is invited to a tour
 * 
 * @param type $invitation_id the ID of the invitation
 * @param type $invitation the invitation object
 */
function on_expedition_user_invited_to_tour( $invitation_id, $invitation ){
    global $firebase;
    //$booking_id
    
    $owner_user = get_userdata($invitation->owner_id);
    // $user = get_userdata($invitation->user_id);
    // $fullname = $user->first_name . ' '. $user->last_name ;
    $owner_fullname = $owner_user->first_name . ' '. $owner_user->last_name ;
    $tour = get_post($invitation->tour_id);
    
    $one_signal_id = get_field( 'one_signal_id', 'user_'.$invitation->user_id);
    if ( $one_signal_id ){
        Notifications::sendPushToUser($one_signal_id, "$owner_fullname has invited you to ".$tour->post_title, "Tour invitation", 
                array(
                    'notification'=> 'tour_invited',
                    'tour'=> $invitation->tour_id
                    ) 
                );
    }
}
add_action('expedition_user_invited_to_tour', 'on_expedition_user_invited_to_tour', 10, 2);





/**
 * Is fired when a user is invited to a tour
 * 
 * @param type $invitation_id the ID of the invitation
 * @param type $invitation the invitation object
 */
function on_expedition_user_confirmed_invite_to_tour( $invitation_id, $invitation ){
    
    //$booking_id
    
    // $owner_user = get_userdata($invitation->owner_id);
    $user = get_userdata($invitation->user_id);
    $fullname = $user->first_name . ' '. $user->last_name ;
    // $owner_fullname = $owner_user->first_name . ' '. $owner_user->last_name ;
    $tour = get_post($invitation->tour_id);
    
    $one_signal_id = get_field( 'one_signal_id', 'user_'.$invitation->user_id);
    if ( $one_signal_id ){
        Notifications::sendPushToUser($one_signal_id, "$fullname has confirmed your invite to ".$tour->post_title, "Tour invitation", 
                array(
                    'notification'=> 'tour_invited',
                    'tour'=> $invitation->tour_id
                    ) 
                );
    }
    
}
add_action('expedition_user_confirmed_invite_to_tour', 'on_expedition_user_confirmed_invite_to_tour', 10, 2);