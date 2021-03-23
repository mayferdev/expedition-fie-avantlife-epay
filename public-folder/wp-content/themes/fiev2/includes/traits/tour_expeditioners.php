<?php

trait TourExpeditioners{
    
    
    /**
     * Get expeditioners of passed $tour_id
     * 
     * @param integer $tour_id
     * @return array
     * 
     */
    public static function getTourExpeditionersByTour($tour_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM ".TOURS_EXPEDITIONERS."
			WHERE tour_id = %d;
		", $tour_id ));
    }
    
    /**
     * Get expeditioners of passed $tour_id
     * 
     * @param integer $tour_id
     * @return array
     * 
     */
    public static function getTourExpeditioners($tour_id, $user_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("
			SELECT * FROM ".TOURS_EXPEDITIONERS."
			WHERE tour_id = %d AND user_id = %d;
		", $tour_id, $user_id ));
    }
    
    /**
     * Get specific tour expeditioner object by $id
     * 
     * @param integer $id tour expeditioner id to get
     * @return object
     * 
     */
    public static function getTourExpeditioner($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("
			SELECT * FROM ".TOURS_EXPEDITIONERS."
			WHERE id = %d;
		", $id ));
    }
    
    /**
     * Inserts a record into tour expeditioner table
     * 
     * @global type $wpdb
     * @param type $data
     * @return type
     */
    public static function insertTourExpeditionerRecord($data) {
        global $wpdb;
        $insert_response = $wpdb->insert(TOURS_EXPEDITIONERS, [
            //'id' => '', // auto
            
            // 4 numeros
            'booking_id' => $data['booking_id'],
            'tour_id' => $data['tour_id'],
            'user_id' => $data['user_id'],
            'expeditioner_id' => $data['expeditioner_id'],
            
            // 5 strings
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'sex' => $data['sex'],
            'age' => $data['age'],
            
            // 4 strings
            'nationality' => $data['nationality'],
            'dpi_passport' => $data['dpi_passport'],
            'phone' => $data['phone'],
            'occupation' => $data['occupation'],
            
            // 10 - dynamic extra fields - strings
            'ex_field_01' => $data['ex_field_01'],
            'ex_field_02' => $data['ex_field_02'],
            'ex_field_03' => $data['ex_field_03'],
            'ex_field_04' => $data['ex_field_04'],
            'ex_field_05' => $data['ex_field_05'],
            'ex_field_06' => $data['ex_field_06'],
            'ex_field_07' => $data['ex_field_07'],
            'ex_field_08' => $data['ex_field_08'],
            'ex_field_09' => $data['ex_field_09'],
            'ex_field_10' => $data['ex_field_10'],
            

            // 4 strings
            'receipt_name' => $data['receipt_name'],
            'receipt_num' => $data['receipt_num'],
            'direccion_nit'=> $data['receipt_address'],
            'source' => $data['source'],
            
            
            // 2 strings
            'created_at' => current_time('mysql', 1),
            'updated_at' => current_time('mysql', 1)
            
                ], ['%d', '%d', '%d', '%d',
                    '%s', '%s', '%s', '%s', '%s', 
                    '%s', '%s', '%s', '%s',
                    '%s', '%s', '%s', '%s', '%s',
                    '%s', '%s', '%s', '%s', '%s',
                    '%s', '%s', '%s', '%s',
                    '%s', '%s']);
        
        if ( $insert_response ){
            $_id = $wpdb->insert_id;
            return self::getTourExpeditioner($_id);
        }
        
        return $insert_response;
        
    }
    
    /**
     * Modify a record into tour expeditioner table
     * 
     * @global type $wpdb
     * @param type $data
     * @return type
     */
    public static function modifyTourExpeditionerRecord($data, $record) {
        global $wpdb;
        $modified_response = $wpdb->update(
                TOURS_EXPEDITIONERS, 
                [
                    //'id' => '', // auto
                    'booking_id' => isset($data['booking_id']) ? $data['booking_id'] : $record->booking_id,
                    'tour_id' => isset($data['tour_id']) ? $data['tour_id'] : $record->tour_id,
                    'user_id' => isset($data['user_id']) ? $data['user_id'] : $record->user_id,
                    'expeditioner_id' => isset($data['expeditioner_id']) ? $data['expeditioner_id'] : $record->expeditioner_id,
                    
                    'first_name' => isset($data['first_name']) ? $data['first_name'] : $record->first_name,
                    'last_name' => isset($data['last_name']) ? $data['last_name'] : $record->last_name,
                    'age' => isset($data['age']) ? $data['age'] : $record->age,
                    'dpi_passport' => isset($data['dpi_passport']) ? $data['dpi_passport'] : $record->dpi_passport,
                    'phone' => isset($data['phone']) ? $data['phone'] : $record->phone,
                    'source' => isset($data['source']) ? $data['source'] : $record->source,
                    
                    'collegiate' => @$data['collegiate'],
                    'emergency_contact' => @$data['emergency_contact'],
                    'emergency_contact_number' => @$data['emergency_contact_number'],
                    
                    'updated_at' => current_time('mysql', 1)
                        ], 
                
                array('id' => (int)(isset($data['id']) ? $data['id'] : $record->id) ),
                
                ['%d', '%d', '%d', '%d',       '%s', '%s', '%s', '%s', '%s','%s',     '%s']);
        
        if ( $modified_response ){
            $_id = (int)(isset($data['id']) ? $data['id'] : $record->id);
            return self::getTourExpeditioner($_id);
        }
        
        return $modified_response;
    }
    
    /**
     * Deletes a record from user TourExpeditioner table
     * 
     * @global type $wpdb
     * @param int $id the ID to delete
     * @return type
     */
    public static function deleteTourExpeditionerRecord($id) {
        global $wpdb;
        return $wpdb->delete( TOURS_EXPEDITIONERS, array( 
            'id' => $id
            ), array( '%d' ) );
    }
    
}