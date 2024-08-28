<?php

// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
// END iThemes Security - Do not modify or remove this line

define( 'ITSEC_ENCRYPTION_KEY', 'PDUgY0QvKzk9JV1lMzUwKHcjO3ZbLn5FM2AhYDA4RXhQTHtQPHBCdnA0LU5PYHF3IHdQXnwxdWVMcSt3RnpjYA==' );

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'webbanhang_pd' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '##1WCCCz9_h{7|B3J|9cC-abYFB6nq4{P?PrD7S4phbVbGe#TFl3`h[K/pe)LI1G' );
define( 'SECURE_AUTH_KEY',  'TsKhmr_d!t# 6z6-aI26?pl .3{<d8@Zw}mPvIQZ!%T-EUnrt_D$1po?Zt2i.y0`' );
define( 'LOGGED_IN_KEY',    'Bq@c^]KI_ $//:CS! ljnkw,kXpa#BU(pKb`w4_R)DvIdfXt_ca{1G!5(!oDbvik' );
define( 'NONCE_KEY',        ':iVStV1~}MR%V+WkuNV:G&cF%tj;D`Mzt`=o#r23ur;u^y[n-5ts4:|P%!N)p(.[' );
define( 'AUTH_SALT',        't2Sz?kt+l/=}s#=rbER#O`J5])GFQ|3~H:Ngj~uDH+i3/B/e%L=rbEi*YhODvS{W' );
define( 'SECURE_AUTH_SALT', 'ew:WJYu3>hBg<[^QE6%iEK22f/Id5J{wkypJD^sy?8kd-Xtw?ePCuK%ZrY@`?LWU' );
define( 'LOGGED_IN_SALT',   'a*iymbZi$uG6#9ra,B}]wf2Rn%<wLu>_-!.y[w>Jwvuo%;4v(40!nu0pn2!#LtEd' );
define( 'NONCE_SALT',       '^Wsv^%F6tcpLUm%f6@F(eT}<h@WFuVR|9fe,}Qg* *!SoJ#{#m$Q PaA|P]C/80R' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
