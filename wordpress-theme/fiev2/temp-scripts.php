<?php

function create_viewed_travel_notes(){
    global $wpdb;
    
    $table = VIEWED_TRAVEL_NOTES_TABLE;
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "DROP TABLE IF EXISTS $table;
                    CREATE TABLE $table (
                            id BIGINT(20) NOT NULL AUTO_INCREMENT,

                            user_id BIGINT(20) NOT NULL,
                            travel_note_id BIGINT(20) NOT NULL,
                            
                            created_at DATETIME NULL,
                            updated_at DATETIME NULL,

                            PRIMARY KEY (id)
                    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}
//create_viewed_travel_notes();

function create_saved_travel_notes(){
    global $wpdb;
    
    $table = SAVED_TRAVEL_NOTES_TABLE;
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "DROP TABLE IF EXISTS $table;
                    CREATE TABLE $table (
                            id BIGINT(20) NOT NULL AUTO_INCREMENT,

                            user_id BIGINT(20) NOT NULL,
                            travel_note_id BIGINT(20) NOT NULL,
                            
                            created_at DATETIME NULL,
                            updated_at DATETIME NULL,

                            PRIMARY KEY (id)
                    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}
//create_saved_travel_notes();


function create_activities(){
    global $wpdb;
    
    $table = ACTIVITIES_TABLE;
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "DROP TABLE IF EXISTS $table;
                    CREATE TABLE $table (
                            id BIGINT(20) NOT NULL AUTO_INCREMENT,

                            user_id BIGINT(20) NOT NULL,
                            type TEXT NOT NULL,
                            ref_id BIGINT(20) NOT NULL,
                            deleted TINYINT(1) NOT NULL,
                            
                            created_at DATETIME NULL,
                            updated_at DATETIME NULL,

                            PRIMARY KEY (id)
                    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}
// create_activities();


function create_followers(){
    global $wpdb;
    
    $table = FOLLOWERS_TABLE;
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "DROP TABLE IF EXISTS $table;
                    CREATE TABLE $table (
                            id BIGINT(20) NOT NULL AUTO_INCREMENT,

                            follower_id BIGINT(20) NOT NULL,
                            following_id BIGINT(20) NOT NULL,
                            
                            deleted TINYINT(1) NOT NULL,
                            
                            created_at DATETIME NULL,
                            updated_at DATETIME NULL,

                            PRIMARY KEY (id)
                    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}
//create_followers();

function create_expeditioners_cards(){
    global $wpdb;

    $table = USER_CARDS_TABLE;
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "DROP TABLE IF EXISTS $table;
                    CREATE TABLE $table (
                            id BIGINT(20) NOT NULL AUTO_INCREMENT,

                            user_id BIGINT(20) NOT NULL,

                            alias TEXT NULL,
                            credit_card_name TEXT NULL,
                            credit_card_expiry TEXT NULL,
                            credit_card_brand TEXT NULL,
                            gateway TEXT NULL,
                            token TEXT NULL,

                            created_at DATETIME NULL,
                            updated_at DATETIME NULL,

                            PRIMARY KEY (id)
                    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}
//create_expeditioners_cards();

function create_tour_expeditioners(){
    global $wpdb;

    $table = TOURS_EXPEDITIONERS;
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "DROP TABLE IF EXISTS $table;
                    CREATE TABLE $table (
                            id BIGINT(20) NOT NULL AUTO_INCREMENT,

                            booking_id BIGINT(20) NOT NULL,
                            tour_id BIGINT(20) NOT NULL,
                            user_id BIGINT(20) NOT NULL,

                            first_name TEXT NULL,
                            last_name TEXT NULL,
                            age TEXT NULL,
                            dpi_passport TEXT NULL,
                            phone TEXT NULL,
                            email TEXT NOT NULL,

                            created_at DATETIME NULL,
                            updated_at DATETIME NULL,

                            PRIMARY KEY (id)
                    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}
//create_tour_expeditioners();

function create_user_tour_invitations(){
    global $wpdb;

    $table = $wpdb->prefix . 'user_tour_invitations';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "DROP TABLE IF EXISTS $table;
                        CREATE TABLE $table (
                            id BIGINT(20) NOT NULL AUTO_INCREMENT,
                            
                            user_id BIGINT(20) NOT NULL,
                            tour_id BIGINT(20) NOT NULL,
                            
                            status TINYINT(1) NOT NULL,
                            
                            created_at DATETIME NULL,
                            updated_at DATETIME NULL,

                            PRIMARY KEY (id)
                        ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}
function create_user_bookings(){
    global $wpdb;

    $table = $wpdb->prefix . 'user_bookings';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "DROP TABLE IF EXISTS $table;
                        CREATE TABLE $table (
                            id BIGINT(20) NOT NULL AUTO_INCREMENT,
                            
                            user_id BIGINT(20) NOT NULL,
                            owner_id BIGINT(20) NOT NULL,
                            tour_id BIGINT(20) NOT NULL,
                            tour_meta TEXT NOT NULL,
                            seats BIGINT(20) NOT NULL,
                            
                            status TINYINT(1) NOT NULL,
                            
                            created_at DATETIME NULL,
                            updated_at DATETIME NULL,

                            PRIMARY KEY (id)
                        ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

function create_transactions_table() {
    global $wpdb;
    
    $table = $wpdb->prefix . 'transactions';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "DROP TABLE IF EXISTS $table;
                    CREATE TABLE $table (
                            id BIGINT(20) NOT NULL AUTO_INCREMENT,

                            booking_id BIGINT(20) NULL,
                            user_id BIGINT(20) NOT NULL,
                            owner_id BIGINT(20) NOT NULL,
                            amount NUMERIC(10,2) NOT NULL,
                            currency VARCHAR(32) NOT NULL,
                            gateway VARCHAR(32) NOT NULL,
                            meta TEXT NULL,
                            
                            ws_sent TEXT NULL,
                            ws_response TEXT NULL,
                            success TINYINT(1) NULL,
                            
                            created_at DATETIME NULL,
                            updated_at DATETIME NULL,
                            
                            PRIMARY KEY (id)
                    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

function create_what_to_bring_table() {
    global $wpdb;
    
    $table = $wpdb->prefix . 'what_to_bring';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "DROP TABLE IF EXISTS $table;
                    CREATE TABLE $table (
                            id BIGINT(20) NOT NULL AUTO_INCREMENT,
                            
                            tour_id BIGINT(20) NULL,
                            user_id BIGINT(20) NOT NULL,
                            acf_id TEXT NOT NULL,
                            
                            status TINYINT(1) NULL,
                            
                            created_at DATETIME NULL,
                            updated_at DATETIME NULL,
                            
                            PRIMARY KEY (id)
                    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}


function create_sessions_table() {
    global $wpdb;
    
    $table = $wpdb->prefix . 'sessions';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "DROP TABLE IF EXISTS $table;
                    CREATE TABLE $table (
                            id BIGINT(20) NOT NULL AUTO_INCREMENT,
                            
                            token TEXT NULL,
                            sl_token TEXT NOT NULL,
                            user_id BIGINT(20) NOT NULL,
                            role TEXT NOT NULL,
                            
                            status TINYINT(1) NULL,
                            
                            created_at DATETIME NULL,
                            updated_at DATETIME NULL,
                            
                            PRIMARY KEY (id)
                    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}


function create_tours_per_month_table() {
    global $wpdb;
    
    $table = TOURS_PUBLISHED_IN_MONTH_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "DROP TABLE IF EXISTS $table;
                    CREATE TABLE $table (
                            id BIGINT(20) NOT NULL AUTO_INCREMENT,
                            
                            user_id BIGINT(20) NOT NULL,
                            role TEXT NOT NULL,
                            tour_id BIGINT(20) NOT NULL,
                            
                            created_at DATETIME NULL,
                            updated_at DATETIME NULL,
                            
                            PRIMARY KEY (id)
                    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}
//create_tours_per_month_table();



function create_google_places_table() {
    global $wpdb;
    
    $table = GOOGLE_PLACES_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "DROP TABLE IF EXISTS $table;
                    CREATE TABLE $table (
                            id BIGINT(20) NOT NULL AUTO_INCREMENT,
                            
                            place_id TEXT NOT NULL,
                            google_result TEXT NOT NULL,
                            
                            created_at DATETIME NULL,
                            updated_at DATETIME NULL,
                            
                            PRIMARY KEY (id)
                    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}
//create_google_places_table();



//global $firebase;
//$firebase->set(
//        array(
//            'chats' => array(
//                8705 => array(
//                            'id'    =>  8705,
//                            'name'  =>  'Mexico DF',
//                            'image' =>  "https://app.expeditionguate.com/wp-content/uploads/2018/04/1523425955_picture-360x216.jpg"
//                            )
//                )
//        ), 
//        "users/8");

//global $firebase;
//$firebase->set(
//        array(
//            'id'    =>  8686,
//            'name'  =>  'Flores Petén',
//            'image' =>  "https://app.expeditionguate.com/wp-content/uploads/2018/04/1523425562_picture-360x216.jpg"
//        ), 
//        "users/8/chats/8686");



/*
global $firebase;
$firebase->set(
        array(
            'id'    =>  8686,
            'name'  =>  'Flores Petén',
            'image' =>  "https://app.expeditionguate.com/wp-content/uploads/2018/04/1523425562_picture-360x216.jpg",
            'users' => array(8=>1, 2=>1, 3=>1),
            'last_message' => 'Hola, ya estoy en Petén, todo bien?',
            'last_message_by' => 8,
            'last_message_by_name' => 'Estuardo Estrada',
            'last_message_by_image' => 'https://app.expeditionguate.com/wp-content/uploads/2018/03/1520975648_picture-150x150.jpg',
            'last_message_timestamp' => 1524587572,
        ), 
        "recents/8686");


global $firebase;
$firebase->set(
        array(
            'id'    =>  8705,
            'name'  =>  'Mexico DF',
            'image' =>  "https://app.expeditionguate.com/wp-content/uploads/2018/04/1523425955_picture-360x216.jpg",
            'users' => array(8=>1, 2=>1, 3=>1),
            'last_message' => 'El DF está horrible jovenes, apesta a humo dondequiera',
            'last_message_by' => 8,
            'last_message_by_name' => 'XJavier Estrada',
            'last_message_by_image' => 'https://app.expeditionguate.com/wp-content/uploads/2018/03/203-150x150.jpg',
            'last_message_timestamp' => 1524587572,
        ), 
        "recents/8705");*/

/*
global $firebase;
$firebase->set(
        array(
            'users' => array(1,2,3),
            'last_activity' => array(
                1 => 1234123412343,
                2 => 1234123412349,
                3 => 1234123412359,
            ),
            'messages' => array(
                'aj43hjk3h4' => array(
                    '_id' => 'aj43hjk3h4',
                    'user' => 1,
                    'text' => 'testing message',
                    'createdAt' => 1234123412389,
                    
                    'user' => array(
                        '_id' => 8,
                        'name' => 'Estuardo Estrada',
                        'avatar' => 'https://app.expeditionguate.com/wp-content/uploads/2018/03/1520975648_picture-150x150.jpg'
                    )
                ),
                'aj43hjk3h5' => array(
                    '_id' => 'aj43hjk3h5',
                    'user' => 1,
                    'text' => 'testing message 2',
                    'createdAt' => 1234123412389,
                    
                    'user' => array(
                        '_id' => 8,
                        'name' => 'Estuardo Estrada',
                        'avatar' => 'https://app.expeditionguate.com/wp-content/uploads/2018/03/1520975648_picture-150x150.jpg'
                    )
                ),
                'aj43hjk3h6' => array(
                    '_id' => 'aj43hjk3h6',
                    'user' => 1,
                    'text' => 'testing message 3',
                    'createdAt' => 1234123412389,
                    'image' => 'https://www.google.com.gt/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png',
                    
                    'user' => array(
                        '_id' => 8,
                        'name' => 'XJavier Estrada',
                        'avatar' => 'https://app.expeditionguate.com/wp-content/uploads/2018/03/203-150x150.jpg'
                    )
                ),
            )
        ), 
        "chats/8705");*/



/*
if ( isset( $_GET['foo'] ) && $_GET['foo'] == 'bar'){
    $args = array(
                'post_type'=>'tour', 
                'posts_per_page'    =>  1000
            );
    $query = new WP_Query( $args );
    global $firebase;

    if ( $query->found_posts ){
        foreach ($query->posts as $p) {
            $type = get_field('type', $p->ID);
            if ( $type == 'private' ){
                $invitations = Expedition_Helper::getTourInvitationByTour($p->ID);
                $expeditioners = array();
                if ( $invitations ){
                    foreach ($invitations as $invitation) {
                        $expeditioners[] = (int)$invitation->user_id;
                    }
                }
                Expedition_Helper::createPrivateChatForTour($p->ID, $expeditioners);
                
            }else{
                
            }
        }
    }
}
if ( isset( $_GET['foo'] ) && $_GET['foo'] == 'bar1'){
    $args = array(
                'post_type'=>'tour', 
                'posts_per_page'    =>  1000
            );
    $query = new WP_Query( $args );
    global $firebase;

    if ( $query->found_posts ){
        foreach ($query->posts as $p) {
            $owner_id = get_field('owner', $p->ID, false);
            $postdata = array(
                'ID'            => $p->ID,
                'post_author'	=> $owner_id
            );
            $post_id = wp_update_post( $postdata );
        }
    }
}
*/

/*
$image = '';
                    $gallery = get_field('gallery', $p->ID);
                    if ( $gallery ){
                        $image = $gallery[0]['sizes']['medium'];
                    }
                    //var_dump( get_field('gallery', $p->ID), $p, $invites);
                    $tour_id = 0;
                    $users = array();
                    foreach ($invites as $invite) {
                        $tour_id = $invite->tour_id;
                        $users[$invite->user_id] = 1;
                    }
                    $recent= array(
                        'id'    =>  $tour_id,
                        'name'  =>  $p->post_title,
                        'image' =>  $image,
                        'users' => $users,
                        'last_message' => 'Welcome to '.$p->post_title,
                        'last_message_by' => 0,
                        'last_message_by_name' => 'Expeditioners',
                        'last_message_by_image' => $image,
                        'last_message_timestamp' => time()*1000,
                    );
//                    var_dump($recent);
//                    exit();
                    $firebase->set($recent, "recents/$tour_id");
 */


//$recents = FirebaseHelper::getPath('recents');
//$firebase->set($recents, "recents-backup");
//
//$chats = FirebaseHelper::getPath('chats');
//$firebase->set($chats, "chats-backup");

/*
if ( isset( $_GET['foo'] ) && $_GET['foo'] == 'bar1'){
    $args = array(
                'post_type'=>'tour', 
                'posts_per_page'    =>  1000
            );
    $query = new WP_Query( $args );
    if ( $query->found_posts ){
        foreach ($query->posts as $p) {
            update_field('currency_symbol', 'Q', $p->ID);
        }
    }
}
 */

/*
if ( isset( $_GET['foo'] ) && $_GET['foo'] == 'bar1'){
    $args = 
            array( 'post_type' => 'attachment', 
                'post_status'=>'inherit', 'orderby'=> 'date', 'order' => 'DESC', 'posts_per_page'=> -1 );
            
    $query = new WP_Query( $args );
    if ( $query->found_posts ){
        foreach ($query->posts as $p) {
            $attachment_id = $p->ID;
            Expedition_Helper::fillPlacesForAtachment($attachment_id);
            
        }
    }
}
 */




if ( isset( $_GET['foo'] ) && $_GET['foo'] == 'bar1'){
    global $wpdb;
    $transactions = $wpdb->get_results($wpdb->prepare("
                    SELECT * FROM ".TRANSACTIONS_TABLE."
                    WHERE gateway = %s;
            ", 'deposit' ));
    if ( $transactions ){
        foreach ($transactions as $transaction) {
            $meta = json_decode( $transaction->meta );
            if ( $meta->attachment_id ){
                update_field('private', 1, $meta->attachment_id);
            }
            
        }
    }
}

if ( isset( $_GET['foo2'] ) && $_GET['foo2'] == 'bar2'){
    global $wpdb;
    $users =  get_users(
                    array(
                     'number' => 10000,
                     'count_total' => false
                    )
                   );
    foreach ($users as $key => $user) {
        update_field('status', 1, "user_{$user->ID}");
    }
    
}

    