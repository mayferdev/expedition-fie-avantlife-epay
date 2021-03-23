<?php

/**
 * Is fired when the booking is confirmed by owner or admin
 * 
 * @param type $booking_id the ID of the booking
 * @param type $booking the booking object
 */
function on_expedition_booking_confirmed( $booking_id, $booking ){
    
    //$booking_id
    
    $owner_user = get_userdata($booking->owner_id);
    $user = get_userdata($booking->user_id);
    $fullname = $user->first_name . ' '. $user->last_name ;
    
    /********* QR CODE ***********/
    $booking_o = array(
        'id' => $booking->id,
        'user_id' => $booking->user_id,
        'owner_id' => $booking->owner_id,
        'tour_id' => $booking->tour_id,
    );
    $image_base_64 = getQRImage( base64_encode(json_encode($booking_o)) , 250, true);
    $data = 'data:image/gif;base64,'.$image_base_64;
    list($type, $data) = explode(';', $data);
    list(, $data)      = explode(',', $data);
    $data = base64_decode($data);
    
    $upload_dir = wp_upload_dir();
    $filename = 'my-base64-image.gif';
    $hashed_filename = md5( $filename . microtime() ) . '_' . $filename;
    $upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;
    $image_upload = file_put_contents( $upload_path . $hashed_filename, $data );

    
    $file = $upload_path . $hashed_filename;//file_put_contents('/tmp/image.gif', $data);
    $uid = 'qr-image'; //will map it to this UID
    $name = $filename; //this will be the file name for the attachment

    global $phpmailer;
    add_action( 'phpmailer_init', function(&$phpmailer)use($file,$uid,$name){
        $phpmailer->SMTPKeepAlive = true;
        $phpmailer->AddEmbeddedImage($file, $uid, $name);
    });
    $qr_image = '<img src="cid:qr-image" width="250" height="250" />';
    /********* QR CODE ***********/
    

    $one_signal_id = get_field( 'one_signal_id', 'user_'.$booking->user_id);
    if ( $one_signal_id ){
        Notifications::sendPushToUser($one_signal_id, "$fullname, your booking is confirmed", "Booking info", 
                array(
                    'notification'=> 'booking_confirmed',
                    //'owner_user'=> $owner_user
                    ) 
                );
    }
    
    
    $user_language = 'en';//get_user_meta($user_id, 'user_language', true);
    $valid_languages = array('en', 'es');
    if (!in_array($user_language, $valid_languages) ){
        $user_language = $valid_languages[0];
    }
    
    $program_id = $booking->tour_id;
    $template = get_field("confirmation_content_$user_language", $program_id)?:get_field("booking_confirmation_content_$user_language", 'options');
    $voucher = 
            "<p>Hey $fullname!</p>
            <p>Your trip with $owner_user->first_name has been successfully booked.</p>
            <p>Your booking number is : $booking_id</p>
            <p>All the information you need about your tour is available on the app.</p>
            <p>We recommend to contact the tour agencie through the app, to resolve any question you might have and make sure everything is going as planned.</p>
            <p>We wish the best on your new adventure! Don’t forget to show the expeditioners your experience.</p>";

    
    $user_id = $booking->user_id;
    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name = get_user_meta($user_id, 'last_name', true);
    $user_full_name = "$first_name $last_name";
    $user_email = $user->user_email;
    $tour_id = $booking->tour_id;
    $departure_date = date('d/m/Y H:i:s', strtotime(get_field( 'departure_date', $tour_id )));
    $tags = wp_get_post_tags($tour_id);
    $tag_array = array();
    if (is_array($tags) && count($tags)>0){
        foreach ($tags as $key => $_tag) {
            $tag_array[] = $_tag->name;
        }
    }
    $tag = join(', ', $tag_array);
    
    
    $_categories = get_field('category', $tour_id);
    $categories = array();
    if ( $_categories && count($_categories) ){
        foreach ($_categories as $_category) {
            $categories[$_category['id']] = $_category['category'];
        }
    }
    $categories_shifts = [];

    $total = 0;
    $booking->tour_meta = json_decode($booking->tour_meta);
    if ( is_array($booking->tour_meta) && count($booking->tour_meta)>0 ){
        foreach ($booking->tour_meta as $meta) {
            $total = $total+$meta->total;
            $categories_shifts[] = $categories[$meta->category].' / '.$meta->title;
        }
    }
    
    $meta = json_decode($booking->tour_meta);
    $category_array = array();
    if (is_array($meta) && count($meta)>0){
        foreach ($meta as $key => $_meta) {
            $category_array[] = $_meta->title;
        }
    }
    $category_title = join(', ', $categories_shifts); //join(', ', $category_array);
    $departure_place = get_field('departure_place_name', $tour_id);
    $desc = get_field('desc', $tour_id);
    
    $search_array = array('{user_firstname}', '{user_last_name}', '{user_full_name}', '{user_email}', '{event_name}', 
        '{businesss_name}', '{booking_id}', '{booking_qr_code_image}', '{departure_date}', '{tag}', '{category_title}', '{departure_place}', '{description}');
    $replace_array = array($first_name, $last_name, $user_full_name, $user_email, get_the_title($tour_id), 
        $owner_user->first_name, $booking->id, $qr_image, $departure_date, $tag, $category_title, $departure_place, $desc);
    
    $message = str_replace(
            $search_array, 
            $replace_array, 
            $template,
            $voucher);
    
    $subject_template = get_field("confirmation_subject_$user_language", $program_id)?:get_field("booking_confirmation_subject_$user_language", 'options');
    $subject = str_replace(
            $search_array, 
            $replace_array, 
            $subject_template);
    
    
    //$enable_field = "enable_register_{$role}_notification";
    //$enable = get_field($enable_field, 'options');
    $enable = true;
    
    if ( !$enable ){
        Expedition_Helper::logMessage( "Booking email disabled, not sent to $user_email", 'emails_sent.txt');
    }else{
        $sent = Notifications::send( $user_email, $subject, $message );
        if (file_exists($file)) {
            unlink($file);
        }
        Expedition_Helper::logMessage( "Booking confirm email ".($sent ? 'sent' : 'failed')." to $user_email", 'emails_sent.txt');
    }
    
    
    
    /*$content = 
            "<p>Hey $fullname!</p>
            <p>Your trip with $owner_user->first_name has been successfully booked.</p>
            <p>Your booking number is : $booking_id</p>
            <p>All the information you need about your tour is available on the app.</p>
            <p>We recommend to contact the tour agencie through the app, to resolve any question you might have and make sure everything is going as planned.</p>
            <p>We wish the best on your new adventure! Don’t forget to show the expeditioners your experience.</p>";

    $sent = Notifications::send($user->user_email, 'Trip confirmed', $content );
    Expedition_Helper::logMessage( "Booking confirm email ".($sent ? 'sent' : 'failed')." to $user->user_email", 'emails_sent.txt');*/
    
}
add_action('expedition_booking_confirmed', 'on_expedition_booking_confirmed', 10, 2);

