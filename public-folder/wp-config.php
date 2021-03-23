<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'avantlife_wp66' );

/** MySQL database username */
define( 'DB_USER', 'avantlife_wp66' );

/** MySQL database password */
define( 'DB_PASSWORD', '@Y7p3P[()1SABX' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'pybxnq6vebqrhutur3sxby8zbi7krhlvsgsuy6kdnyptcagvppacikdgtpg9btqn' );
define( 'SECURE_AUTH_KEY',  'yplx5qwjtqq4s7cg2ao6fuepm9ti3rbisaefkg17ch5scfdtzrvf5uwulhyz8d9w' );
define( 'LOGGED_IN_KEY',    'j7ci2qa4uwuqegyuffpymqwvd8rlznwg164wngiudv8bhprlzmrsydlif75hs09s' );
define( 'NONCE_KEY',        'nxnyfpvbyplq8kyuexq34heglpk8ul9i50kn57ijitp63cyrocg3vfxkgbqmrajs' );
define( 'AUTH_SALT',        '5woiow7ot7daao9a36wc4mzgy5pkjat44flrn4ad9tifugo9cixenoxcsox6bijg' );
define( 'SECURE_AUTH_SALT', 's6pclsdifzxcqcptot2fza4jrkhrrqemeiahskdqrycnhyiacjckl6fdi7tgtlvk' );
define( 'LOGGED_IN_SALT',   'i5emnzwympius0fzliekoucnclmnhoe2jpjcxupld5sh4b6telnrj2s1kdknhgql' );
define( 'NONCE_SALT',       'chjvkjeuq8sbop5ddr4txyhvhks1ojqi4rlmxms7dz2lqzk5zauzuqdwmkquw4gk' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpxj_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', true );



define( 'WPOSES_AWS_ACCESS_KEY_ID',     'AKIA4TXJHNMGDSYA2SAU' );
define( 'WPOSES_AWS_SECRET_ACCESS_KEY', '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' );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
