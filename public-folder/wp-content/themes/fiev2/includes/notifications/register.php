<?php

/**
 * Is fired when an user is created... 
 * 
 * We send the welcome email
 * 
 * @param int $user_id User ID
 */
function on_expedition_created_user( $user_id, $email = false, $password = false ){
    
    $user = get_user_by('id', $user_id );
    
//    $status = get_user_meta($user_id, 'status', true);
    $user_language = 'en';//get_user_meta($user_id, 'user_language', true);
    // $activation_key = get_user_meta($user_id, 'activation_key', true );
    $valid_languages = array('en', 'es');
    if (!in_array($user_language, $valid_languages) ){
        $user_language = $valid_languages[0];
    }
    
    $field = "register_email_content_$user_language";
    $template = get_field($field, 'options');
    
    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name = get_user_meta($user_id, 'last_name', true);
    $user_full_name = "$first_name $last_name";
    $user_email = $email && is_email($email) ? $email : $user->data->user_email;
    
    
    
    
    /****** ACTIVATION LINK *********/
    $hash = md5(uniqid());
    update_user_meta($user_id, '_activation_hash', $hash);
    $activation_link = get_bloginfo('home')."/api/v1/user/activate?hash=$hash";
    /****** ACTIVATION LINK *********/
    
    
    
    
    //$url = get_bloginfo('home')."/api/v1/users/activate?key=$activation_key&user_id=$user_id";
    $message = str_replace(
            array('{user_firstname}', '{user_last_name}', '{user_full_name}', '{user_email}', '{activation_link}'), 
            array($first_name, $last_name, $user_full_name, $user_email, $activation_link), 
            $template);
            
    if ( $password ){
        // $message .= "<p>Your password is <strong>$password</strong>, use it when your login in ExpeditionApp</p>";
    }
    
    $subject = get_field("register_email_subject_$user_language", 'options');
    
    
    //$enable_field = "enable_register_{$role}_notification";
    //$enable = get_field($enable_field, 'options');
    $enable = true;
    
    if ( !$enable ){
        Expedition_Helper::logMessage( "Register email disabled, not sent to $user_email", 'emails_sent.txt');
    }else{
        $sent = Notifications::send( $user_email, $subject, $message );
        Expedition_Helper::logMessage( "Register email ".($sent ? 'sent' : 'failed')." to $user_email", 'emails_sent.txt');
    }
}

add_action('expedition_created_user', 'on_expedition_created_user');

/**
 * Alias of on_expedition_created_user
 * 
 * @param type $user_id
 */
function expedition_send_activation_email( $user_id, $email ){
    on_expedition_created_user($user_id, $email );
}

/******************************************************************************/
