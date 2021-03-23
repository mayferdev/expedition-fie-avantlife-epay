<?php

/**
 * Is fired when a doctor rejects to be doctor of a patient
 * 
 * We send the email to inform it
 * 
 * @param int $doctor_id Doctor ID
 * @param int $patient_id Patient ID
 */
function on_expedition_sent_request( $doctor_id, $patient_id ){
    
    // site_url().'/api/v1/scheme/?scheme=expedition://doctors/specialties'
    $fuser_doctor = FirebaseHelper::getSinglePathWithQuery("User", "objectId", (string)$doctor_id);
    $doctor_name = (string)@$fuser_doctor['fullname'];
    $thumbnail = (string)@$fuser_doctor['thumbnail'];
    $doctor_email = (string)@$fuser_doctor['email'];
    
    $fuser_patient = FirebaseHelper::getSinglePathWithQuery("User", "objectId", (string)$patient_id);
    $patient_fullname = (string)@$fuser_patient['fullname'];
    $patient_thumbnail = (string)@$fuser_patient['thumbnail'];
    
    $notify_user = Expedition_Helper::get_notification_email_and_id($patient_id);
    $user_language = Expedition_Helper::get_notification_language($notify_user->id);
    
    $subject = get_field("sent_request_email_subject_$user_language", 'options');
    $message = get_field("sent_request_email_$user_language", 'options');
    
    $subject = str_replace(array('{doctor_name}', '{doctor_code}', '{doctor_thumbnail}', '{patient_name}', '{patient_thumbnail}'), array($doctor_name, $doctor_id, $thumbnail, $patient_fullname, $patient_thumbnail), $subject);
    $message = str_replace(array('{doctor_name}', '{doctor_code}', '{doctor_thumbnail}', '{patient_name}', '{patient_thumbnail}'), array($doctor_name, $doctor_id, $thumbnail, $patient_fullname, $patient_thumbnail), $message);
    
    $enable_field = "enable_sent_request_notification";
    $enable = get_field($enable_field, 'options');
    if ( !$enable ){
        Expedition_Helper::logMessage( "Sent request email disabled, not sent to {$notify_user->email}", 'emails_sent.txt');
    }else{
        $sent = Notifications::send($doctor_email, $subject, $message);
        Expedition_Helper::logMessage( "Sent request email ".($sent ? 'sent' : 'failed')." to {$notify_user->email}", 'emails_sent.txt');
    }
    
    /**************************************************************************/
    
    $oneSignalId = "";
    if ( @$fuser_doctor['oneSignalId'] ){
        $oneSignalId = (string) @$fuser_doctor['oneSignalId'];
    }

    $result = Notifications::sendPushToUser(
                $oneSignalId, 
                array(
                    "en" => $patient_fullname.' has added you as their doctor',
                    "es" => $patient_fullname.' te ha agregado como doctor',
                ),
                array(
                    "en" => 'Pending requests',
                    "es" => 'Solicitudes pendientes',
                ),
                array('force_alert'=>true, 'notification'=> 'doctor_added'));
    
}

add_action('expedition_sent_request', 'on_expedition_sent_request', 10, 2);


/**
 * Is fired when a doctor rejects to be doctor of a patient
 * 
 * We send the email to inform it
 * 
 * @param int $doctor_id Doctor ID
 * @param int $patient_id Patient ID
 */
