<?php

add_filter( 'expedition_api_error_message', 'expedition_api_filter_rest_api_message', 99, 1 );

/**
 * Filter error messages in rest API response
 * 
 * @param type $message
 * @return string
 */
function expedition_api_filter_rest_api_message($message){
    if (strpos($message, "The username or password you entered is incorrect") !== false  ){
        $message = "The username or password you entered is incorrect";
    }
    else if (strpos($message, "The password you entered for the email address") !== false  ){
        $message = "The password you entered is incorrect";
    }
    else if (strpos($message, "Invalid parameter(s): email") !== false  ){
        $message = "Invalid email address";
    }
    else if (strpos($message, "Missing parameter") !== false  ){
        $message = "Missing parameter(s)";
    }
    else if (strpos($message, "Invalid parameter") !== false  ){
        $message = "Invalid parameter(s)";
    }
    else if (strpos($message, "Invalid email address") !== false  ){
        $message = "Invalid email address";
    }
    else if (strpos($message, "The password field is empty") !== false  ){
        $message = "The password field is empty";
    }
    
    return $message;
}


add_filter( 'rest_request_before_callbacks', 'expedition_filter_rest_api_response', 99, 3 );

/**
 * Add filter previous to serve response of rest API
 * 
 * @param type $response
 * @param type $handler
 * @param type $request
 * @return type
 */
function expedition_filter_rest_api_response($response, $handler, $request){
    
    if (is_wp_error($response)){
        
        $errors = $response->errors;
        reset($errors);
        $first_key = key($errors);
        $error_array = $errors[$first_key];
        $hooked =  false;
        
        $message = apply_filters('expedition_api_error_message', $error_array[0]);
        
        if ( $message != $error_array[0] ){
            $hooked = true;
        }
        
        if ($hooked){
            $errors[$first_key] = $error_array;
            $response->errors = $errors;
        }
        
    }
    
    return $response;
}