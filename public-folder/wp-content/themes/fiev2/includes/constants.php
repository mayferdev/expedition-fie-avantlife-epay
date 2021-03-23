<?php

// Useful global constants
define('EXPEDITION_API_VERSION', '0.0.1');
define('EXPEDITION_DEBUG', true);
define('EXPEDITION_PRODUCTION_API_VERSION', 'v1');

define('EXPEDITION_GOOGLE_MAPS_API_KEY', 'AIzaSyBjEJ1On1y110fdEgm6N4WAigW8fWP9rls');
//define('EXPEDITION_GOOGLE_MAPS_API_KEY', 'AIzaSyAR1HbkGDHKSvfPIVVAP8BPejOFJ32nSKo'); // previous api key

function setup_acf_google_maps() {
    acf_update_setting('google_api_key', 'AIzaSyDZ84sentSvPiM1N_KdSymoJ9siTOasyYU');
}

add_action('acf/init', 'setup_acf_google_maps');

// Cofidi, used to get NIT info for Guatemala users
define('COFIDI_NIT_USER', 'EXPEDITION');
define('COFIDI_NIT_PASSWORD', '33A865B7-699C-41A2-A79A-BB7AB7B0EA9C');
define('COFIDI_NIT_URL', 'http://www.cofidi.com.gt:8020/NIT/ConsultaNIT.asmx/getNIT');

// Cofidi, for ebills, used for Guatemala bills
define('COFIDI_BILL_WS_URL', 'http://www.cofidi.com.gt:8020/WSCOFIDI/WSCOFIDI.asmx?WSDL');

// Cybersource
// development
    define('CYBERSOURCE_API_URL', 'https://testsecureacceptance.cybersource.com/silent/pay');
    define('CYBERSOURCE_SECRET_KEY', 'e949c123aef34a7d828c7ef743c36f657a536e2ae4fc4943a98ffc23a89ea2a8fbc63d2158dd4727a23a9312e34f23af4fd1f0689b9a41eb8d78763a95e5c8fde128add5f7a1424db40a1543b274e67f85f2f6ba1c8744578a0452d08582b9c13629450595b64227adef6a3a60fdceb2bd1efdd67e7145cea6a0b74e581d7424');
    define('CYBERSOURCE_ACCESS_KEY', '4dd6503968bb3f6980f15129671f976d');
    define('CYBERSOURCE_PROFILE_ID', '3DCA3D4C-A18F-43EE-9735-5C68F963B6EC');
    define('CYBERSOURCE_MERCHANT_ID', 'visanetgt_expedition');
// production
    define('CYBERSOURCE_API_URL_PRODUCTION', 'https://secureacceptance.cybersource.com/silent/pay');
    define('CYBERSOURCE_SECRET_KEY_PRODUCTION', '9b2f3ebb061c43dcbb6079ea2f20cfabd21a92b614f94f909502bf50b89e9f6a8bc1f5a88e2d4dd79684511823ea818ad8e4959a561249bbb3033fb800fb6192b8f99dfff7cf4503a2789a6e92b4e7c598f9833ae696482880f45d12d719ac99f6ccc276f5784e1f8fbd924a0e5c8c1ca8f406fd6b43490aadb85b269e95e2dd');
    define('CYBERSOURCE_ACCESS_KEY_PRODUCTION', '59589c62fcdd332781bdaba773292fb6');
    define('CYBERSOURCE_PROFILE_ID_PRODUCTION', '904C8C6C-A437-4F0D-84D0-0DBF3C946E24');
    define('CYBERSOURCE_MERCHANT_ID_PRODUCTION', 'visanetgt_expedition');

define('USER_INACTIVE', 0);
define('USER_ACTIVE', 1);
define('USER_DISABLED', 2);

// TOUR STATUS
define('TOUR_PENDING', 0);
define('TOUR_ACCEPTED', 1);
define('TOUR_REJECTED', 2);

// BOOKING STATUS
define('BOOKING_PENDING', 0);
define('BOOKING_CONFIRMED', 1);
define('BOOKING_PENDING_CONFIRM', 2);
define('BOOKING_REJECTED', 3);
define('BOOKING_CHECKED_IN', 4);
define('BOOKING_CONFIRMED_CARD', 5);
define('BOOKING_CANCELLED', 6);

// TRANSACTION STATUS
define('TRANSACTION_ERROR', 0);
define('TRANSACTION_SUCCESS', 1);
define('TRANSACTION_PENDING', 2);

// TRANSACTION GATEWAY
define('GATEWAY_DEPOSIT', 'deposit');
define('GATEWAY_VISANETGT', 'visanetgt');


// SESSIONS
define('SESSION_ACTIVE', 1);
define('SESSION_INACTIVE', 0);


// DB TABLES
global $wpdb;
define('USER_TOUR_INVITATIONS_TABLE', $wpdb->prefix.'user_tour_invitations');
define('USER_BOOKINGS_TABLE', $wpdb->prefix.'user_bookings');
define('TRANSACTIONS_TABLE', $wpdb->prefix.'transactions');
define('WHAT_TO_BRING_TABLE', $wpdb->prefix.'what_to_bring');
define('TOURS_PUBLISHED_IN_MONTH_TABLE', $wpdb->prefix.'tours_published_in_month');
define('GOOGLE_PLACES_TABLE', $wpdb->prefix.'google_places');
define('TOURS_EXPEDITIONERS', $wpdb->prefix.'tours_expeditioners');
define('USER_CARDS_TABLE', $wpdb->prefix.'user_cards');
define('FOLLOWERS_TABLE', $wpdb->prefix.'followers');
define('ACTIVITIES_TABLE', $wpdb->prefix.'activities');
define('SAVED_POSTS_TABLE', $wpdb->prefix.'saved_posts');
define('VIEWED_POSTS_TABLE', $wpdb->prefix.'viewed_posts');
define('LIKED_POSTS_TABLE', $wpdb->prefix.'liked_posts');
define('BILLS_TABLE', $wpdb->prefix.'bills');


// define('FB_SDK_CONFIG', [
//     'app_id' => '402044240368564',
//     'app_secret' => '1e7daedbd80d9f138d659233bfce1c2a',
//     'default_graph_version' => 'v2.5',
// ]);

define('PAGALO_URL', 'https://app.pagalocard.com/api/v1');
define('PAGALO_TOKEN', '6y3H9ms1j9aisFF0fUIg');
define('PAGALO_PUBLIC_KEY', 't46V9WSwEkfIdRqyezDvXJqK0YW6UPHP5Vucn5BU');
define('PAGALO_SECRET_KEY', 'Yys6AuPVdikTnXHXx9dPjpU9OGpOcNsX3avKZH5U');
define('PAGALO_ID_EN_EMPRESA', 'E151730706');
