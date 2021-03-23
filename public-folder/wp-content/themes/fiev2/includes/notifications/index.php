<?php

// Function to change email address
 
function expedition_sender_email( $original_email_address ) {
    return 'hola@mayfer.dev';
}
 
// Function to change sender name
function expedition_sender_name( $original_email_from ) {
    return 'AvantLife';
}
 
// Hooking up our functions to WordPress filters 
add_filter( 'wp_mail_from', 'expedition_sender_email' );
add_filter( 'wp_mail_from_name', 'expedition_sender_name' );

class Notifications {
    
    /**
     * Sends push notification to a specific user between One Signal service
     * 
     * @param type $player_id the user ID n one signal
     * @param type $message the message to send
     * @param type $data an array with data to send to the user
     * @return Json with the curl response
     */
    public static function sendPushToUser( $player_id, $message, $title = false, $data = false, $contents_available = false ){

        $one_signal_app_id = '6c71fb29-a763-4e7b-a65c-092fbfef303d';//esc_attr( get_field('one_signal_application_id', 'options') );
        $one_signal_rest_key = 'ZDI1MjNmMTItOWU0NC00ZTAxLWFlYTctN2Y1MjY5NDFjNzk3';//esc_attr( get_field('one_signal_rest_api_key', 'options') );
        
        if (is_array($message) ){
            $content = $message;
        }else{
            $content = array(
                "en" => $message,
                //"es" => $message,
            );
        }
        
        
        
        $fields = array(
            'app_id' => $one_signal_app_id,
            
            // remove after testing
            'include_player_ids' => array($player_id),
            //'included_segments' => array('All'),
            'small_icon' => 'expedition',
            'large_icon' => 'ic_stat_onesignal_default',
            //'data' => array("url" => ""),
            'contents' => $content
        );

        if ( $data ){
            $fields['data'] = $data;
        }
        if ( $title ){
            if (is_array($title) ){
                $fields['headings'] = $title;
            }else{
                $fields['headings'] = array(
                    "en" => $title,
                    //"es" => $message,
                );
            }
        }
        
        if ( $contents_available ){
            $fields['content_available'] = true;
        }
        
        $fields_json = json_encode($fields);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            "Authorization: Basic $one_signal_rest_key"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_json);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
    

    /**
     * Send an email with the passed data ($to, $subject, $message)
     * 
     * @param string $to the email address to send mail... If you pass false, or null
     * wil be sent to the site administrator
     * 
     * @param string $subject the email subject
     * @param string $message the message, you can include html
     * 
     * @return boolean depending the result of the mail delivery
     */
    public static function send($to = false, $subject, $message, $attachment = array() ) {
        $admin_email = get_option('admin_email');

        if (!$to) {
            $to = $admin_email;
        }
        
        $header = '';
        //$header = "From: $admin_email\r\n"; 
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: text/html; charset=utf-8\r\n";
        $header .= "X-Priority: 1\r\n";

        $message = self::header() . $message . self::footer();

        return wp_mail($to, $subject, $message, $header, $attachment);
    }

    /**
     * Return an string with the html of the header of the email
     * 
     * @return string
     */
    public static function header() {
        ob_start();
        ?>
        <div style="width: 100%; padding : 20px 0; margin :0;background: #dddddd">
            <div style="max-width: 600px; margin : 0 auto; background:white;">
                <?php the_field('email_header', 'options') ?>
                <div style="margin:30px; text-align : center;">
            
        <?php
        $content = ob_get_contents();
        ob_clean();

        return $content;
    }

    /**
     * Return an string with the html of the footer of the email
     * 
     * @return string
     */
    public static function footer() {
        ob_start();
        ?>
                    <?php the_field('email_signature', 'options') ?>
                </div>
                <?php the_field('email_footer', 'options') ?>
            </div>
        </div>
        <?php
        $content = ob_get_contents();
        ob_clean();

        return $content;
    }

}









/**
 * Is fired when an user posted an item... 
 * 
 * We send the welcome email
 * 
 * @param int $post_id Post ID
 */
/*
function on_expedition_created_content( $post_id ){
    
    $post = get_post( $post_id );
    
    if ( !isset($post->post_author) ){
        return false;
    }
    
    $user_id = $post->post_author;
    $post_type = substr($post->post_type, 0, -1);
    
    $user = get_user_by('id', $user_id );
    $avatar = as_get_avatar( $user_id );
    $first_name = get_user_meta($user_id, 'first_name', true); // update first name
    
    $message .= "<h1>Gracias por compartir tu $post_type</h1>";
    $message .= "<img src='$avatar'style='border-radius : 50%; display:block; width : 80px; margin : 0 auto 20px;' />";
    $message .= "<p>Hola $first_name,</p>";
    $message .= "<p>Hemos recibido tu publicaci&oacute;n, en cuanto sea aprobada te notificaremos.</p>";
    $message .= "<p>Recuerda que puedes iniciar sesi&oacute;n, publicar contenido, guardar tu historial de recetas y contenido favorito, tus recetas le&iacute;das y mucho m&aacute;s visitando <a style='color : #0067B8;' href='http://comiendosabroso.com/'>http://comiendosabroso.com</a></p>";
    
    Notifications::send( $user->data->user_email, "Gracias por enviar tu $post_type", $message );
    
}

add_action('as_created_content', 'on_expedition_created_content');
*/



/**
 * Is fired when an user publish an item... 
 * 
 * We send the notification email
 * 
 * @param WP_Post $post the post published
 */
/*
function on_publish_pending_post( $post ){
    
    if ( !isset($post->post_author) ){
        return false;
    }
    
    $user_id = $post->post_author;
    $post_type = substr($post->post_type, 0, -1);
    $user = get_user_by('id', $user_id );
    $first_name = get_user_meta($user_id, 'first_name', true); // update first name
    
    $image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium' );
    $item = "";
    $image_avatar = "";
    $item_url = "http://comiendosabroso.com/articulo/{$post_type}s/{$post->post_name}/";
    
    if ( isset($image[0]) ){
        $url = $image[0];
        
        $item .= "<div><br><hr><br>";
            $item .= "<img src='$url'style='display:block; width : 100%; margin : 0 auto 20px;' />";
            $item .= "<h2><a href='$item_url' style='color : #0067B8;'>{$post->post_title}</a></h2>";
            $item .= "<div>". apply_filters('the_content', $post->post_content) ."</div>";
            $item .= "<br><hr><br>";
        $item .= "</div>";
        
    }else{
        $url = as_get_avatar( $user_id );
        $image_avatar = "<img src='$url'style='border-radius : 50%; display:block; width : 80px; margin : 0 auto 20px;' />";
    }
    
    $message .= "<h1>Tu art&iacute;culo ha sido aprobado</h1>";
    $message .= $image_avatar;
    $message .= "<p>Hola $first_name,</p>";
    $message .= "<p>Tu art&iacute;culo '<a href='$item_url' style='color : #0067B8;'>{$post->post_title}</a>' ha sido aprobado y ya est&aacute; disponible para ser visto por el mundo.</p>";
    $message .= $item;
    $message .= "<p>Recuerda que puedes iniciar sesi&oacute;n, publicar contenido, guardar tu historial de recetas y contenido favorito, tus recetas le&iacute;das y mucho m&aacute;s visitando <a style='color : #0067B8;' href='http://comiendosabroso.com/'>http://comiendosabroso.com</a></p>";
    
    Notifications::send( $user->data->user_email, "Tu $post_type ha sido aprobad".($post_type =="video" ? "o" : "a"), $message );
    
}

add_action(  'pending_to_publish',  'on_publish_pending_post' );*/