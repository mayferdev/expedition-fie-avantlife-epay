<?php

$query_args = apply_filters( 'wp_dropdown_users_args', $query_args, $r );


//add_action('pre_user_query', 'dt_pre_user_query');
//
//function dt_pre_user_query($user_search) {
//    global $wpdb, $current_screen, $ignore_users_filter;
//    
//    $is_non_profit = get_field('non_profit_organization', 'user_'.get_current_user_id());
//    $current_user_role = expedition_get_user_role();
//    if ( $current_user_role && $current_user_role == 'business' && $is_non_profit 
//            && $current_screen && $current_screen->base == 'users' ){
//        
//        $user_search->query_from = "FROM {$wpdb->users} "
//            . " INNER JOIN {$wpdb->usermeta} as mt1 ON ( {$wpdb->users}.ID = mt1.user_id )";
//            $user_search->query_where = "WHERE 1=1 "
//                    . " AND ( mt1.meta_key = '{$wpdb->prefix}capabilities' AND mt1.meta_value LIKE '%expeditioner%' ) ";
//
//        if ( isset( $_GET['s'] ) ){
//            $search = esc_sql($_GET['s']);
//            $user_search->query_where .= " AND (user_login LIKE '%$search%' OR user_url LIKE '%$search%' OR user_email LIKE '%$search%' OR user_nicename LIKE '%$search%' OR display_name LIKE '%$search%')";
//        }
//        
//    }
//}
//
//
//
//add_filter("views_users", "dt_list_table_views");
//function dt_list_table_views($views) {
//    $current_user_role = expedition_get_user_role();
//    $is_non_profit = get_field('non_profit_organization', 'user_'.get_current_user_id());
//    $users = count_users();
//    $all_num = $users['total_users'] - 1;
//    $class_all = ( strpos($views['all'], 'current') === false ) ? "" : "current";
//    $views['all'] = '<a href="users.php" class="' . $class_all . '">' . __('All') . ' <span class="count">(' . $all_num . ')</span></a>';
//
//    if ($current_user_role && $current_user_role == 'business' && $is_non_profit ) {
//        $views['expeditioner'] = str_replace('"count"', '"count hidden"', $views['expeditioner']);
//        $views = array($views['expeditioner']);
//    }
//
//    return $views;
//}





add_filter( 'wp_dropdown_users_args', 'expedition_wp_dropdown_users_args_filter', 99, 2 );
function  expedition_wp_dropdown_users_args_filter($query_args, $r) {
   if ( isset($r['name']) && $r['name'] == 'post_author_override' ){
       unset($query_args['who']);
       $query_args['role__in'] = array('administrator', 'business');
   }
   return $query_args;
}

add_filter( 'parse_query', 'expedition_prefix_parse_filter' );
function  expedition_prefix_parse_filter($query) {
   global $pagenow;
   $current_page = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';

   if ( is_admin() && 'tour' == $current_page && 'edit.php' == $pagenow && 
      isset( $_GET['departure'] ) ) {
    $query->query_vars['meta_key'] = 'departure_date';
    $query->query_vars['meta_value'] = sanitize_title($_GET['departure']);
    $query->query_vars['meta_compare'] = 'LIKE';
  }
}



