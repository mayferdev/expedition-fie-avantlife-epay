<?php

global $expedition_roles_permissions;
$expedition_roles_permissions = array();

/**
 * Sets the values for the global $expedition_roles_permissions
 * 
 * @global type $expedition_roles_permissions
 */
function expedition_set_roles_permissions() {
    global $expedition_roles_permissions;
    
    $current_user_role = expedition_get_user_role();
    if ( $current_user_role ){
        if ( $current_user_role == 'administrator' ){
            $expedition_roles_permissions['tour'] = array('C','R','U','D','UF');
            $expedition_roles_permissions['travel_note'] = array('C','R','U','D','UF');
        }else if ( $current_user_role == 'editor' ){
            $expedition_roles_permissions['tour'] = array('C','R','U','D','UF');
            $expedition_roles_permissions['travel_note'] = array('C','R','U','D','UF');
        }else if ( $current_user_role == 'business' ){
            /*
            $remaining = Expedition_Helper::getBusinessRemainToursByMonth();
            if (!$remaining){
                add_action( 'admin_notices', function () {
                    global $current_user;
                    // var_dump($current_user);
                    ?>
                    <div class="expedition-message update-nag notice">
                        <p>
                            <?php
                            //__( "", 'expedition' ); 
                            $user_level = get_user_meta($current_user->ID, 'membership_level', true);
                            $tours_per_month = (int)Expedition_Helper::getBusinessAvailableToursByMonth();
                            $user_name = $current_user->data->display_name;
                            
                            $message = get_field('message_when_business_exceed_quota', 'options');
                            $message_search =  array('{level_name}', '{tours_per_month}', '{user_name}');
                            $message_replace =  array($user_level, $tours_per_month, $user_name);
                            echo str_replace($message_search, $message_replace, $message);
                            ?>
                        </p>
                    </div>
                    <?php
                } );
            }
            $expedition_roles_permissions['tour'] = array( $remaining ? 'C':'NOT','R','U','D','UF');
            */
            $expedition_roles_permissions['tour'] = array( 'NOT','R','U','D','UF');
            $expedition_roles_permissions['travel_note'] = array('R', 'UF');
        }else if ( $current_user_role == 'expeditioner' ){
            $expedition_roles_permissions['tour'] = array();
        }
    }
}
add_action('admin_init', 'expedition_set_roles_permissions', 0);

/*
 * Get user's role
 *
 * @param  mixed       $user User ID or object.
 * @return string|bool       The User's role, or false on failure.
 */
function expedition_get_user_role( $user = null ) {
    $user = $user ? new WP_User( $user ) : wp_get_current_user();
    return $user->roles ? array_shift(array_values($user->roles)) : false;
}

 
/**
 * Modifies the row actions list, removing view or edit actions
 * 
 * @global array $expedition_roles_permissions
 * @param type $actions
 * @param type $post
 * @return type
 */
function expedition_modify_list_row_actions( $actions, $post ) {
    global $expedition_roles_permissions;
    // Check for your post type.
    if ( isset( $expedition_roles_permissions[$post->post_type] ) && !in_array( 'U', $expedition_roles_permissions[$post->post_type] ) ) {
        
        // var_dump($actions);
        // exit();
 
        // Build your links URL.
        $url = admin_url( 'admin.php?page=mycpt_page&post=' . $post->ID );
        
        if ( isset( $actions['edit'] ) ){
            $actions['edit'] = str_replace('>'.__('Edit').'<', '>'.__('View').'<', $actions['edit']);
        }
    }
    if ( isset( $actions['view']) ){
        unset($actions['view']);
        unset($actions['inline hide-if-no-js']);
    }
 
    return $actions;
}
add_filter( 'post_row_actions', 'expedition_modify_list_row_actions', 10, 2 );


/**
 * Removes the submit meta box area from some post types, based on $expedition_roles_permissions levels
 * 
 * @global array $expedition_roles_permissions
 */
function expedition_remove_submit_meta_box() {
    global $expedition_roles_permissions;
    
    foreach ($expedition_roles_permissions as $post_type => $permissions) {
        if ( !in_array('U', $permissions)) {
            remove_meta_box('submitdiv', $post_type, 'core');
        }
        // add_meta_box('submitdiv', sprintf( __('Save/Update %s'), $value ), 'themeflection_submit_meta_box', $item, 'side', 'low'); // $value will be the output title in the box
    }
}
add_action('admin_menu', 'expedition_remove_submit_meta_box');

/**
 * Receive the $permissions array to merge to return appropiate object to use in WP
 * 
 * @param array $permissions simple array with children C R U D
 * @param string $post_type the post type to assign/remove permissions
 * @param string $role
 */
