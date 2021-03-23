<?php

class base_API extends WP_REST_Controller {
    
    public $user = null;
    
    /**
     * Check if the passed header token is valid
     * 
     * @param type $request
     * @return boolean
     */
    public static function check_token($request){
        $token = self::get_token();
        $decode = FirebaseHelper::decode_token($token);
        
        if ( !isset($decode->data) ){
            return false;
        }
        
        $data = self::get_token_data();
        $user_id = (int)$data->ID;
        $user = get_user_by( 'id', $user_id );
        
        if ( !$user ){
            return false;
        }
        
        $this->user = Expedition_Helper::getUser($user_id, true);
        
        return true;
    }
    
    /**
     * Check if the passed header token is valid and the user is an expeditioner
     * 
     * @param type $request
     * @return boolean
     */
    public static function check_token_expeditioner($request){
        $token = self::get_token();
        $decode = FirebaseHelper::decode_token($token);
        
        if ( !isset($decode->data) || !isset($decode->data->role) || $decode->data->role != "expeditioner" ){
            return false;
        }
        
        $data = self::get_token_data();
        $user_id = (int)$data->ID;
        $expeditioner = get_user_by( 'id', $user_id );
        
        if ( !$expeditioner ){
            return false;
        }
        
        $this->user = Expedition_Helper::getUser($user_id, true);
        
        return true;
    }
    
    /**
     * Check if the passed header token is valid and the user is a business
     * 
     * @param type $request
     * @return boolean
     */
    public static function check_token_business($request){
        $token = self::get_token();
        $decode = FirebaseHelper::decode_token($token);
        
        if ( !isset($decode->data) || !isset($decode->data->role) || $decode->data->role != "business" ){
            return false;
        }
        
        $data = self::get_token_data();
        $user_id = (int)$data->ID;
        $business = get_user_by( 'id', $user_id );
        
        if ( !$business ){
            return false;
        }
        
        $this->user = Expedition_Helper::getUser($user_id, true);
        
        return true;
    }
    
    /**
     * Returns the passed header token
     * 
     * @return type
     */
    public static function get_token(){
        $token = @$_SERVER['HTTP_TOKEN'];
        return $token;
    }
    
    /**
     * Returns the decrypted data in the passed header token
     * 
     * @return array | false
     */
    public static function get_token_data(){
        $token = self::get_token();
        $decode = FirebaseHelper::decode_token($token);
        if ( isset($decode->data) ){
            return $decode->data;
        }
        return false;
    }
    
}