add_filter('post_row_actions','expedition_row_actions', 10, 2);
function expedition_row_actions($actions, $post){
    if ($post->post_type =="tour" && get_field('type', $post->ID) == 'public' ){
        // $actions['web'] = '<a target="_blank" href="'. get_bloginfo('url').'/api/forms/optin/'.$post->ID.'?utm_source=link&utm_medium=web&">Opt-in Web</a>';
        // $actions['whatsapp'] = '<a target="_blank" href="'. get_bloginfo('url').'/api/forms/optin/'.$post->ID.'?utm_source=link&utm_medium=whatsapp&">Whatsapp</a>';
        // $actions['facebook'] = '<a target="_blank" href="'. get_bloginfo('url').'/api/forms/optin/'.$post->ID.'?utm_source=link&utm_medium=facebook&">Facebook</a>';
        // $actions['instagram'] = '<a target="_blank" href="'. get_bloginfo('url').'/api/forms/optin/'.$post->ID.'?utm_source=link&utm_medium=instagram&">Instagram</a>';
        
        // $actions['web'] = '<a target="_blank"href="'. get_bloginfo('url').'/api/forms/optin/0?tour_id='.$post->ID.'&utm_source=link&utm_medium=web&">Web</a>';
        // $actions['whatsapp'] = '<a target="_blank"href="'. get_bloginfo('url').'/api/forms/optin/0?tour_id='.$post->ID.'&utm_source=link&utm_medium=whatsapp&">Whatsapp</a>';
        // $actions['facebook'] = '<a target="_blank"href="'. get_bloginfo('url').'/api/forms/optin/0?tour_id='.$post->ID.'&utm_source=link&utm_medium=facebook&">Facebook</a>';
        // $actions['instagram'] = '<a target="_blank"href="'. get_bloginfo('url').'/api/forms/optin/0?tour_id='.$post->ID.'&utm_source=link&utm_medium=instagram&">Instagram</a>';
        
        $actions['whatsapp'] = '<a target="_blank"href="https://pay.avantlife.gt/v1?tour_id='.$post->ID.'&utm_source=link&utm_medium=whatsapp&">Whatsapp</a>';
        $actions['facebook'] = '<a target="_blank"href="https://pay.avantlife.gt/v1?tour_id='.$post->ID.'&utm_source=link&utm_medium=facebook&">Facebook</a>';
        $actions['instagram'] = '<a target="_blank"https://pay.avantlife.gt/v1?tour_id='.$post->ID.'&utm_source=link&utm_medium=instagram&">Instagram</a>';
    }
    return $actions;
}



function expedition_custom_upload_mimes($mimes = array()) {
    $mimes['jpeg'] = "image/jpeg";
    
    return $mimes;
}

add_action('upload_mimes', 'expedition_custom_upload_mimes');

//add_action( 'show_user_profile', 'expedition_show_plan_info_in_profile');

function expedition_show_plan_info_in_profile($profileuser){
    global $current_user;
    $current_user_role = expedition_get_user_role();
    
    if ($current_user_role=='business'){
        $remaining = Expedition_Helper::getBusinessRemainToursByMonth();
        if ($remaining){
            
            
            $user_level = get_user_meta($current_user->ID, 'membership_level', true);
            $tours_per_month = (int)Expedition_Helper::getBusinessAvailableToursByMonth();
            $user_name = $current_user->data->display_name;

            $message_raw = get_field('message_when_business_not_exceed_quota_inside_profile_page', 'options');
            $message_search =  array('{level_name}', '{tours_per_month}', '{user_name}');
            $message_replace =  array($user_level, $tours_per_month, $user_name);
            $message = str_replace($message_search, $message_replace, $message_raw);
            echo '<div class="expedition-message updated notice">'.$message.'</div>';
            ?>
            
            <?php
        }
    }
//    var_dump($profileuser);
//    exit();
}

function expedition_remove_user_fields($what){
    
    remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');
    remove_action('profile_personal_options', 'profile_personal_options');
    
}

add_action( 'admin_menu', 'expedition_remove_user_fields' );



add_action( 'admin_menu', 'expedition_remove_post_meta_boxes' );

function expedition_remove_post_meta_boxes() {
    
    $current_user_role = expedition_get_user_role();
    if ( $current_user_role ){
        if ( $current_user_role != 'administrator' && $current_user_role != 'editor' ){
            remove_meta_box('authordiv', 'tour', 'normal');
        }
    }
    
//    if( isset( $_GET['post'] ) && $_GET['post'] == '3' ) 
//    {
//        remove_meta_box('tagsdiv-post_tag', 'post', 'normal');
//        remove_meta_box('categorydiv', 'post', 'normal');
//        remove_meta_box('postimagediv', 'post', 'normal');
//        remove_meta_box('authordiv', 'post', 'normal');
//        remove_meta_box('authordiv', 'page', 'normal');
//        remove_meta_box('postexcerpt', 'post', 'normal');
//        remove_meta_box('trackbacksdiv', 'post', 'normal');
//        remove_meta_box('commentstatusdiv', 'post', 'normal');
//        remove_meta_box('commentstatusdiv', 'page', 'normal');
//        remove_meta_box('postcustom', 'post', 'normal');
//        remove_meta_box('postcustom', 'page', 'normal');
//        remove_meta_box('commentstatusdiv', 'post', 'normal');
//        remove_meta_box('commentsdiv', 'post', 'normal');
//        remove_meta_box('revisionsdiv', 'post', 'normal');
//        remove_meta_box('authordiv', 'post', 'normal');
//        
//        remove_meta_box('slugdiv', 'post', 'normal');
//        remove_meta_box('slugdiv', 'page', 'normal');
//        
        
//    }
}