function expedition_prepare_role_permissions($post_type, $role) {
    global $wp_roles, $expedition_roles_permissions;
    
    if ( !isset($expedition_roles_permissions[$post_type]) ){
        return;
    }
    $permissions = $expedition_roles_permissions[$post_type];

    /**
     * Read and update array will be merged due in WP admin side doesnt exist
     * read permissions, only in site side.
     * 
     * We'll add a hook to delete the save box to prevent modify a post to
     * simulate the read permissions although this will be hooked only
     */
    $array = array(
        "C" => array(
            "publish_{$post_type}s" => true,
            "publish_{$post_type}" => true,
            "create_{$post_type}s" => true,
            "create_{$post_type}" => true,
        ),
        "R" => array(
            // "read" => true,
            "read_{$post_type}" => true,
            "read_{$post_type}s" => true,
            "read_private_{$post_type}s" => true,
            "read_others_{$post_type}s" => true,
            "edit_{$post_type}" => true,
            "edit_{$post_type}s" => true,
            "edit_others_{$post_type}s" => true,
            "edit_private_{$post_type}s" => true,
            "edit_published_{$post_type}s" => true,
        ),
        "U" => array(
//            "edit_{$post_type}" => true,
//            "edit_{$post_type}s" => true,
//            "edit_others_{$post_type}s" => true,
//            "edit_private_{$post_type}s" => true,
//            "edit_published_{$post_type}s" => true,
        ),
        "D" => array(
            "delete_{$post_type}" => true,
            "delete_{$post_type}s" => true,
            "delete_private_{$post_type}s" => true,
            "delete_published_{$post_type}s" => true,
            "delete_others_{$post_type}s" => true,
        ),
        "O" => array(
        // "edit_private_posts" => true,
        // "delete_private_posts" => true,
        ),
        "UF" => array(
            "upload_files" => true,
        ),
    );

    $crud_array = array('C', 'R', 'U', 'D', 'O', 'UF');
    // echo '<div style="margin-left:200px;">';
    foreach ($crud_array as $crud_letter) {
        $value = in_array($crud_letter, $permissions);
        if (is_array($array[$crud_letter]) && count($array[$crud_letter]) > 0) {
            foreach ($array[$crud_letter] as $key => $permission) {
                // $return[$key] = $value;
                if ($value) {
//                     echo "adding $key to role $role <br>";
                    $wp_roles->add_cap($role, (string) $key);
                } else {
//                    echo "remove $key to role $role <br>";
                    $wp_roles->remove_cap($role, (string) $key);
                }
            }
        }
    }
    // echo '</div>';

    //return array_merge( $form_caps['C'], $form_caps['R'], $form_caps['U'], $form_caps['D'] );
    
    
}

/**
 * Register custom roles and assing permissions
 * 
 * @global Class $wp_roles
 */
function expedition_add_roles_on_activation() {
    global $wp_roles, $current_user;
    
    remove_role('author');
    remove_role('subscriber');
    remove_role('contributor');
    
    //$wp_roles->add_cap('editor','edit_tour');
    //$wp_roles->add_cap('administrator','edit_tour');
    
    // $adm = $wp_roles->get_role('editor');
    // $adm->capabilities
    
    add_role('expeditioner', __('Expeditioner', 'expedition'), array('read' => true, 'level_0' => true, 'edit_posts' => true));
    add_role('business', __('Business', 'expedition'), array('read' => true, 'level_0' => true, 'edit_posts' => true));
    
    $post_types = array('tour', 'travel_note');
    foreach ($post_types as $post_type) {
        expedition_prepare_role_permissions($post_type, 'administrator');
        expedition_prepare_role_permissions($post_type, 'editor');
        expedition_prepare_role_permissions($post_type, 'expeditioner');
        expedition_prepare_role_permissions($post_type, 'business');
    }
    
    $current_user_role = expedition_get_user_role();
//    var_dump($current_user, $current_user_role);
//    exit();
    // $user = get_userdata( $current_user->ID );
//    $user->set_role( 'administrator' );
    
    if ($current_user_role=='business'){
        $remaining = Expedition_Helper::getBusinessRemainToursByMonth();
        if (!$remaining){
            add_action( 'admin_notices', function () {
                global $current_user;
                // var_dump($current_user);
                ?>
                <div class="expedition-message update-nag notice">
                    <p>
                        <?php
                        //__( "", 'expedition' ); 
                        $user_level = get_user_meta($current_user->ID, 'membership_level', true);
                        $tours_per_month = (int)Expedition_Helper::getBusinessAvailableToursByMonth();
                        $user_name = $current_user->data->display_name;

                        $message = get_field('message_when_business_exceed_quota', 'options');
                        $message_search =  array('{level_name}', '{tours_per_month}', '{user_name}');
                        $message_replace =  array($user_level, $tours_per_month, $user_name);
                        echo str_replace($message_search, $message_replace, $message);
                        ?>
                    </p>
                </div>
                <?php
            } );
            $user = new WP_User( $current_user->ID );
            $user->remove_cap( 'publish_tours' );
            $user->remove_cap( 'publish_tour' );
            $user->remove_cap( 'create_tours' );
            $user->remove_cap( 'create_tour' );
            $user = new WP_User( $current_user->ID );
//            var_dump_pre($user, $current_user);
        }else{
            $user = new WP_User( $current_user->ID );
            $user->add_cap( 'publish_tours' );
            $user->add_cap( 'publish_tour' );
            $user->add_cap( 'create_tours' );
            $user->add_cap( 'create_tour' );
            $user = new WP_User( $current_user->ID );
//            var_dump_pre($user, $current_user);   
        }   
    }
    $wp_roles->add_cap('editor', 'list_users');
    $wp_roles->add_cap('editor', 'edit_users');
    $wp_roles->add_cap('editor', 'create_users');
    $wp_roles->add_cap('editor', 'delete_users');
}
//register_activation_hook(__FILE__, 'add_roles_on_plugin_activation');
add_action('admin_init', 'expedition_add_roles_on_activation', 0);



