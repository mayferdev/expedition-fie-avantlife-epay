<?php

/**
 * Is fired when a user follow other expeditioner
 * 
 * @global type $firebase
 * @param type $follower_id
 * @param type $following_id
 */
function on_expedition_following_user( $follower_id, $following_id ){
    
    $_user = get_userdata($follower_id);
    // $user = get_userdata($invitation->user_id);
    // $fullname = $user->first_name . ' '. $user->last_name ;
    $_fullname = $_user->first_name . ' '. $_user->last_name ;
    
    $one_signal_id = get_field( 'one_signal_id', 'user_'.$following_id);
    if ( $one_signal_id ){
        Notifications::sendPushToUser($one_signal_id, "$_fullname started following you", "New Follower",
                array(
                    'notification'=> 'new_follower',
                    'follower_id'=> $following_id
                    ) 
                );
    }
}
add_action('expedition_following_user', 'on_expedition_following_user', 10, 2);