/**
 * Prevent overwrite expeditioners list, also the owner, 
 * and create the user invitation relationships
 *
 * @param int $post_id The ID of the post.
 * @param post $post the post.
 */
function expedition_update_tour_expeditioners($post_id, $post, $update) {
    
    // var_dump_pre($post, $post_id, $update, $_POST, get_field('expeditioners', $post_id) );
    // exit();
    
    $post_type = $post->post_type;
    
    if ( $post_type != 'tour' )
        return;
    
    $current_expeditioners = get_field('expeditioners', $post_id);
    $owner = get_field('owner', $post_id);
    
    // IF POST IS OF NON PROFIT USER
//    if ( isset($_POST['acf']) && isset($_POST['acf']['field_5aab01e29cde1']) ){
//        $owner_id = $_POST['acf']['field_5aab01e29cde1'];
//        $non_profit = (bool)get_field('non_profit_organization', 'user_'.$owner_id);
//        
//        update_post_meta($post_id, 'non_profit', true);
//        
//        get_field('non_profit_organization', 'user_'.$tour['owner']);
//    }
    
    
    if ( isset($_POST['acf']) && $_POST['acf']['field_5a9654d1c01df'] == 'private' && $current_expeditioners ){ // prevent overwrite expeditioners
        $_POST['acf']['field_5a9659af3ca00'] = $current_expeditioners; // the expeditioners    
//        $expeditioners = (array)$_POST['acf']['field_5a9659af3ca00'];
//        if ( count($expeditioners)>0 ){
//            foreach ($expeditioners as $expeditioner) {
//                Expedition_Helper::insertUserTourInvitation(array(
//                    'user_id'=> (int)$expeditioner, 
//                    'tour_id'=> (int)$post_id, 
//                    'owner_id'=>(int)$_POST['acf']['field_5aab01e29cde1'], 
//                    'status'=> TOUR_PENDING ));
//            }
//        }
    }elseif( !$current_expeditioners && isset($_POST['acf']) && isset($_POST['acf']['field_5a9659af3ca00'])){
        if ( $_POST['acf']['field_5a9654d1c01df'] == 'private' ){
            $expeditioners = (array)$_POST['acf']['field_5a9659af3ca00'];
            $owner_id = $_POST['acf']['field_5aab01e29cde1'];
            Expedition_Helper::createTourExpeditionersInvitations($expeditioners, $post_id, $owner_id);
        }
    }
    
    if ( isset($_POST['acf']) && isset($_POST['acf']['field_5aab01e29cde1']) && $owner ){ // prevent overwrite owner
        $_POST['acf']['field_5aab01e29cde1'] = $owner; // the owner
    }
    
    if ( !$_POST['acf']['field_5aab01e29cde1'] ){
        $_POST['acf']['field_5aab01e29cde1'] = $_POST['post_author'];
    }
    
//    remove_action('save_post', 'expedition_update_tour_expeditioners'); // prevent loop
//    $_POST['post_content'] = $_POST['acf']['field_5a9655e1c01e2']; // samw than field 'desc'
//    $my_post = array(
//        'ID'           => $post_id,
//        'post_content' => $_POST['post_content']
//        );
//    wp_update_post( $my_post );
//    add_action('save_post', 'expedition_update_tour_expeditioners', 1, 3);
  
    $tour_code_prefix = get_field('tour_code_prefix', 'user_'.$_POST['acf']['field_5aab01e29cde1']);
    $tour_code = $tour_code_prefix . base_convert($post_id, 10, 36);
    update_post_meta($post_id, 'tour_code', strtoupper($tour_code) );
    
    
}
add_action('save_post', 'expedition_update_tour_expeditioners', 1, 3);


add_filter( 'wp_insert_post_data' , 'expedition_duplicate_desc_post_data' , '99', 2 );
function expedition_duplicate_desc_post_data( $data , $postarr ) {
    if ( $data['post_type'] == 'tour' && isset($_POST['acf']['field_5a9655e1c01e2']) ){
        $data['post_content'] = $_POST['acf']['field_5a9655e1c01e2'];
    }
    return $data;
}