//$user = get_userdata( 1 );
//$user->set_role( 'administrator' );
//
//$user = get_userdata( 2 );
//
//$user->remove_role( 'business' );
//$user->set_role( 'business' );

/******************************************************************************/
/******************************************************************************/
/******************************************************************************/





// Show only posts and media related to logged in author
add_action('pre_get_posts', 'query_set_only_author' );
function query_set_only_author( $wp_query ) {
    global $current_user;
    if( is_admin() && !current_user_can('administrator') ) {
        $wp_query->set( 'author', $current_user->ID );
        add_filter('views_edit-post', 'fix_post_counts');
        add_filter('views_upload', 'fix_media_counts');
    }
}

// Fix post counts
function fix_post_counts($views) {
    global $current_user, $wp_query;
    unset($views['mine']);
    $types = array(
        array( 'status' =>  NULL ),
        array( 'status' => 'publish' ),
        array( 'status' => 'draft' ),
        array( 'status' => 'pending' ),
        array( 'status' => 'trash' )
    );
    foreach( $types as $type ) {
        $query = array(
            'author'      => $current_user->ID,
            'post_type'   => 'post',
            'post_status' => $type['status']
        );
        $result = new WP_Query($query);
        if( $type['status'] == NULL ):
            $class = ($wp_query->query_vars['post_status'] == NULL) ? ' class="current"' : '';
            $views['all'] = sprintf(__('<a href="%s"'. $class .'>All <span class="count">(%d)</span></a>', 'all'),
                admin_url('edit.php?post_type=post'),
                $result->found_posts);
        elseif( $type['status'] == 'publish' ):
            $class = ($wp_query->query_vars['post_status'] == 'publish') ? ' class="current"' : '';
            $views['publish'] = sprintf(__('<a href="%s"'. $class .'>Published <span class="count">(%d)</span></a>', 'publish'),
                admin_url('edit.php?post_status=publish&post_type=post'),
                $result->found_posts);
        elseif( $type['status'] == 'draft' ):
            $class = ($wp_query->query_vars['post_status'] == 'draft') ? ' class="current"' : '';
            $views['draft'] = sprintf(__('<a href="%s"'. $class .'>Draft'. ((sizeof($result->posts) > 1) ? "s" : "") .' <span class="count">(%d)</span></a>', 'draft'),
                admin_url('edit.php?post_status=draft&post_type=post'),
                $result->found_posts);
        elseif( $type['status'] == 'pending' ):
            $class = ($wp_query->query_vars['post_status'] == 'pending') ? ' class="current"' : '';
            $views['pending'] = sprintf(__('<a href="%s"'. $class .'>Pending <span class="count">(%d)</span></a>', 'pending'),
                admin_url('edit.php?post_status=pending&post_type=post'),
                $result->found_posts);
        elseif( $type['status'] == 'trash' ):
            $class = ($wp_query->query_vars['post_status'] == 'trash') ? ' class="current"' : '';
            $views['trash'] = sprintf(__('<a href="%s"'. $class .'>Trash <span class="count">(%d)</span></a>', 'trash'),
                admin_url('edit.php?post_status=trash&post_type=post'),
                $result->found_posts);
        endif;
    }
    return $views;
}

