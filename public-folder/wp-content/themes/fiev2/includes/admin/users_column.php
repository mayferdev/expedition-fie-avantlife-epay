<?php

// ADD COLUMN TO USERS TABLE, SPECICALLY TO ADD THE STATUS, AND AUTH BUTTON
add_action('manage_users_columns','expedition_add_modify_user_columns');
function expedition_add_modify_user_columns($column_headers) {
  unset($column_headers['posts']);
  $column_headers['status'] = 'Status';
  return $column_headers;
}

add_action('manage_users_custom_column', 'expedition_add_modify_user_columns_content', 10, 3);
function expedition_add_modify_user_columns_content($value, $column_name, $user_id) {
//    $user = get_userdata( $user_id );
//    $roles = array('administrator');
    
    $status = get_user_meta($user_id, 'status', true);
    
    if ( $status == 1 ){
        $value = 'Active';
    }else if ( $status == 2 ){
        $value = 'User Disabled';
    }else{
        $value = 'No activated';
    }
    
    // if is a
//    if ( is_array($user->roles) && !in_array($user->roles[0],$roles) ){
//        $query = new WP_Query( 
//                    array(
//                        'post_type'=>'a', 
//                        'meta_query' => array(
//                            array(
//                             'key' => 'a_user',
//                             'value' => $user_id
//                            ),
//                        )
//                        )
//                    );
//        if ( $query->found_posts ){
//            $p = $query->posts[0];
//            $value .= '<br/><a href="'. admin_url('post.php?post='.$p->ID.'&action=edit').'">See/edit public info</a>';
//        }else{
//            
//        }
//    }
  
    return $value;
}