function on_expedition_booking_confirmed_card( $booking_id, $booking ){
    

    // ------------ CARD DETAILS ----------------
    $cardDetails = Expedition_Helper::getUserCardByBookingId($booking_id, $booking->user_id);
    error_log( print_r( $cardDetails, true ) );
    // ------------ CARD DETAILS ----------------
    
    $owner_user = get_userdata($booking->owner_id);
    $user = get_userdata($booking->user_id);
    $fullname = $user->first_name . ' '. $user->last_name ;
    
    /********* QR CODE ***********/
    $booking_o = array(
        'id' => $booking->id,
        'user_id' => $booking->user_id,
        'owner_id' => $booking->owner_id,
        'tour_id' => $booking->tour_id,
    );
    
    $image_base_64 = getQRImage( base64_encode(json_encode($booking_o)) , 250, true);
    $data = 'data:image/gif;base64,'.$image_base_64;
    list($type, $data) = explode(';', $data);
    list(, $data)      = explode(',', $data);
    $data = base64_decode($data);
    
    $upload_dir = wp_upload_dir();
    $filename = 'my-base64-image.gif';
    $hashed_filename = md5( $filename . microtime() ) . '_' . $filename;
    $upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;
    $image_upload = file_put_contents( $upload_path . $hashed_filename, $data );

    
    $file = $upload_path . $hashed_filename;//file_put_contents('/tmp/image.gif', $data);
    $uid = 'qr-image'; //will map it to this UID
    $name = $filename; //this will be the file name for the attachment

    global $phpmailer;
    add_action( 'phpmailer_init', function(&$phpmailer)use($file,$uid,$name){
        $phpmailer->SMTPKeepAlive = true;
        $phpmailer->AddEmbeddedImage($file, $uid, $name);
    });
    $qr_image = '<img src="cid:qr-image" width="250" height="250" />';
    /********* QR CODE ***********/
    

    $one_signal_id = get_field( 'one_signal_id', 'user_'.$booking->user_id);
    if ( $one_signal_id ){
        Notifications::sendPushToUser($one_signal_id, "$fullname, your booking is confirmed", "Booking info", 
                array(
                    'notification'=> 'booking_confirmed',
                    //'owner_user'=> $owner_user
                    ) 
                );
    }
    
    
    $user_language = 'en';//get_user_meta($user_id, 'user_language', true);
    $valid_languages = array('en', 'es');
    if (!in_array($user_language, $valid_languages) ){
        $user_language = $valid_languages[0];
    }
    
    $program_id = $booking->tour_id;
    $template = get_field("confirmation_content_$user_language", $program_id)?:get_field("booking_confirmation_content_$user_language", 'options');
    $voucher = get_field("booking_voucher_$user_language", 'options');
    // error_log( print_r( $voucher, true ) );
    // $voucher = 
    //         "<p>Hey $fullname!</p>
    //         <p>Your trip with $owner_user->first_name has been successfully booked.</p>
    //         <p>Your booking number is : $booking_id</p>
    //         <p>All the information you need about your tour is available on the app.</p>
    //         <p>We recommend to contact the tour agencie through the app, to resolve any question you might have and make sure everything is going as planned.</p>
    //         <p>We wish the best on your new adventure! Don’t forget to show the expeditioners your experience.</p>";

    
    $user_id = $booking->user_id;
    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name = get_user_meta($user_id, 'last_name', true);
    $user_full_name = "$first_name $last_name";
    $user_email = $user->user_email;
    $tour_id = $booking->tour_id;
    $departure_date = date('d/m/Y H:i:s', strtotime(get_field( 'departure_date', $tour_id )));
    $tags = wp_get_post_tags($tour_id);
    $tag_array = array();
    if (is_array($tags) && count($tags)>0){
        foreach ($tags as $key => $_tag) {
            $tag_array[] = $_tag->name;
        }
    }
    $tag = join(', ', $tag_array);
    
    
    $_categories = get_field('category', $tour_id);
    $categories = array();
    if ( $_categories && count($_categories) ){
        foreach ($_categories as $_category) {
            $categories[$_category['id']] = $_category['category'];
        }
    }
    $categories_shifts = [];

    $total = 0;
    $booking->tour_meta = json_decode($booking->tour_meta);
    if ( is_array($booking->tour_meta) && count($booking->tour_meta)>0 ){
        foreach ($booking->tour_meta as $meta) {
            $total = $total+$meta->total;
            $categories_shifts[] = $categories[$meta->category].' / '.$meta->title;
        }
    }
    
    $meta = json_decode($booking->tour_meta);
    $category_array = array();
    if (is_array($meta) && count($meta)>0){
        foreach ($meta as $key => $_meta) {
            $category_array[] = $_meta->title;
        }
    }
    $category_title = join(', ', $categories_shifts); //join(', ', $category_array);
    $departure_place = get_field('departure_place_name', $tour_id);
    $desc = get_field('desc', $tour_id);
    

    $search_array = array('{user_firstname}', '{user_last_name}', '{user_full_name}', '{user_email}', '{tour_name}', 
        '{businesss_name}', '{booking_id}', '{booking_qr_code_image}', '{departure_date}', '{tag}', '{category_title}', '{departure_place}', '{description}',
        '{No_afiliacion}', '{transaction_time}', '{No_referencia}', '{No_autorizacion}',  '{No_auditoria}', '{last_digits}', '{nombre_tarjeta}', '{total}');
    $replace_array = array($first_name, $last_name, $user_full_name, $user_email, get_the_title($tour_id), 
        $owner_user->first_name, $booking->id, $qr_image, $departure_date, $tag, $category_title, $departure_place, $desc,
        $cardDetails[0]->afiliation, $cardDetails[0]->created_at, $cardDetails[0]->reference_num, $cardDetails[0]->auth_num, $cardDetails[0]->audit_num,
        $cardDetails[0]->credit_card_last4, $cardDetails[0]->credit_card_name, $cardDetails[0]->amount
        );
    
    $message = str_replace(
            $search_array, 
            $replace_array, 
            $voucher);
    
    $subject_template = get_field("voucher_subject", $program_id)?:get_field("voucher_subject", 'options');
    
    $subject = str_replace(
            $search_array, 
            $replace_array, 
            $subject_template);
    
    
    //$enable_field = "enable_register_{$role}_notification";
    //$enable = get_field($enable_field, 'options');
    $enable = true;
    
    if ( !$enable ){
        Expedition_Helper::logMessage( "Booking email disabled, not sent to $user_email", 'emails_sent.txt');
    }else{
        $sent = Notifications::send( $user_email, $subject, $message );
        if (file_exists($file)) {
            unlink($file);
        }
        Expedition_Helper::logMessage( "Booking confirm email ".($sent ? 'sent' : 'failed')." to $user_email", 'emails_sent.txt');
    }
    
    
    
    
   /*
    $sent = Notifications::send($user->user_email, 'Trip confirmed', $content );
    Expedition_Helper::logMessage( "Booking confirm email ".($sent ? 'sent' : 'failed')." to $user->user_email", 'emails_sent.txt');*/
    
}
add_action('expedition_booking_confirmed_card', 'on_expedition_booking_confirmed_card', 10, 2);