function on_expedition_declined_request( $doctor_id, $patient_id ){
    
    // site_url().'/api/v1/scheme/?scheme=expedition://doctors/specialties'
    $fuser_doctor = FirebaseHelper::getSinglePathWithQuery("User", "objectId", (string)$doctor_id);
    $doctor_name = (string)@$fuser_doctor['fullname'];
    $thumbnail = (string)@$fuser_doctor['thumbnail'];
    
    $fuser_patient = FirebaseHelper::getSinglePathWithQuery("User", "objectId", (string)$patient_id);
    $patient_fullname = (string)@$fuser_patient['fullname'];
    $patient_thumbnail = (string)@$fuser_patient['thumbnail'];
    
    $notify_user = Expedition_Helper::get_notification_email_and_id($patient_id);
    $user_language = Expedition_Helper::get_notification_language($notify_user->id);

    $subject = get_field("decline_request_email_subject_$user_language", 'options');
    $message = get_field("decline_request_email_$user_language", 'options');
    
    $subject = str_replace(array('{doctor_name}', '{doctor_code}', '{doctor_thumbnail}', '{patient_name}', '{patient_thumbnail}'), array($doctor_name, $doctor_id, $thumbnail, $patient_fullname, $patient_thumbnail), $subject);
    $message = str_replace(array('{doctor_name}', '{doctor_code}', '{doctor_thumbnail}', '{patient_name}', '{patient_thumbnail}'), array($doctor_name, $doctor_id, $thumbnail, $patient_fullname, $patient_thumbnail), $message);
    
    $enable_field = "enable_decline_request_notification";
    $enable = get_field($enable_field, 'options');
    if ( !$enable ){
        Expedition_Helper::logMessage( "Decline request email disabled, not sent to {$notify_user->email}", 'emails_sent.txt');
    }else{
        $sent = Notifications::send($notify_user->email, $subject, $message);
        Expedition_Helper::logMessage( "Decline request email ".($sent ? 'sent' : 'failed')." to {$notify_user->email}", 'emails_sent.txt');
    }
    
    /**************************************************************************/
    
    $oneSignalId = "";
    if ( @$fuser_patient['oneSignalId'] ){
        $oneSignalId = (string) @$fuser_patient['oneSignalId'];
    }

    $result = Notifications::sendPushToUser(
                $oneSignalId, 
                array(
                    "en" => $doctor_name.' did not accepted your request',
                    "es" => $doctor_name. ' no aceptó tu solicitud',
                ),
                array(
                    "en" => 'Pending requests',
                    "es" => 'Solicitudes pendientes',
                ),
                array('force_alert'=>true));
    
}

add_action('expedition_declined_request', 'on_expedition_declined_request', 10, 2);

/******************************************************************************/

/**
 * Is fired when a doctor rejects to be doctor of a patient
 * 
 * We send the email to inform it
 * 
 * @param int $doctor_id Doctor ID
 * @param int $patient_id Patient ID
 */
function on_expedition_accepted_request( $doctor_id, $patient_id ){
    
    // site_url().'/api/v1/scheme/?scheme=expedition://doctors/specialties'
    $fuser_doctor = FirebaseHelper::getSinglePathWithQuery("User", "objectId", (string)$doctor_id);
    $doctor_name = (string)@$fuser_doctor['fullname'];
    $thumbnail = (string)@$fuser_doctor['thumbnail'];
    
    $fuser_patient = FirebaseHelper::getSinglePathWithQuery("User", "objectId", (string)$patient_id);
    $patient_fullname = (string)@$fuser_patient['fullname'];
    $patient_thumbnail = (string)@$fuser_patient['thumbnail'];
    
    $notify_user = Expedition_Helper::get_notification_email_and_id($patient_id);
    $user_language = Expedition_Helper::get_notification_language($notify_user->id);

    $subject = get_field("accept_request_email_subject_$user_language", 'options');
    $message = get_field("accept_request_email_$user_language", 'options');
    
    $subject = str_replace(array('{doctor_name}', '{doctor_code}', '{doctor_thumbnail}', '{patient_name}', '{patient_thumbnail}'), array($doctor_name, $doctor_id, $thumbnail, $patient_fullname, $patient_thumbnail), $subject);
    $message = str_replace(array('{doctor_name}', '{doctor_code}', '{doctor_thumbnail}', '{patient_name}', '{patient_thumbnail}'), array($doctor_name, $doctor_id, $thumbnail, $patient_fullname, $patient_thumbnail), $message);
    
    //var_dump( array($doctor_name, $doctor_id, $thumbnail, $patient_fullname, $patient_thumbnail, $patient_id) );
    
    
    $enable_field = "enable_accept_request_notification";
    $enable = get_field($enable_field, 'options');
    if ( !$enable ){
        Expedition_Helper::logMessage( "Accept request email disabled, not sent to {$notify_user->email}", 'emails_sent.txt');
    }else{
        $sent = Notifications::send($notify_user->email, $subject, $message);
        Expedition_Helper::logMessage( "Accept request email ".($sent ? 'sent' : 'failed')." to {$notify_user->email}", 'emails_sent.txt');
    }
    
    
    /**************************************************************************/
    
    $oneSignalId = "";
    if ( @$fuser_patient['oneSignalId'] ){
        $oneSignalId = (string) @$fuser_patient['oneSignalId'];
    }

    $result = Notifications::sendPushToUser(
            $oneSignalId, 
            array(
                "en" => $doctor_name.' has accepted your request',
                "es" => $doctor_name. ' ha aceptado tu solicitud',
            ),
            array(
                "en" => 'Pending requests',
                "es" => 'Solicitudes pendientes',
            ),
            array('force_alert'=>true, 'notification'=>'accepted_doctor_request'));

}

add_action('expedition_accepted_request', 'on_expedition_accepted_request', 10, 2);