// Fix media counts
function fix_media_counts($views) {
    global $wpdb, $current_user, $post_mime_types, $avail_post_mime_types;
    $views = array();
    $count = $wpdb->get_results( "
        SELECT post_mime_type, COUNT( * ) AS num_posts 
        FROM $wpdb->posts 
        WHERE post_type = 'attachment' 
        AND post_author = $current_user->ID 
        AND post_status != 'trash' 
        GROUP BY post_mime_type
    ", ARRAY_A );
    foreach( $count as $row )
        $_num_posts[$row['post_mime_type']] = $row['num_posts'];
    $_total_posts = array_sum($_num_posts);
    $detached = isset( $_REQUEST['detached'] ) || isset( $_REQUEST['find_detached'] );
    if ( !isset( $total_orphans ) )
        $total_orphans = $wpdb->get_var("
            SELECT COUNT( * ) 
            FROM $wpdb->posts 
            WHERE post_type = 'attachment' 
            AND post_author = $current_user->ID 
            AND post_status != 'trash' 
            AND post_parent < 1
        ");
    $matches = wp_match_mime_types(array_keys($post_mime_types), array_keys($_num_posts));
    foreach ( $matches as $type => $reals )
        foreach ( $reals as $real )
            $num_posts[$type] = ( isset( $num_posts[$type] ) ) ? $num_posts[$type] + $_num_posts[$real] : $_num_posts[$real];
    $class = ( empty($_GET['post_mime_type']) && !$detached && !isset($_GET['status']) ) ? ' class="current"' : '';
    $views['all'] = "<a href='upload.php'$class>" . sprintf( __('All <span class="count">(%s)</span>', 'uploaded files' ), number_format_i18n( $_total_posts )) . '</a>';
    foreach ( $post_mime_types as $mime_type => $label ) {
        $class = '';
        if ( !wp_match_mime_types($mime_type, $avail_post_mime_types) )
            continue;
        if ( !empty($_GET['post_mime_type']) && wp_match_mime_types($mime_type, $_GET['post_mime_type']) )
            $class = ' class="current"';
        if ( !empty( $num_posts[$mime_type] ) )
            $views[$mime_type] = "<a href='upload.php?post_mime_type=$mime_type'$class>" . sprintf( translate_nooped_plural( $label[2], $num_posts[$mime_type] ), $num_posts[$mime_type] ) . '</a>';
    }
    $views['detached'] = '<a href="upload.php?detached=1"' . ( $detached ? ' class="current"' : '' ) . '>' . sprintf( __( 'Unattached <span class="count">(%s)</span>', 'detached files' ), $total_orphans ) . '</a>';
    return $views;
}






function wpse_188863_get_allowed_roles( $user ) {
    $allowed = array();

    if ( in_array( 'administrator', $user->roles ) ) { // Admin can edit all roles
        $allowed = array_keys( $GLOBALS['wp_roles']->roles );
    } elseif ( in_array( 'editor', $user->roles ) ) {
        $allowed[] = 'business';
    }

    return $allowed;
}

/**
 * Remove roles that are not allowed for the current user role.
 */
function wpse_188863_editable_roles( $roles ) {
    if ( $user = wp_get_current_user() ) {
        $allowed = wpse_188863_get_allowed_roles( $user );

        foreach ( $roles as $role => $caps ) {
            if ( ! in_array( $role, $allowed ) )
                unset( $roles[ $role ] );
        }
    }

    return $roles;
}

add_filter( 'editable_roles', 'wpse_188863_editable_roles' );


/**
 * Prevent users deleting/editing users with a role outside their allowance.
 */
function wpse_188863_map_meta_cap( $caps, $cap, $user_ID, $args ) {
    if ( ( $cap === 'edit_user' || $cap === 'delete_user' ) && $args ) {
        $the_user = get_userdata( $user_ID ); // The user performing the task
        $user     = get_userdata( $args[0] ); // The user being edited/deleted

        if ( $the_user && $user && $the_user->ID != $user->ID /* User can always edit self */ ) {
            $allowed = wpse_188863_get_allowed_roles( $the_user );

            if ( array_diff( $user->roles, $allowed ) ) {
                // Target user has roles outside of our limits
                $caps[] = 'not_allowed';
            }
        }
    }

    return $caps;
}

add_filter( 'map_meta_cap', 'wpse_188863_map_meta_cap', 10, 4 );