function on_expedition_booking_cancelled( $booking_id, $booking ){
    
    //$booking_id
    
    $owner_user = get_userdata($booking->owner_id);
    $user = get_userdata($booking->user_id);
    $fullname = $user->first_name . ' '. $user->last_name ;
    
    
    
    /********* QR CODE ***********/

    /**
     $booking_o = array(
        'id' => $booking->id,
        'user_id' => $booking->user_id,
        'owner_id' => $booking->owner_id,
        'tour_id' => $booking->tour_id,
    );
    $image_base_64 = getQRImage( base64_encode(json_encode($booking_o)) , 250, true);
    $data = 'data:image/gif;base64,'.$image_base_64;
    list($type, $data) = explode(';', $data);
    list(, $data)      = explode(',', $data);
    $data = base64_decode($data);
    
    $upload_dir = wp_upload_dir();
    $filename = 'my-base64-image.gif';
    $hashed_filename = md5( $filename . microtime() ) . '_' . $filename;
    $upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;
    $image_upload = file_put_contents( $upload_path . $hashed_filename, $data );

    
    $file = $upload_path . $hashed_filename;//file_put_contents('/tmp/image.gif', $data);
    $uid = 'qr-image'; //will map it to this UID
    $name = $filename; //this will be the file name for the attachment

    global $phpmailer;
    add_action( 'phpmailer_init', function(&$phpmailer)use($file,$uid,$name){
        $phpmailer->SMTPKeepAlive = true;
        $phpmailer->AddEmbeddedImage($file, $uid, $name);
    });
    $qr_image = '<img src="cid:qr-image" width="250" height="250" />';
    **/
    /********* QR CODE ***********/
    

    $one_signal_id = get_field( 'one_signal_id', 'user_'.$booking->user_id);
    if ( $one_signal_id ){
        Notifications::sendPushToUser($one_signal_id, "$fullname, your booking is confirmed", "Booking info", 
                array(
                    'notification'=> 'booking_confirmed',
                    //'owner_user'=> $owner_user
                    ) 
                );
    }
    
    
    $user_language = 'en';//get_user_meta($user_id, 'user_language', true);
    $valid_languages = array('en', 'es');
    if (!in_array($user_language, $valid_languages) ){
        $user_language = $valid_languages[0];
    }
    
    $program_id = $booking->tour_id;
    // $template = get_field("confimation_content_$user_language", $program_id)?:get_field("booking_cancellation_content_$user_language", 'options');
    
    $template = get_field("booking_cancellation_content_$user_language", 'options');
    
    $user_id = $booking->user_id;
    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name = get_user_meta($user_id, 'last_name', true);
    $user_full_name = "$first_name $last_name";
    $user_email = $user->user_email;
    $tour_id = $booking->tour_id;
    $departure_date = date('d/m/Y H:i:s', strtotime(get_field( 'departure_date', $tour_id )));
    $tags = wp_get_post_tags($tour_id);
    $tag_array = array();
    if (is_array($tags) && count($tags)>0){
        foreach ($tags as $key => $_tag) {
            $tag_array[] = $_tag->name;
        }
    }
    $tag = join(', ', $tag_array);
    
    
    $_categories = get_field('category', $tour_id);
    $categories = array();
    if ( $_categories && count($_categories) ){
        foreach ($_categories as $_category) {
            $categories[$_category['id']] = $_category['category'];
        }
    }
    $categories_shifts = [];

    $total = 0;
    $booking->tour_meta = json_decode($booking->tour_meta);
    if ( is_array($booking->tour_meta) && count($booking->tour_meta)>0 ){
        foreach ($booking->tour_meta as $meta) {
            $total = $total+$meta->total;
            $categories_shifts[] = $categories[$meta->category].' / '.$meta->title;
        }
    }
    
    $meta = json_decode($booking->tour_meta);
    $category_array = array();
    if (is_array($meta) && count($meta)>0){
        foreach ($meta as $key => $_meta) {
            $category_array[] = $_meta->title;
        }
    }
    $category_title = join(', ', $categories_shifts); //join(', ', $category_array);
    $departure_place = get_field('departure_place_name', $tour_id);
    $desc = get_field('desc', $tour_id);
    
    // getting transaction details: 
    $transactions = (array) Expedition_Helper::getTransactionsByBooking($booking->id);
    
    $transaction_sent = json_decode($transactions[0]->ws_sent);
    $transaction_response = json_decode($transactions[0]->ws_response);
    if($transaction_sent &&  $transaction_response) {
        $transaction_sent = $transaction_sent->AuthorizationRequest;
        $isCard = true;
        $title = 'VisaNet Guatemala C.A. <br> Anulación de Transacción';
        $cardDetails = Expedition_Helper::getUserCardByBookingId($booking_id, $booking->user_id);
    } else {
        $isCard = false;
        $title = 'Cancelación de Reserva';
    }
    
     $search_array = array('{user_firstname}', '{user_last_name}', '{user_full_name}', '{user_email}', '{event_name}', 
        '{businesss_name}', '{booking_id}', '{departure_date}', '{tag}', '{category_title}', '{departure_place}', '{description}',
        '{titulo}', '{No_afiliacion}', '{transaction_time}', '{No_referencia}', '{No_autorizacion}',  '{No_auditoria}', '{last_digits}', '{nombre_tarjeta}', '{total}');
        
    if($isCard){
        $replace_array = array($first_name, $last_name, $user_full_name, $user_email, get_the_title($tour_id), 
        $owner_user->first_name, $booking->id, $departure_date, $tag, $category_title, $departure_place, $desc,
        $title, $cardDetails[0]->afiliation, $cardDetails[0]->created_at, $cardDetails[0]->reference_num, $cardDetails[0]->auth_num, $cardDetails[0]->audit_num,
        $cardDetails[0]->credit_card_last4, $cardDetails[0]->credit_card_name, '-'.$cardDetails[0]->amount);
        $template .= <<<EOT
<center></center>
<table id="backgroundTable" style="background-color: #c7c7c7; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin-top: 30px; margin-bottom: 30px; border-collapse: collapse !important;" border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" align="center" valign="top">
<table id="templateContainer" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 500px; margin-top: 30px; margin-bottom: 30px; border-collapse: collapse !important;" border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" align="center" valign="top">
<table id="templateModule" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 500px; background-color: #ffffff; border-radius: 4px; margin-bottom: 40px; border-collapse: collapse !important;" border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><!-- // Begin Template Header \\ -->
<table id="templateHeader" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: transparent; border-collapse: collapse !important;" border="0" width="100%" cellspacing="0" cellpadding="0"></table>
</td>
</tr>
<tr>
<td style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" align="center" valign="top">
<table id="templateBody" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: transparent; border-top: 1px solid #FFFFFF; border-collapse: collapse !important;" border="0" width="100%" cellspacing="0" cellpadding="25">
<tbody>

<td style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top">
<div>
<div class="balance_text" style="text-align: left; display: block; font-size: 24px; font-weight: 400; padding-bottom: 12px; padding-top: 12px; line-height: 1.2;">{titulo}</div>
<p class="footer" style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; font-family: 'roboto', 'Helvetica Neue', Helvetica, sans-serif; font-weight: 300; font-size: 15px; line-height: 27px; padding-left: 20px; padding-right: 20px; margin-top: 0; margin-bottom: 10px; color: #3d3d3d; text-align: left;"><span class="voucher_code_inline" style="font-weight: 600;">Afiliación: {No_afiliacion} <br>
Fecha y hora: {transaction_time}  <br>
Número de referencia: {No_referencia}  <br>
Número de Autorización: {No_autorizacion}  <br>
Número de auditoría: {No_auditoria} <br>
Últimos cuatro digitos de tarjeta: {last_digits} <br>
Nombre de tarjeta: {nombre_tarjeta} <br>
Monto: -Q{total} <br>
(01) pagado electrónicamente <br>
</span></p>


<hr />

</div></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
EOT;
        
    } else {
        $replace_array = array($first_name, $last_name, $user_full_name, $user_email, get_the_title($tour_id), 
        $owner_user->first_name, $booking->id, $departure_date, $tag, $category_title, $departure_place, $desc,
        $title);
    }
    
   
    $message = str_replace(
            $search_array, 
            $replace_array, 
            $template);
    
    // $subject_template = get_field("confirmation_subject_$user_language", $program_id)?:get_field("booking_cancellation_subject_$user_language", 'options');
    
    $subject_template = get_field("booking_cancellation_subject_$user_language", 'options');
    $subject = str_replace(
            $search_array, 
            $replace_array, 
            $subject_template);
    
    
    //$enable_field = "enable_register_{$role}_notification";
    //$enable = get_field($enable_field, 'options');
    $enable = true;
    
    if ( !$enable ){
        Expedition_Helper::logMessage( "Booking email disabled, not sent to $user_email", 'emails_sent.txt');
    }else{
        $sent = Notifications::send( $user_email, $subject, $message );
        if (file_exists($file)) {
            unlink($file);
        }
        Expedition_Helper::logMessage( "Booking confirm email ".($sent ? 'sent' : 'failed')." to $user_email", 'emails_sent.txt');
    }
    
    
    
    /*$content = 
            "<p>Hey $fullname!</p>
            <p>Your trip with $owner_user->first_name has been successfully booked.</p>
            <p>Your booking number is : $booking_id</p>
            <p>All the information you need about your tour is available on the app.</p>
            <p>We recommend to contact the tour agencie through the app, to resolve any question you might have and make sure everything is going as planned.</p>
            <p>We wish the best on your new adventure! Don’t forget to show the expeditioners your experience.</p>";

    $sent = Notifications::send($user->user_email, 'Trip confirmed', $content );
    Expedition_Helper::logMessage( "Booking confirm email ".($sent ? 'sent' : 'failed')." to $user->user_email", 'emails_sent.txt');*/
    
}
add_action('expedition_booking_cancelled', 'on_expedition_booking_cancelled', 10, 2);