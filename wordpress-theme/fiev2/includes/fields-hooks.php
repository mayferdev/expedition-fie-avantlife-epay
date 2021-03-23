<?php

/**
 * change the values for membership level
 * 
 * @param type $field
 * @return type
 */
function expedition_filter_name_membership_level_value( $field ){
    $field['choices'] = array();
    $levels_qty = get_option('options_membership_levels');
    $levels = array();
    if ( $levels_qty && (int)$levels_qty> 0 ){
        for ($index = 0; $index < $levels_qty; $index++) {
            $levels[] = array('name'=> get_option("options_membership_levels_{$index}_name") , 'tours_by_month'=>get_option("options_membership_levels_{$index}_tours_by_month"));
        }
    }
    
    foreach( $levels as $level ){
        $field['choices'][ $level['name'] ] = $level['name'];
    }
    
    return $field;
}
add_filter('acf/load_field/name=membership_level', 'expedition_filter_name_membership_level_value');



/* * ************************************************************** */
/* * ************* MAKE THE FIELD OWNER TOUR READ ONLY ************ */
/* * ************************************************************** */
function expedition_owner_tour_disable_acf_load_field( $field ) {
    global $post_id, $post;
    if ($post_id && $post && $post->post_type == 'tour' && $post->post_status == 'publish' ){
        $field['disabled'] = 1;
    }else{
        $field['disabled'] = 1;
        $current_user_role = expedition_get_user_role();
        if ( $current_user_role == 'administrator' || $current_user_role == 'editor' ){
            $field['disabled'] = 0;
        }
        $field['value'] = get_current_user_id();
        // var_dump($field, $current_user, get_current_user_id());
        // exit();
    }
    
    return $field;
}
add_filter('acf/load_field/name=owner', 'expedition_owner_tour_disable_acf_load_field');



/**
 * Detect bookings field, and we display the bookings of current tour
 * 
 * @param type $field
 * @return type
 */
function expedition_show_bookings_data_in_tour_view( $field ) {
    global $post;
    
    $id = "5b992e55fb760";
    $id2 = "5bbfceefc6454";
    //acf-field-$id
    if ( $field['key'] == "field_$id" ){
        echo '<style type="text/css">.acf-field-'.$id.' .acf-input .acf-input-wrap{display:none}</style>';
        
        
        
        global $wpdb;
        $table = USER_BOOKINGS_TABLE;
        $query = "SELECT * FROM $table WHERE tour_id = '{$post->ID}' ORDER BY created_at DESC";
        $bookings = $wpdb->get_results($query);
        
        if ($bookings){
            ?>
            <table class="wf-striped-table wf-fixed-table">
                <thead>
                    <tr>
                        <th><?= __('Booking ID', 'expedition') ?></th>
                        <th><?= __('Date', 'expedition') ?></th>
                        <th><?= __('Category / Shift', 'expedition') ?></th>
                        <th><?= __('User', 'expedition') ?></th>
                        <th><?= __('Total', 'expedition') ?></th>
                        <th><?= __('Status', 'expedition') ?></th>
                        <th><?= __('Transactions', 'expedition') ?></th>
                    </tr>
                </thead>
                <tbody>
            <?php
            foreach ($bookings as $key => $booking) {
                $user_fullname = get_user_meta( (int)$booking->user_id, 'first_name', true ) . ' ' .get_user_meta( (int)$booking->user_id, 'last_name', true );
                
                $_categories = get_field('category', $post->ID);
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
                
                
                ?>
                <tr <?= $key % 2 == 0 ? 'class="odd"' : 'class="even"' ?>>
                    <td><?= '<a target="_blank" href="'.admin_url("admin.php?page=expedition_tour_bookings&search_for=id&search=".$booking->id).'">'.$booking->id.'</a>' ?></td>
                    <td>
                        <?php
                        echo Expedition_Helper::dateFromDB($booking->created_at, "d/m/Y"). "<br>".
                             Expedition_Helper::dateFromDB($booking->created_at, "H:i:s");
                        ?>
                    </td>
                    <td>
                        <?= join('<br/>', $categories_shifts) ?>
                    </td>
                    <td>
                        <?= $user_fullname ?>
                    </td>
                    <td>
                        Q.<?= number_format($total, 2) ?>
                    </td>
                    <td>
                        <?= '<small title="'.Expedition_Helper::getTourBookingStatusInfoFromCode($booking->status).'">'.Expedition_Helper::getTourBookingStatusFromCode($booking->status).'</small>'; ?>
                    </td>
                    <td>
                        <?= '<a target="_blank" href="'. admin_url("admin.php?page=expedition_transactions&search_for=booking_id&search={$booking->id}") .'">'.__('Search', 'transactions').'</a>' ?>
                    </td>
                </tr>
                <?php
            }
            ?>
                </tbody>
            </table>
            <?php
        }else{
            echo '<p>'. __('No bookings found... yet!', 'expedition') .'</p>';
        }
    }else if ( $field['key'] == "field_$id2" ){
        echo '<style type="text/css">.acf-field-'.$id2.' .acf-input .acf-input-wrap{display:none}</style>';
        $tour_code = get_post_meta($post->ID, 'tour_code', true);
        
        $type = get_field('type', $post->ID);
        
        if ( $type == 'public' ){
            if ($tour_code){
                echo "<p>". __('Tour code', 'expedition') ." : $tour_code</p>";
            }else{
                echo "<p>". __('Please save the tour to update the tour code', 'expedition') ."</p>";
            }
        }else{
            echo "<p>". __('Tour code applies only to public tours', 'expedition') ."</p>";
        }
        
        
    }
}
add_filter('acf/render_field/type=message', 'expedition_show_bookings_data_in_tour_view');