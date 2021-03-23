<?php

function on_expedition_liked_post( $travel_note_id, $user_id ){
    
    $post = get_post($travel_note_id);
    $_user = get_userdata($user_id);
    $_fullname = $_user->first_name . ' '. $_user->last_name ;
    
    $one_signal_id = get_field( 'one_signal_id', 'user_'.$post->post_author);
    if ( $one_signal_id ){
        $response = Notifications::sendPushToUser($one_signal_id, "$_fullname liked {$post->post_title}", "New like",
                array(
                    'notification'=> 'liked_post',
                    'user_id'=> $user_id
                    )
                );
        $contn = json_encode( array($one_signal_id, "$_fullname liked {$post->post_title}", 
                array(
                    'response'=>$response,
                    'notification'=> 'liked_post',
                    'user_id'=> $user_id
                    )) );
        // Notifications::send('estuardoeg@gmail.com', "APPLY $one_signal_id", "$contn $travel_note_id, $user_id ");
    }else{
        // Notifications::send('estuardoeg@gmail.com', "NOT APPLY $one_signal_id", "$travel_note_id, $user_id ");
    }
}
add_action('expedition_liked_post', 'on_expedition_liked_post', 10, 2);