/**
 * Detect doctor info field, and the retrieve doctor's user info from Firebase
 * and then print it
 * 
 * @param type $field
 * @return type
 */
function expedition_show_doctor_firebase_data( $field ) {
    global $post, $firebase;
    
    $id = "591ef71ad096c";
    //acf-field-$id
    if ( $field['key'] == "field_$id" ){
        echo '<style type="text/css">.acf-field-'.$id.' .acf-input .acf-input-wrap{display:none}</style>';
        
        if ( get_field('doctor_user', $post->ID) ){
            $uid = get_post_meta($post->ID, 'doctor_user', true);
            $user = get_user_by('id', $uid);
            
            
            $fuser = false;
            $fusers = FirebaseHelper::getPathWithQuery("User", "objectId", $uid);
            
            $keys = array(
                'objectId' => 'Doctor ID',
                //"idType" => "ID type", 
                "idNumber"=> "ID Number",
                "birthDate" => "Birth Date", 
                "bloodType" => "Blood Type",
                "city"=> "City", 
                "country" => "Country",  
                
                "oneSignalId" => "Push ID",
                );
            
            if (is_array($fusers) && count($fusers)> 0){
                $fuser = array_values($fusers)[0];
                        
                foreach ($keys as $key => $value) {
                    echo '<p><strong>'.$value.' : </strong> '.@$fuser[$key].'</p>';
                }
            }else{
                echo '<p>'.__("There isn't data in firebase", 'expedition').'</p>';
            }
        }
    }
}
add_filter('acf/render_field/type=text', 'expedition_show_doctor_firebase_data');

/**
 * Adds extra fields to get_terms function
 * 
 * @param type $terms
 * @param type $taxonomy
 * @param type $query_vars
 * @param type $term_query
 * @return type
 */
function expedition_add_fields_to_get_terms($terms){
    if (is_array($terms) && count($terms)>0){
        foreach ($terms as $key => $term) {
            $terms[$key] = apply_filters('expedition_get_term', $term);
        }
    }
    
    return $terms;
}


//return apply_filters( 'get_terms', $terms, $term_query->query_vars['taxonomy'], $term_query->query_vars, $term_query );





function expedition_filter_image_value( $value, $post_id, $field )
{
    
    if ( is_array($value) ){
        $array = array('id', 'filename', 'alt', 'author', 'description', 'caption', 'name', 'date',
            'modified', 'mime_type', 'icon', 'type', 'width', 'height');
        foreach ($array as $key) {
            try {
                unset($value[$key]);
            } catch (Exception $exc) {}
        }
    }
    
    return $value;
}

// acf/load_value/type={$field_type} - filter for a value load based on it's field type
add_filter('acf/format_value/type=image', 'expedition_filter_image_value', 999, 3);




function expedition_filter_field_59308c8a35f10_value( $field ){
    $field['choices'] = array();
    $countries = get_field('countries', 'options');
    foreach( $countries as $country ) {
        $field['choices'][ $country['id'] ] = $country['name'];
    }
    
    return $field;
}

// acf/load_value/type={$field_type} - filter for a value load based on it's field type
//add_filter('acf/format_value/id=field_59308c8a35f10', 'expedition_filter_image_value', 999, 3);
add_filter('acf/load_field/key=field_59308c8a35f10', 'expedition_filter_field_59308c8a35f10_value');




/**
 * Detect doctor info field, and the retrieve doctor's user info from Firebase
 * and then print it
 * 
 * @param type $field
 * @return type
 */
function expedition_show_map_in_media( $field ) {
    global $post;
    
    $id = "5aa4d9f60b441";
    //acf-field-$id
    if ( $field['key'] == "field_$id" ){
        echo '<style type="text/css">.acf-field-'.$id.' .acf-input .acf-input-wrap{display:none}</style>';
        //$attachment_id = $_POST['query']['item'];
        var_dump_pre($post_id);
        if ( get_field('placeID', $attachment_id) ){
            $img_url = "https://maps.googleapis.com/maps/api/staticmap?zoom=14&size=400x200&markers=color:red%7Clabel:C%7C40.718217,-73.998284&key=".EXPEDITION_GOOGLE_MAPS_API_KEY;
            echo '<img src="'.$img_url.'"/>';
        }
    }
}

//add_filter('acf/render_field/type=text', 'expedition_show_map_in_media', 99, 2);

